<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		$db = new dbconnection();
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id = $data[0];
		$name = base64_encode(strtolower($data[1]));
		$address = base64_encode(strtolower($data[2]));
		$contact = $data[3];
		$installtion = $data[4];
		$cload = $data[5];
		$mru = $data[6];
		$mfactor = $data[7];
		$mext = $data[8];
		
		$ccate = $data[9];
		$mrt = $data[10];
		
		$subdivid = $data[11];
		
		$db->command("select id from consumer_info where consumer_id='".$id."'");
		$q = $db->response;
		
		if(sizeof($q) >0){
			echo 1;
		}
		else{
			$db->command("insert into consumer_info(datetime, consumer_id, name, address, contact, installation_no, cload, mru, mfactor, extra) values('".$datetime."','".$id."','".$name."','".$address."','".$contact."','".$installtion."','".$cload."','".$mru."','".$mfactor."','".$mext."')");
			$cid = $db->response;
			
			$db->command("connect (consumer_info,".$cid.") (consumer_cate,".$ccate.")");
			$db->command("connect (consumer_info,".$cid.") (meter_data,".$mrt.")");
			$db->command("connect (consumer_info,".$cid.") (subdiv_data,".$subdivid.")");
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>