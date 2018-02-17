<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$acode = $data[0];
		
		$q = mysql_query("select id,name from zzpagetag where id='".$acode."'");
		if(mysql_num_rows($q)==1){
			
			$aq = mysql_query("select id,name from zzpage where link='". $acode ."' order by srl");
			if(mysql_num_rows($aq)>0){
				$page = array();
				while($ad = mysql_fetch_object($aq)){
					$page[]=$ad;
				}
				$agent_str = base64_encode(json_encode($page));
				echo base64_encode(json_encode(array($_POST['c'],$agent_str)));
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