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
		
		$i = $data[0];
		$s = $data[1];
		$a = $data[2];
		$d = $data[3];
		
		$dq = mysql_query("select id from agent_dtr where dtr='".$d."'");
		if(mysql_num_rows($dq)==0){
			$q = mysql_query("select id from agent_dtr where id='".$i."'");
			if(mysql_num_rows($q)==1){
				mysql_query("update agent_dtr set dtr='".$d."' where id='".$i."'");
			}
			else{
				mysql_query("insert into agent_dtr(subdiv,aid,dtr) values('".$s."','".$a."','".$d."')");
			}
			echo $_POST['c'];
		}else{
			echo 1;
		}
	}
}
else{
	echo 0;
}
?>