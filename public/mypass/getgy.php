<?php
require_once('dbconnect.php');

if (isset($_POST['code'])) {
    $query = "SELECT userid from mypass_codes WHERE code = '".$_POST['code']."'";
	$resource = mysql_query($query);
    $result = mysql_fetch_row($resource);
	echo ($result[0]);
}