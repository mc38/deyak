<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$s = json_decode(base64_decode($_GET['s']));
	
	$f = $s[0];
	$a = $s[1];
	
	date_default_timezone_set('Asia/Kolkata');
	$tdate = date('Y-m-d',$datetime);
	
	$dbh = new PDO("sqlite:server/temp/".$tdate."/".$f);
	if($dbh){
		require_once("list/type.php");
		
		$dbh=null;
	}
	else{
		echo "File not Found";
	}	
}
else{
	echo "Unauthorized user";
}
?>