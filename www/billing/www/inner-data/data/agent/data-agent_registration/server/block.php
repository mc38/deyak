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
		
		$id = $data[0];
		$q = mysql_query("select id,status from agent_info where id='".$id."'");
		if(mysql_num_rows($q)==1){
			$d = mysql_fetch_object($q);
			$sta = $d->status;

			$backdoor  = "";
			if($sta == 0){
				$sta =1;
				$backdoor = ",backdoor=0";
			}
			else{
				$sta=0;
			}
			
			mysql_query("update agent_info set status=".$sta."". $backdoor ." where id='".$id."'");
			echo $_POST['c'];
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