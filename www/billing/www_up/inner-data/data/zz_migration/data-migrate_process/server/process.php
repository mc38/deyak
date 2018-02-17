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
		
		$q = mysql_query("select * from in_data_queue where subdivision_id='". $subdiv ."' and status='0' limit 0,".$migration_batchno);
		if(mysql_num_rows($q)>0){
			$data = array();
			while($d = mysql_fetch_object($q)){
			
				include "import_consumer.php";
				include "import_billing.php";
				
				if($billdo){
					mysql_query("insert into bill_details(mydate,subdiv_id,conid,readid,baid) values('". strtotime($mydate) ."','". $subdiv ."','". $conid ."','". $readid ."','". $baid ."')");
					mysql_query("update in_data_queue set status='1' where id='". $d->id ."'");
				}
			}
			echo 3;
		}else{
			echo 2;
		}
	}else{
		echo 1;	
	}
}
else{
	echo 0;
}
?>