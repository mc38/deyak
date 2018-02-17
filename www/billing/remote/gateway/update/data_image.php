<?php
include "../../db/command.php";
include "../../plugin/authentication.php";
include "../../plugin/data_transfer.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";
	
	if($a = authenticate()){
		
		$data = json_decode(base64_decode($_POST['d']));
		$fname = $data[0];
		$fdata = base64_decode($data[1]);
		$fpart = $data[2];
		
		include "../../../file/config.php";
		include "../../../file/image/plugin/upload.php";
		
		
		$send_data = 1;
		
	
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}
?>