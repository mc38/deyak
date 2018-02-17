<?php
date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);
	
$host = "localhost";
$user = "apdcl";
$password ="apdclpass14092016";
$database = "electricity_apdcl";

$link = mysql_connect($host,$user,$password);
$db = mysql_select_db($database,$link);

?>