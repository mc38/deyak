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
		
		$acode = $data[0];
		
		$q = mysql_query("select id from settings_consumer_cate where tariff_id='".$acode."'");
		if(mysql_num_rows($q)>0){
			echo 1;
		}
		else{
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>