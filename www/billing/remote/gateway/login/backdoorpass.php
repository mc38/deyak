<?php
include "../../db/command.php";
include "../../plugin/data_transfer.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";

	$data = json_decode(base64_decode($_POST['d']));
	
	$imei= $data[0];
	
	$q = mysql_query("select id,backdoor,backdoor_pass,backdoor_datetime from agent_info where imei='".$imei."'");
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);

		if($d->backdoor == 1){
			if($d->backdoor_datetime >0 && $d->backdoor_datetime > $datetime){
				$send_data = $d->backdoor_pass;
			}else{
				$pass = generate_pass();
				$ex_datetime = $datetime + 600;
				$q = mysql_query("update agent_info set backdoor_pass='". $pass ."',backdoor_datetime='". $ex_datetime ."' where imei='".$imei."'");
				$send_data = $pass;
			}
		}else{
			$send_data = 1;
		}
	}else{
		$send_data = 0;
	}
	$dt->data_send($send_data);
}

function generate_pass(){
	$data = "0123456789"; $out = "";
	for($i=0;$i<8;$i++){
		$out .= substr($data,rand(0,strlen($data)-1),1);
	}
	return $out;
}
?>