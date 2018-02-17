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
		
		$q = mysql_query("select id,name from settings_subdiv_data where sid='".$acode."'");
		if(mysql_num_rows($q)==1){
			$d = mysql_fetch_object($q);
			
			$aq = mysql_query("select id,name from agent_info where subdiv='".$d->id."' and status='0'");
			if(mysql_num_rows($aq)>0){
				$agent = array();
				while($ad = mysql_fetch_object($aq)){
					$agent[]=$ad;
				}
				$agent_str = base64_encode(json_encode($agent));
				echo base64_encode(json_encode(array($_POST['c'],$d->id,$d->name,$agent_str)));
			}
			else{
				echo 2;
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