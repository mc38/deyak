<?php
include "../../db/command.php";
include "../../plugin/authentication.php";
include "../../plugin/data_transfer.php";
include "../../plugin/rcrypt.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";
	
	if($a = authenticate()){
	
		$data = json_decode(base64_decode($_POST['d']));
		
		$id 								= $data[0];
		$aid								= $data[1];
		
		$n_meterpic_binary					= $data[2];
		
		
		$r = new rcrypt();
		$key = $r->getrkey($_POST['i']);
		
		if($a == $r->rdecode($key,$aid)){
			
			$query = "update m_data set in_meterpic_binary='". base64_encode($r->rdecode($key,$n_meterpic_binary)) ."' where id='". $r->rdecode($key,$id) ."' and c_import_status=0 and c_done=0";
			$q_done = mysql_query($query);
			
			if($q_done){
				$send_data = 1;
			}
		}else{
			$send_data = 0;
		}
	
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}
?>