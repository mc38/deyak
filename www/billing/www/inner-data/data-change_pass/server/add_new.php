<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");
require_once("../../../plugin/func/index.php");

if($us = authenticate()){
	if($us == 'a'){
		$us = '0';
	}
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$u = strtolower($data[0]);
		$p = $data[1];
		$n = $data[2];
		
		$q = mysql_query("select id,hashalgo,ushashvalue,salt from zzuserdata where username='". $u ."' and id='".$us."'");
		if(mysql_num_rows($q)==1){
			$d = mysql_fetch_object($q);
			
			$hashalgo = hash_algos();
			
			$data = $u.$p."_".$d->salt;
			$hash = hash($hashalgo[$d->hashalgo],$data);
			if($hash == $d->ushashvalue){
				
				$salt = randcode(5);
				$hdata = $u.$n."_".$salt;
				$r = rand(0,sizeof($hashalgo)-1);
				$h = hash($hashalgo[$r],$hdata);
				
				$ddq = mysql_query("update zzuserdata set hashalgo='".$r."',ushashvalue='".$h."',salt='".$salt."', uactive='' where id='".$us."' and  username='". $u ."'");
				if($ddq){
					echo $_POST['c'];
				}
			
			}
			else{
				echo 1;
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