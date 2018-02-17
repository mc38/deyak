<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$id 			= $data[0];
		$mid 			= $data[1];
		$rid 			= $data[2];

		$o_meterno 		= strtoupper($data[3]);
		$n_meterno 		= strtoupper($data[4]);
		$sm 			= strtoupper($data[5]);
		
		$q = mysql_query("select id from consumer_details where id='".$id."' and meterno='". $o_meterno ."'");
		if(mysql_num_rows($q)<1){
			echo 1;
		}
		else{
			mysql_query("update consumer_details set meterno='". $n_meterno ."' where id='". $id ."' and meterno='". $o_meterno ."'");
			if($sm == 0){
				if($mid>0){
					mysql_query("update m_data set out_meter_no='". $n_meterno ."' where id='". $mid ."' and out_meter_no='". $o_meterno ."'");
				}

				if($rid>0){
					mysql_query("update bill_reading set meterno='". $n_meterno ."' where id='". $rid ."' and meterno='". $o_meterno ."' and mdid='". $mid ."'");
				}
			}
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>