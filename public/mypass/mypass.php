<?php
session_start();

require_once('dbconnect.php');

function displayWinitemsBlock($query)
{
	$resource = mysql_query($query);
	while($result = mysql_fetch_array($resource)) {
		?>
		<div>
			<?php echo $result['code']; ?> - <input type="text" class="wintype" value="<?php echo $result['winitem'] ?>" id="wintype_<?php echo $result[0]; ?>" />
		</div>
		<?php
	}
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
			$(".wintype").keyup(function() {
				$.post('savewintype.php', {code : $(this).attr('id').replace('wintype_', ''), text : $(this).val() });
			});
		   
			$("#continue_script").click(function() {
				var r = confirm("Mielott folytatnad, ellenorizd, hogy nem fut-e egy masik felhasznalora!");
				if (r == true) {
					window.location.href= 'mypass.php?userid=<?php echo $_GET['userid']; ?>&reset=1'
				}		   
			});
		});
	</script>
	<?php
}

function getSecWithDiff($serverTimeDifference)
{
	return (60 + date('s') + $serverTimeDifference) % 60;
}

/**
 * Az 1-es user running mezojeben tarolodik el az eppen futo userid.
 * @param bool $onoff
 */
function setUserRunning($onoff)
{
	if ($onoff == true) {
		$query = "UPDATE mypass_user SET running = ".$_GET['userid']." WHERE id=1";
		mysql_query($query);		
	} else {
		$query = "SELECT running FROM mypass_user WHERE id = 1";
		$resource = mysql_query($query);
		$result = mysql_fetch_row($resource);
		if ($result[0] == $_GET['userid']) {
			$query = "UPDATE mypass_user SET running = 0 WHERE id=1";
			mysql_query($query);
		}
	}
}
//$_SESSION['submitted'] = 0;


/**
 * $serverTimeDifference
 * ennyi masodperc eltolodas van a sajat gep es a freedom15 ideje kozott.
 * Hogy meg tujuk ezt hatarozni, a writewintime.php scriptet kell futtatni akar tobb oran keresztul,
 * 
 */

$serverTimeDifference = -7; //elotte -3 volt, de lelassult

if ($_GET['userid'] == 1)
	$cigaretteMaxWin = 15;
else
	$cigaretteMaxWin = 15;

if (!isset($_GET['userid']))
    die('User ID not set!');

if (isset($_GET['reset']) && $_GET['reset'] == 1) {
	unset($_SESSION['cigaretteWinCounts']);
	unset($_SESSION['scriptStart']);
	unset($_SESSION['submittedCount']);
	
	$query = "SELECT COUNT(*) as cnt FROM mypass_codes WHERE userid =".$_GET['userid']." AND winitem='c'";
	$resource = mysql_query($query);
	$result = mysql_fetch_row($resource);
	
	if ($result[0] < $cigaretteMaxWin) {
		$query = "UPDATE mypass_user SET IsLocked = 0 WHERE id = ".$_GET['userid'];
		mysql_query($query);
	}
}

if (isset($_GET['force']) && $_GET['force'] == 1) {
	setUserRunning(true);
}

/**
 * Az adatbazisbol kiszejuk, hogy hany cigit nyert eddig a felhasznalo.
 * Az aktualis kampanyban 15-ot nyerhet maximum, ha ezt a szamot elertuk, akkor nem futtatjuk
 */
if (!isset($_SESSION['cigaretteWinCounts'])) {
	$query = "SELECT COUNT(*) as cnt FROM mypass_codes WHERE userid =".$_GET['userid']." AND winitem='c'";
	$resource = mysql_query($query);
	$result = mysql_fetch_row($resource);
	$_SESSION['cigaretteWinCounts'] = $result[0];
}

if (!isset($_SESSION['scriptStart'])) {
	$_SESSION['scriptStart'] = date('Y-m-d H:i:s');
}

if ($_SESSION['cigaretteWinCounts'] == $cigaretteMaxWin) {
	echo "Ez a felhasznalo mar nyert ".$cigaretteMaxWin." cigit!";	
	setUserRunning(false);
	die;
}

if (!isset($_SESSION['submittedCount'])) {
	$_SESSION['submittedCount'] = 0;
}


$time = time();

$dayStart = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d 00:00:00'));
$dayEnd = DateTime::createFromFormat('Y-m-d 23:59:59', date('Y-m-d 23:59:59'));

//if ($time > strtotime(date('Y-m-d 19:30:00'))) {
//    $dayStart->modify('+1 day');
//    $dayEnd->modify('+1 day');
//}

$dayStart = $dayStart->format('Y-m-d H:i:s');
$dayEnd = $dayEnd->format('Y-m-d H:i:s');

$query = "SELECT COUNT(*) as cnt FROM mypass_codes WHERE userid IS NULL";
$resource = mysql_query($query);
$result = mysql_fetch_row($resource);
$totalNew = $result[0];
if($totalNew == 0)
    die('No more codes available!');

$executePage = false;

$userid = $_GET['userid'];

$query = "SELECT running FROM mypass_user WHERE id=1";
$resource = mysql_query($query);
$result = mysql_fetch_row($resource);
$running = $result[0];
if ($running > 0 && $running != $userid) {
	$executePage = false;
} else {
	setUserRunning(true);
	$executePage = true;
}

if ($executePage) {
	
    $query = "SELECT COUNT(*) FROM mypass_codes WHERE userid=" . $_GET['userid'] . " AND date BETWEEN '$dayStart' AND '$dayEnd'";
	
    $resource = mysql_query($query);
    $result = mysql_fetch_row($resource);	
	
    if ($result[0] < 10) {		
		//lehetseges, hogy elertuk a max 15 ciginyeremenyt
		if ($_SESSION['cigaretteWinCounts'] + $_SESSION['submittedCount'] == $cigaretteMaxWin) {
			$query = "UPDATE mypass_user SET IsLocked = 1 WHERE id = ".$_GET['userid'];
			mysql_query($query);
			setUserRunning(false);

			echo "Lehetseges, hogy elerted a max $cigaretteMaxWin ciginyeremenyt!";
				$query = "SELECT code, winitem FROM mypass_codes WHERE userid=" . $_GET['userid'] . " AND date > '".$_SESSION['scriptStart']."'";
				displayWinitemsBlock($query);	
			?>
			<button id="continue_script">Script folytatasa</button>
			<?php
			die;
		}
		
        if (getSecWithDiff($serverTimeDifference) < 8) {
            $_SESSION['submitted'] = 0;
        }

        if (!isset($_SESSION['submitted']))
            $_SESSION['submitted'] = 1;

        echo 'Total new codes: ' . $totalNew . '<br>';
        echo 'Submitted codes:' . $result[0]. '<br>';
        echo 'submitted:' . $_SESSION['submitted']. '<br>';
		
		$_SESSION['prevwin'] = isset($_SESSION['prevwin']) ? $_SESSION['prevwin'] : $time + 1;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, 'https://mypass.ro/winners/list/campaign_id/19/page/999999');
        curl_setopt($curl, CURLOPT_REFERER, 'https://mypass.ro/winners/list/campaign_id/19/page/999999');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $page_current = curl_exec($curl);
        curl_close($curl);

        $current_page_length = strlen($page_current);
        $diff = $time - $_SESSION['prevwin'];
		
        if ($current_page_length != $_SESSION['ln']) {
			$_SESSION['prevwin'] = $time;
        }		
		
		$_SESSION['ln'] = $current_page_length;
		
        echo 'Current secs: ' . date('i:s', strtotime(date('H:i:s')) + $serverTimeDifference).'<br>';
        echo  'Last win time: '.date('H:i:s', $_SESSION['prevwin'] + $serverTimeDifference).'<br>';
		echo 'cigaretteWinCounts:'.$_SESSION['cigaretteWinCounts'].'<br>';
        echo $diff . 'sec<br>';
		
		$startTime = strtotime(date('Y-m-d 02:58:00'));
        if (
                $time > $startTime //Ha mar kezdheti
				&& $time < strtotime(date('Y-m-d 07:59:00')) //opcionalis idolimit
                && date('H:i', $_SESSION['prevwin'] + $serverTimeDifference) < date('H:i', $serverTimeDifference + strtotime(date('H:i:s'))) //ha a legutobbi nyeres nem ebben a percben volt
                && getSecWithDiff($serverTimeDifference) >= 56//57 //4 secundumos ablak, ekkor kuldodik be a kod
                && $_SESSION['submitted'] == 0
            ) {
            $query = "SELECT code FROM mypass_codes WHERE userid IS NULL ORDER BY id ASC LIMIT 1";
            $resource = mysql_query($query);
            $result = mysql_fetch_row($resource);
            $code = $result[0];
            ?>
            <script>window.open('<?php echo "https://freedom15.ro/profile/load-code?code=$code&_=".($time + $serverTimeDifference) . "63" . rand(10, 99); ?>', '_blank');</script>
            <?php
            $_SESSION['submitted'] = 1;
            $query = "UPDATE mypass_codes SET userid = " . $_GET['userid'] . " WHERE code = '$code'";
            mysql_query($query);
			
			$_SESSION['submittedCount']++;
        }
        ?>
        <script>setTimeout(function() {window.location.href= 'mypass.php?userid=<?php echo $_GET['userid']; ?>&reset=0'}, 500);</script>
        <?php
    } else {
		unset($_SESSION['prevwin']);
		setUserRunning(false);
        echo 'User already submitted 10 codes today!';
		
		$query = "SELECT code, winitem FROM mypass_codes WHERE userid=" . $_GET['userid'] . " AND date BETWEEN '$dayStart' AND '$dayEnd'";
		displayWinitemsBlock($query);
		?>
		<script>
			var now = new Date();
			var millisTill10 = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 0, 0) - now;
			setTimeout(function(){window.location.href= 'mypass.php?userid=<?php echo $_GET['userid']; ?>&reset=0'}, millisTill10 + 65000);
		 </script>
	 <?php
    }
} else {
	$query = "SELECT COUNT(*) FROM mypass_user WHERE id >= $running AND id < $userid AND IsLocked = 0";
	$resource = mysql_query($query);
	$result = mysql_fetch_row($resource);
	if ($result[0] == 0)
		$setTimeout = 3000;
	else
		$setTimeout = 3000 + ($result[0] - 1) * 60000;
	
    echo "It is another user's turn! Retrying in ".($setTimeout/1000)." seconds...<br>";
	
	$query = "SELECT running FROM mypass_user WHERE id = 1";
	$resource = mysql_query($query);
	$result = mysql_fetch_row($resource);
	echo "Currently running user:" . $result[0]."<br>";
    ?>
	<button id="force_run">Force Run On This User</button>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
		setTimeout(function() {window.location.href= 'mypass.php?userid=<?php echo $_GET['userid']; ?>&reset=0'}, <?php echo $setTimeout; ?>);
		
		$(document).ready(function() {
			$("#force_run").click(function() {
				var r = confirm("Biztosan futtatni akarod?");
				if (r == true) {
					window.location.href= 'mypass.php?userid=<?php echo $_GET['userid']; ?>&reset=0&force=1'
				}		   
			});
		});
	</script>
    <?php
}
?>
