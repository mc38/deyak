<?php
$user="";
function authenticate(){
	if(! isset($_SESSION)){
		session_start();
	}
	$dir = dirname(__FILE__);
	require_once(str_replace("\\","/",$dir) . "/../../db/command.php");
	
	if(! class_exists('rcrypt')){
		require("rcrypt.php");
	}
	
	$res = false;
	$sess = new rcrypt();
	if(isset($_SESSION['us']) && $_SESSION['us'] !=""){
		if(isset($_SESSION[$_SESSION['us']]) && $_SESSION[$_SESSION['us']]!=""){
			$val = $sess->rdecode($_SESSION['us'],$_SESSION[$_SESSION['us']]);
			$val = substr($val,0,16);
			if(isset($_SESSION[$val .'_0']) && $_SESSION[$val .'_0']!="" && isset($_SESSION[$val .'_1']) && $_SESSION[$val .'_1']!=""){
				$data_k = $_SESSION[$val.'_0'];
				$data_d = $_SESSION[$val.'_1'];
				$id = $sess->rdecode($data_k,$data_d);
				
				$q = mysql_query("select id from zzuserdata where id ='".$id."' and status='0'");
				if(mysql_num_rows($q)>0){
					$d = mysql_fetch_object($q);
					if(isset($d->id)){
						$res=$d->id;
						if($res == 0){
							$res = 'a';
						}
						
						date_default_timezone_set('Asia/Kolkata');
						$datetime=date($_SERVER['REQUEST_TIME']);
						$_SESSION['t'] = $datetime;
					}
				}
			}
		}
	}
	return $res;
}

function check_subdivision($uid,$sid){
	if($uid == 'a'){
		return true;
	}else{
		$q = mysql_query("select sid from zzuser_subdiv where uid='". $uid ."'");
		if(mysql_num_rows($q) ==1){
			$d = mysql_fetch_object($q);
			if($d->sid == $sid){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}


?>