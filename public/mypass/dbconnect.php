<?php
$db_host = "127.0.0.1";
if ($_SERVER['SERVER_ADDR'] == '89.42.216.241')
  $db_user = "raut5771_autospa";
else
    $db_user = "autospa";

if ($_SERVER['SERVER_ADDR'] == '89.42.216.241')
  $db_name = "raut5771_autospa";
else
    $db_name = "autospa";

$db_pass = "c4rw45H1";

mysql_connect($db_host,$db_user,$db_pass);

mysql_select_db($db_name) or die("ERROR - TABLE");
