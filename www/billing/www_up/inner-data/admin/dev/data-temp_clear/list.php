<?php
require_once("../../../../plugin/func/authentication.php");

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

if(authenticate()){
	
	$dir_1 = "../../../../temp";
	echo '<hr/>temp';
	dir_scan($dir_1,"");
	
	
	$dir_2 = "../../../../inner-data/im-ex_port/csv_import_data/server/temp";
	echo '<hr/>csv_import_data/temp';
	dir_scan($dir_2,"");
}
else{
	echo "Unauthorized user";
}


function dir_scan($path,$gap){
	$dir = scandir($path);
	$gap = $gap .'      ';
	for($i=2;$i<sizeof($dir);$i++){
		echo '<pre>'.$gap.'|- '.$dir[$i] .'  [-> ';
		if(is_file($path."/".$dir[$i]) && file_exists($path."/".$dir[$i])){
			echo date("d-m-Y h:i:s a", fileatime($path."/".$dir[$i])) .' ]</pre>';
		}
		else if(is_dir($path."/".$dir[$i]) && file_exists($path."/".$dir[$i])){
			echo date("d-m-Y h:i:s a", filectime($path."/".$dir[$i])) .' ]</pre>';
			dir_scan($path."/".$dir[$i],$gap);
		}
	}
}
?>