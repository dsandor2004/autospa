<?php
session_start();

require_once('dbconnect.php');
   
//1425259736986
//1425262625
//https://freedom15.ro/profile/load-code?code=byjjapfl3&_=1425259736977


//ECHO strtotime('2015-03-07 00:27:00') . '<br>';

//$_SESSION['submitted'] = 0;

$time = time();

$dayStart = date('Y-m-d 00:00:00');
$dayEnd = date('Y-m-d 23:59:59');


        if (date('s') < 8) {
            $_SESSION['submitted'] = 0;
        }

        if (!isset($_SESSION['submitted']))
            $_SESSION['submitted'] = 1;

        echo 'submitted:' . $_SESSION['submitted']. '<br>';

        $prevwin = isset($_GET['prevwin']) ? $_GET['prevwin'] : $time + 1;

        $ln = isset($_GET['ln']) ? $_GET['ln'] : 0 ;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, 'https://mypass.ro/winners/list/campaign_id/15/page/999999');
        curl_setopt($curl, CURLOPT_REFERER, 'https://mypass.ro/winners/list/campaign_id/15/page/999999');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $page_current = curl_exec($curl);
        curl_close($curl);

        $current_page_length = strlen($page_current);
        $diff = $time - $prevwin;

        if ($current_page_length != $ln) {
            $prevwin = $time;
        }

        echo 'Current secs: ' . date('i:s').'<br>';
        echo  'Last win time: '.date('H:i:s', $prevwin).'<br>';
        echo $diff . 'sec<br>';

        if ($diff > 58 && date('s') >= 55 && date('s') < 57 && $_SESSION['submitted'] == 0) {
            $query = "SELECT code FROM mypass_codes WHERE winitem IS NULL AND again IS NULL ORDER BY id ASC LIMIT 1";
            $resource = mysql_query($query);
            $result = mysql_fetch_row($resource);
            $code = $result[0];
            ?>
            <script>window.open('<?php echo "https://freedom15.ro/profile/load-code?code=$code&_=$time" . "63" . rand(10, 99); ?>', '_blank');</script>
            <?php
            $_SESSION['submitted'] = 1;
            $query = "UPDATE mypass_codes SET again = 1 WHERE code = '$code'";
            mysql_query($query);
        }
        ?>
        <script>setTimeout(function() {window.location.href= 'checknull.php?userid=<?php echo $_GET['userid']; ?>&ln=<?php echo $current_page_length ?>&prevwin=<?php echo $prevwin ?>&lasttime=<?php echo $time ?>'}, 500);</script>