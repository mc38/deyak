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
		isset($_POST['t'])
		&& 
		isset($_POST['o']) 
		&& 
		isset($_POST['c']) 
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
		$t = $_POST['t'];
		$o = $_POST['o'];
		$c = $_POST['c'];
		
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