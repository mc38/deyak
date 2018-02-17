<?php
$ob_d=array();
$or_d=array();
$b_d =array();

$caq = mysql_query("select id,name,imei from agent_info where id='". $a ."'");
if(mysql_num_rows($caq) >0){
	$cad = mysql_fetch_object($caq);
	

	$ob_q = $dbh->query("select * from mdata where aid='". $r->rencode($cad->imei,$cad->id) ."'");
	$ob_d = $ob_q->fetchAll(PDO::FETCH_OBJ);
	$total_data = sizeof($ob_d);
	
}
?>