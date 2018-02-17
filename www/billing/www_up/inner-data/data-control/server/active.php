<?php
include "../../../db/command.php";
session_start();
$min = 10;
$diff = ($datetime - $_SESSION['t'])/60;
if($diff>$min){
	$_SESSION['us'] = "";
	session_destroy();
	echo 1;
}
?>