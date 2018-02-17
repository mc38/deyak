<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$s = json_decode(base64_decode($_GET['s']));
	
	$f = $s[0];
	$d = $s[1];
	
	date_default_timezone_set('Asia/Kolkata');
	$tdate = date('Y-m-d',$datetime);
	
	$data = simplexml_load_file("server/temp/".$tdate."/".$f.".xml");
	if($data){
		require_once("list/type".$d.".php");
	}
	else{
		echo "File not Found";
	}	
}
else{
	echo "Unauthorized user";
}
?>