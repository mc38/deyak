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
		
		$q = mysql_query("select id,name from subdiv_data where sid='".$acode."'");
		if(mysql_num_rows($q)==1){
			$d = mysql_fetch_object($q);
			echo base64_encode(json_encode(array($_POST['c'],$d->id,$d->name)));
			
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