<?php
ini_set('max_execution_time', 10000);
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$link = $data[0];
		
		mysql_query("delete from out_bill_xml where link='". $link ."'");
		mysql_query("delete from out_reading_xml where link='". $link ."'");
		mysql_query("delete from p_billdata_multi where link='". $link ."'");
		
		mysql_query("update p_billdata set billno='',status='',postmeter_read='',meterpic='',reading_date='',fmeterno='',aid=0 where link='".$link."'");
		
		echo $_POST['c'];
			
	}
}
else{
	echo 0;
}
?>