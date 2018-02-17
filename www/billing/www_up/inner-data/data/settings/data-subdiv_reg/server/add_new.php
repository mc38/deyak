<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$sid = $data[0];
		$name = $data[1];
		$detail = $data[2];
		
		if($sid !="" && $name !="" && $sid !=""){
			
			$q = mysql_query("select id from settings_subdiv_data where sid='".$sid."'");
			if(mysql_fetch_object($q)>0){
				echo 1;
			}
			else{
				mysql_query("insert into settings_subdiv_data(sid,name,detail) values('".$sid."','".$name."','".$detail."')");
				echo $_POST['c'];
			}
		}
	}
}
else{
	echo 0;
}
?>