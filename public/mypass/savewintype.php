<?php
require_once('dbconnect.php');

if (isset($_POST['code']) && isset($_POST['text'])) {
    $query = "UPDATE mypass_codes SET winitem = '" . $_POST['text'] . "' WHERE code = '".$_POST['code']."'";
    mysql_query($query);
    
}