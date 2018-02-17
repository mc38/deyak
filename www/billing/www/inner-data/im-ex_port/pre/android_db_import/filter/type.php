<?php
$ob_d=array();
$or_d=array();
$b_d =array();

$a_q = $dbh->query("select * from appdata");
$a_d = $a_q->fetch(PDO::FETCH_OBJ);
$appdata = sizeof($a_d);

$caq = mysql_query("select id from agent_info where id='". $a_d->aid ."' and status ='0'");
if(mysql_num_rows($caq) >0){

	$ob_q = $dbh->query("select * from out_bill_xml");
	$ob_d = $ob_q->fetchAll(PDO::FETCH_OBJ);
	$total_bill_out_data = sizeof($ob_d);
	
	$or_q = $dbh->query("select * from out_reading_xml");
	$or_d = $or_q->fetchAll(PDO::FETCH_OBJ);
	$total_read_out_data = sizeof($or_d);
	
	$b_q = $dbh->query("select * from billdata where status<>''");
	$b_d = $b_q->fetchAll(PDO::FETCH_OBJ);
	$total_bill_data = sizeof($b_d);
	
	$p_q = $dbh->query("select * from payment_data");
	$p_d = $p_q->fetchAll(PDO::FETCH_OBJ);
	$total_pay_data = sizeof($p_d);
}
?>