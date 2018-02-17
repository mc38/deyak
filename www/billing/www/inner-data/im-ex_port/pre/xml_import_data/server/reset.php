<?php
require_once("../../../../plugin/func/all_special.php");
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$f = $_POST['d'];
		
		
		$tdate = date('Y-m-d',$datetime);
		$data = "temp/".$tdate."/".$f.".xml";
		
		if(file_exists($data)){
			unlink($data);
		}
		
		$dir = scandir("temp/");
		for($i=0;$i<sizeof($dir);$i++){
			if(is_dir("temp/".$dir[$i])){
				if(strtotime($dir[$i]) < strtotime('-2 day', strtotime($tdate))){
					deleteDir("temp/".$dir[$i]);
				}
			}
		}
		
		
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>