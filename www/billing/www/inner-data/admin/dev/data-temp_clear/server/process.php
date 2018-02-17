<?php
ini_set('max_execution_time', 10000);
require_once("../../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c'])
		&&
		$_POST['c'] !="" 
	){
		
		date_default_timezone_set('Asia/Kolkata');
		$datetime=date($_SERVER['REQUEST_TIME']);
		
		$del_date = strtotime(date('d-m-Y',$datetime));
		
		$dir_1 = "../../../../../temp";
		dir_del($dir_1,$del_date);
		
		
		$dir_2 = "../../../../../inner-data/im-ex_port/csv_import_data/server/temp";
		dir_del($dir_2,$del_date);
		
		echo $_POST['c'];
	}
}
else{
	echo 0;
}

function dir_del($path,$btime){
	$dir = scandir($path);
	for($i=2;$i<sizeof($dir);$i++){
		if(is_dir($path."/".$dir[$i]) && file_exists($path."/".$dir[$i])){
			dir_del($path."/".$dir[$i],$btime);
			if(filectime($path."/".$dir[$i]) < $btime ){
				rmdir($path."/".$dir[$i]);
			}
		}
		else if(is_file($path."/".$dir[$i]) && file_exists($path."/".$dir[$i])){
			if(fileatime($path."/".$dir[$i]) < $btime ){
				unlink($path."/".$dir[$i]);
			}
		}
	}
}
?>