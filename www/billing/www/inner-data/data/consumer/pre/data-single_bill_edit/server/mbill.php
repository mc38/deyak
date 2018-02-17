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
		
		$read = $data[0];
		$rid  = $data[1];
		
		$q = mysql_query("update p_billdata set postmeter_read='".$read."' where id='".$rid."'");
		if($q){
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>