<?php
include "../../db/command.php";
include "../../plugin/data_transfer.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";

	$data = json_decode(base64_decode($_POST['d']));
	
	$pin = $data[0];
	$imei= $data[1];
	
	$q = mysql_query("select id,name,subdiv from agent_info where agent_pin='".$pin."' and imei='".$imei."'");
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);
		
		$send_data = $d->id;
		
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}


?>