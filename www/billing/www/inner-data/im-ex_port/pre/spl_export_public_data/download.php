<?php
ini_set('max_execution_time', 10000);
require_once("plugin/func/all_special.php");
require_once("db/command.php");

	
	if($_POST){
	if(
		isset($_POST['n']) && $_POST['n']!="" 
		&& 
		isset($_POST['d']) && $_POST['d']!="" 
		&& 
		isset($_POST['b']) && $_POST['b']!="" 
		&& 
		isset($_POST['xd']) && $_POST['xd']!=""
		&& 
		isset($_POST['xf']) && $_POST['xf']!=""
		&& 
		isset($_POST['xt']) && $_POST['xt']!=""
		&& 
		isset($_POST['pd'])
		&& 
		isset($_POST['pt']) 
		&& 
		isset($_POST['ct']) && $_POST['ct']!=""  
	){
		
		/////////////////////////////
		$tdate = date('Y-m-d',$datetime);
		
		clearstatcache();
		$dir = scandir("temp/");
		for($i=0;$i<sizeof($dir);$i++){
			if(is_file("temp/".$dir[$i]) && file_exists("temp/".$dir[$i])){
				if(fileatime("temp/".$dir[$i]) < strtotime('-2 day', strtotime($tdate))){
					unlink("temp/".$dir[$i]);
				}
			}
		}
		
		/////////////////////////////
		
		
		$d = $_POST['n'];
		$s = $_POST['d'];
		$b = $_POST['b'];
		
		$xd = $_POST['xd'];
		$xf = $_POST['xf'];
		$xt = $_POST['xt'];
		$pd = $_POST['pd'];
		$pt = $_POST['pt'];
		$ct = $_POST['ct'];
		
		if($d){
			require_once("down/type".$d.".php");
		}
	}
	else{
		echo 'Error';
	}
	}
	else{
		echo 'Error';
	}

?>