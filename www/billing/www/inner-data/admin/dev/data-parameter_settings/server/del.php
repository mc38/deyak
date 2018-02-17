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
		
		$i = $data[0];
		mysql_query("delete from zzdev where id='".$i."'");
		
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>