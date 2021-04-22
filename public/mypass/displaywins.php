<!--
Az elmentett nyeresi idoket rajzolja cellakba.

Ezen script segitsegevel meg lehet hatarozni az idoeltolodast masodpercre pontosan a freedom15.ro es a lokalis
ido kozott es azt, hogy hol a perc vege.
60 px szelessegu cellablokkokat rajzolunk fel, minden cella 1 percnek felel meg.
A nyeresi idoket egy-egy ponttal rajzoljuk be a cellak koze.
A $serverTimeDifference valtozot ugy allitjuk, hogy egy cellan belul mindig csak 1 nyeresi ido legyen.
Ha ez sikerult, akkor megkaptuk az idoeltolodast es meg tujuk hatarozni a perc veget.

Megjegyzes: Megtortenhet, hogyhalozati, kapcsolodasi stb. problemak miatt egy egy zaj is bekerul az adatok koze,
tehat barmennyire is allitjuk a $serverTimeDifference valtozot, lesz 1-2 cella, amelybe megis 2 nyeresi ido kerul.
Ezt tudva, ezeket a zajokat nem vesszuk figyelembe, altalaban meghatarozhato a nyeresi ido ennek ellenere is.
Esetleg ujra mintat lehet venni, amig zajmentes adatokat sikerul elmenteni.

-->
<style>
	body {
		margin:0;
		border:0;
	}
    .minute {
        width: 59px;
        border-left: 1px solid black;
        float:left;
    }
</style>
<html>
    <body>
        <div style="width:100000px">
            <?php
//			die(date("H:i:s", 1444089716));
			$displayBlockSize = 2075;
            for ($i=0;$i<$displayBlockSize;$i++) {
                ?>
                <div class="minute">
                    &nbsp;
                </div>
                <?php
            }            
            ?>
        </div>
        
        <?php
            $wins = fopen('wins.txt', 'r');
            $go = false;

            
            $start = strtotime('2017-10-06 18:18:01'); //a wins.txt elso sora, :01 masodperccel

            $c = 0;
			$serverTimeDifference = 0;
            while (!feof($wins)) {
                $s = fgets($wins);
                if (strstr($s, '2017-10-06 18:18:11')) //a wins.txt elso sora
                    $go = true;

                if($go) {
                    $c++;
                    $timeString = substr($s, 0, 19);
					$time = strtotime($timeString);
                    ?>
		<div style="color:red;position:absolute;margin-left:<?php echo $time - $start + $serverTimeDifference ?>">.<?php  echo (60 + date('s', $time) + $serverTimeDifference) % 60 ?> </div>
                    <?php
//                    echo strtotime($time) - $start.'<br>';
                }
                
                if ($c == $displayBlockSize)
                    break;
            }
            fclose($wins);        
        ?>
    </body>
</html>