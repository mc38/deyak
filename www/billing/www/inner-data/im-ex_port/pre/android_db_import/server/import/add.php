<?php
////////////////////////////////////////////////////////////////////////////////////////
$a_q = $dbh->query("select * from appdata");
$a_d = $a_q->fetch(PDO::FETCH_OBJ);

$caq = mysql_query("select id from agent_info where id='". $a_d->aid ."' and status ='0'");
if(mysql_num_rows($caq) >0){
		
		$aid = $a_d->aid;
		$id = $d;
		
		$delq = mysql_query("select link from trash_p_billdata where id='".$id."'");
		if(mysql_num_rows($delq)>0){
			$deld = mysql_fetch_object($delq);
			$link =$deld->link;
			
			mysql_query("delete from trash_p_billdata where id='".$id."'");
			mysql_query("delete from trash_out_bill_xml where link='".$link."'");
			mysql_query("delete from trash_out_reading_xml where link='".$link."'");
			
			echo $_POST['c'];
		}
		
}
else{
	echo 2;
}

?>