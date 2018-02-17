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
		
		$n = strtoupper($data[0]);
		$d = strtotime($data[1]);
		
		$q = mysql_query("select id from settings_holidays where datetime='".$d."'");
		if(mysql_num_rows($q)>0){
			echo 1;
		}
		else{
			mysql_query("insert into settings_holidays(name,datetime) values('". $n ."','". $d ."')");
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>