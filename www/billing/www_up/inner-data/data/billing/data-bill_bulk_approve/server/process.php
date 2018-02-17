<?php
ini_set('max_execution_time', 10000);
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../../config/config.php");
require_once("../../../../../spl_func/approve.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		$logdata = "";
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$fd = $data[0];
		$td = $data[1];
		$s = $data[2];
		
		$where = " and c_import_datetime>". strtotime($fd) ." and c_import_datetime<". strtotime('+1day',strtotime($td));
		$q = mysql_query("select * from m_data where in_status<>'' and c_import_status=1 and c_pass_status=0 and c_subdiv_id='". $s ."'". $where ." limit 0,".$migration_batchno ."");
		if(mysql_num_rows($q) >0){
			while($d = mysql_fetch_object($q)){
				billapprove($d,$u,$datetime);
			}
		}
		echo $_POST['c'];
		
	}else{
		echo 1;	
	}
}
else{
	echo 0;
}
?>