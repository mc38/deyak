<?php
ini_set('max_execution_time', 10000);
require_once("../../../../plugin/func/all_special.php");
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$d = $data[0];
		$f = $data[1];
		
		$tdate = date('Y-m-d',$datetime);
		$dfile = "temp/".$tdate."/".$f."";
		$dbh = new PDO("sqlite:".$dfile);
		
		if($dbh){
			require_once("import/add.php");
			
			
			$dir = scandir("temp/");
			for($i=0;$i<sizeof($dir);$i++){
				if(is_dir("temp/".$dir[$i])){
					if(strtotime($dir[$i]) < strtotime('-2 day', strtotime($tdate))){
						deleteDir("temp/".$dir[$i]);
					}
				}
			}
			
			
		}
		else{
			echo "1";
		}
	}
}
else{
	echo 0;
}
?>