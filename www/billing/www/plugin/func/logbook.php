<?php
class LogBook{
	var $tbl;
	var $predata;
	
	function LogBook($tblname,$id){
		$s = "";
		if($id>0){
			$dir = dirname(__FILE__);
			require_once(str_replace("\\","/",$dir) . "/../../db/command.php");
			
			$q = mysql_query("select * from ". $tblname ." where id=". $id );
			$d = mysql_fetch_object($q);
			$s = base64_encode(json_encode($d));
		}
		
		$this->tbl = $tblname;
		$this->predata = $s;
	}
	function store($type,$user,$id){
		$dir = dirname(__FILE__);
		require_once(str_replace("\\","/",$dir) . "/../../db/command.php");
		if(mysql_query("SHOW TABLES LIKE 'zzzlogbook'")){
			date_default_timezone_set('Asia/Kolkata');
			$datetime=date($_SERVER['REQUEST_TIME']);
			
			mysql_query("insert into zzzlogbook(datetime,byuser,tblname,tblid,type,prevdata) values('". $datetime ."','". $user ."','". $this->tbl ."','". $id ."','". $type ."','". $this->predata ."')");
		}
	}
}
?>