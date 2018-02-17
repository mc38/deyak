<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../plugin/func/logbook.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$subdiv_id = $data[0];
		$scode = $data[1];
		$name = strtoupper($data[2])." ". strtoupper($data[3]);
		$contact = $data[4];
		$sex = $data[5];
		$imei = $data[6];
		
		$sq = mysql_query("select id from settings_subdiv_data where id='". $subdiv_id ."'");
		if(mysql_num_rows($sq) ==1){
			
			$iq = mysql_query("select id from agent_info where imei='". $imei ."'");
			if(mysql_num_rows($iq) >0){
				echo 2;	
			}else{
			
				$sd = mysql_fetch_object($sq);
				
				$lb = new LogBook("agent_info",0);
				mysql_query("insert into agent_info(subdiv,agent_pin,imei,datetime,name,contact,sex) values('".$sd->id."','".$scode."','". $imei ."','".$datetime."','".$name."','".$contact."','".$sex."')");
				$lb->store(0,$u,mysql_insert_id());
				echo $_POST['c'];
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