<?php
ini_set('max_execution_time', 10000);
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../plugin/func/logbook.php");
require_once("../../../../../../config/config.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		$logdata = "";
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$mydate = $data[0];
		$subdiv = $data[1];
		
		mysql_query("update m_data set c_done='1' where c_subdiv_id='". $subdiv ."' and c_mydate='". strtotime($mydate) ."'");
		
		echo $_POST['c'];
	}
}
else{
	echo 0;
}
?>