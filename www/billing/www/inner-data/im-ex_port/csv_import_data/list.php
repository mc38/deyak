<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$s = json_decode(base64_decode($_GET['s']));
	
	$f = $s[0];
	
	date_default_timezone_set('Asia/Kolkata');
	$tdate = date('Y-m-d',$datetime);
	
	$file = "server/temp/".$tdate."/".$f.".csv";
	if(file_exists($file)){
		$data = file($file);
		require_once("list/type.php");
	}
	else{
		echo "File not Found";
	}	
}
else{
	echo "Unauthorized user";
}
?>