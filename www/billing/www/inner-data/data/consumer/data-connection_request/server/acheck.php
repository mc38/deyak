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
		
		$acode = $data[0];
		
		$db->command("select id from meter_data where meter_no='".$acode."'");
		$q = $db->response;
		if(sizeof($q)>0){
			$db->command("nodeshowback (meter_data,".$q[0]->id.")");
			$ex = false;
			$mq = $db->response;
			
			if($mq && sizeof($mq)>0){
				for($i=0;$i<sizeof($mq);$i++){
					if($mq[$i][0] == "consumer_info"){
						$ex = true;
						break;
					}
				}
			}
			
			if($ex){
				echo 2;
			}
			else{
				echo base64_encode(json_encode(array($_POST['c'],$q[0])));
			}
		}
		else{
			echo 1;
		}
	}
}
else{
	echo 0;
}
?>