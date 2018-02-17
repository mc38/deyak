<?php
session_start();
require_once("../../../db/command.php");
require_once("../../../plugin/func/index.php");

if(

isset($_POST['c']) && $_POST['c'] !=""
&&
isset($_POST['d']) && $_POST['d'] !=""

){
	
	$data = json_decode(base64_decode($_POST['d']));
	
	$user = strtolower($data[0]);
	$pass = $data[1];
	
	$q = mysql_query("select id,hashalgo,ushashvalue,salt from zzuserdata where username='".$user."' and status='0'");
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);
		
		$hashalgo = hash_algos();
		
		$data = $user.$pass."_".$d->salt;
		$hash = hash($hashalgo[$d->hashalgo],$data);
		if($hash == $d->ushashvalue){
			$id = $d->id;
			
			//salt update
			$salt = randcode(5);
			$hdata = $user.$pass."_".$salt;
			$r = rand(0,sizeof($hashalgo)-1);
			$h = hash($hashalgo[$r],$hdata);
			mysql_query("update zzuserdata set hashalgo='".$r."',ushashvalue='".$h."',salt='".$salt."' where id='".$id."'");
			
			//session create
			$sess = new rcrypt();
			$key = $sess->createkey(16);
			$rkey = $sess->getrkey($key);
			$_SESSION['us'] = $rkey;
			$val = randcode(16);
			$kholder = $sess->rencode($key,$val);
			$_SESSION[$_SESSION['us']]=$kholder;
			
			$id_sess = new rcrypt();
			$id_key = $id_sess->createkey(16);
			$id_rkey = $id_sess->getrkey($id_key);
			$id_holder = $id_sess->rencode($id_key,$id);
			$_SESSION[$val .'_0']=$id_rkey;
			$_SESSION[$val .'_1']=$id_holder;
			
			$_SESSION['t'] = $datetime;
			
			echo $_POST['c'];
		}
		else{
			echo 1;
		}
	}
	else{
		echo 1;
	}
}
else{
	echo 0;
}
?>