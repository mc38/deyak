<?php
date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);
	
$host = "localhost";
$user = "deyak_testing";
$password ="password";
$database = "electricity_testing";

$link = mysql_connect($host,$user,$password);
$db = mysql_select_db($database,$link);

?>