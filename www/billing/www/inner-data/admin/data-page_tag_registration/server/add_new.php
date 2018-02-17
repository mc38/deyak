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
		
		$n = strtolower($data[0]);
		
		$q = mysql_query("select id from zzpagetag where name='". $n ."'");
		if(mysql_num_rows($q)>0){
			echo 1;
		}
		else{
			$dq = mysql_query("insert into zzpagetag(name) values('".$n."')");
			if($dq){
				$ddq = mysql_query("update zzpagetag set srl='".mysql_insert_id()."' where id='".mysql_insert_id()."'");
				if($ddq){
					echo $_POST['c'];
				}
			}
		}
	}
}
else{
	echo 0;
}
?>