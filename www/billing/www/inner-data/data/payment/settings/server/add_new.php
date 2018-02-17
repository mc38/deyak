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
		
		$subdiv_id = $data[0];
		$ct = $data[1];
		$cd = $data[2];
		
		$sq = mysql_query("select sid from subdiv_data where id='". $subdiv_id ."'");
		if(mysql_num_rows($sq) ==1){
			$sd = mysql_fetch_object($sq);
			$sid = $sd->sid;
			
			$q = mysql_query("select id from payment_setting where subdiv='". $sid ."'");
			if(mysql_num_rows($q)>0){
				mysql_query("update payment_setting set ctype='". $ct ."',cdata='". $cd ."' where subdiv='". $sid ."'");
			}else{
				mysql_query("insert into payment_setting(subdiv, ctype, cdata) values('". $sid ."','". $ct ."','". $cd ."')");
			}
			
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