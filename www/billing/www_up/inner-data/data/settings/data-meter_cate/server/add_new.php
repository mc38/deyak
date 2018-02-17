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
		
		$cate = strtoupper($data[0]);
		$rent = $data[1];
		$phase= $data[2];
		$code = $data[3];
		
		$q = mysql_query("select id from settings_meter_cate where link='".$code."'");
		if(mysql_num_rows($q)>0){
			echo 1;
		}
		else{
			mysql_query("insert into settings_meter_cate(name,rent,phase,link) values('".$cate."','".$rent."','". $phase ."','". $code ."')");
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>