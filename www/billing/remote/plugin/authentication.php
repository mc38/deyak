<?php
function authenticate(){
	$dir = dirname(__FILE__);
	require_once(str_replace("\\","/",$dir) . "/rcrypt.php");
	
	if(
		isset($_POST['a']) && isset($_POST['i'])
		&&
		$_POST['a'] !="" && $_POST['i'] !=""
	){
		$rc = new rcrypt();
		$key = $rc->getrkey($_POST['i']);
		$aid = $rc->rdecode($key,$_POST['a']);
		//echo $_POST['i']." ". $aid. " ";
		
		$r = false;
		$query = "select id from agent_info where id='". $aid ."' and imei='". $_POST['i'] ."'";
		$q = mysql_query($query);
		if(mysql_num_rows($q) == 1){
			$r = $aid;
		}
		return $r;
	}
}
?>