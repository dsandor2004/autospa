<?php
/**
 * A freedom15.ro oldalrol leszedi a nyeresi idoket masodpercre pontosan.
 * Akar tobb orat is kell futtatni, amig eleg nyeresi ido osszegyul ahhoz, hogy meg tudjuk hatarozni,
 * hogy hol a perc vege. Ehhez a displaywins.php scriptet kell futtatni.
 * 
 * Megjegyzes:
 * A futtatas megkezdese elott, ha nem erdekelnek a korabbi elmentett idok, manualisan ki kell torolni a wins.txt-t
 * http://autospamures.ro/admin/public/mypass/writewintime.php
 */

$time = time();
$minsec = date('Y-m-d H:i:s');


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, 'https://mypass.ro/winners/list/campaign_id/61/page/999999');
    curl_setopt($curl, CURLOPT_REFERER, 'https://mypass.ro/winners/list/campaign_id/61/page/999999');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $page_current = curl_exec($curl);
    curl_close($curl);

$prevwin = isset($_GET['prevwin']) ? $_GET['prevwin'] : $time + 1;

$ln = isset($_GET['ln']) ? $_GET['ln'] : 0 ;

$current_page_length = strlen($page_current);
$diff = $time - $prevwin;
    
if ($current_page_length != $ln) {
        $wintime = $minsec . ' - ' . $diff;
        $prevwin = $time;
        $wins = fopen('wins.txt', 'a');
        fwrite($wins, $wintime . "\r\n");
        fclose($wins);
}
    
?>

<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    </head>
    <body>
        <!--<audio preload='auto' id="aud" controls src='ram.mp3'></audio>-->
    </body>
    <script>
        $(document).ready(function() {
            setTimeout(function() {window.location.href= 'writewintime.php?ln=<?php echo $current_page_length ?>&prevwin=<?php echo $prevwin ?>&lasttime=<?php echo $time ?>'}, 500);
        });
    </script>
</html>
