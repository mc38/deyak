<?php

$subdiv			= $d->subdivision_id;
$dtrno 			= $d->dtr_no;
$oldconno		= $d->old_consumer_no;
$conno			= $d->consumer_no;
$conname		= $d->consumer_name;
$conaddress		= $d->consumer_address;
$meterno		= $d->meter_no;
$connload		= $d->connected_load;
$mfactor		= $d->multiplying_factor;
$category		= $d->consumer_category_code;
$metertype		= $d->meter_type;

$readdo = false;
$billdo = false;

$chq = mysql_query("select id,category from consumer_details where oldcid='". $conno ."'");
if(mysql_num_rows($chq) >0){
	$chd = mysql_fetch_object($chq);
	$conid = $chd->id;
	$concate = $chd->category;
	$logdata .= $conno ." consumer exists<br/>";
	$readdo = true;
}else{
	$cateq = mysql_query("select id from settings_consumer_cate where id='". $category ."'");
	if(mysql_num_rows($cateq)>0){
		$mq = mysql_query("select id,phase from settings_meter_cate where id='". $metertype ."'");
		if(mysql_num_rows($mq) >0){
			$md = mysql_fetch_object($mq);
	
			$col 		= array();						$coldata		= array();
			
			$col[0] 	= "subdiv_id";					$coldata[0]		= $subdiv;
			$col[1] 	= "oldcid";						$coldata[1]		= $conno;
			$col[2] 	= "oldcno";						$coldata[2]		= $oldconno;
			$col[3] 	= "dtrno";						$coldata[3]		= $dtrno;
			$col[4] 	= "consumer_name";				$coldata[4]		= $conname;
			$col[5] 	= "consumer_address";			$coldata[5]		= $conaddress;
			$col[6] 	= "phase";						$coldata[6]		= $md->phase;
			$col[7] 	= "cload";						$coldata[7]		= $connload;
			$col[8] 	= "load_unit";					$coldata[8]		= "KW";
			$col[9] 	= "category";					$coldata[9]		= $category;
			$col[10]	= "mfactor";					$coldata[10]	= $mfactor;
			$col[11] 	= "meterno";					$coldata[11]	= $meterno;
			$col[12] 	= "meter_cate";					$coldata[12]	= $metertype;
			
			$colstr		= implode(',',$col);			$coldatastr		= implode("','",$coldata);
			
			mysql_query("insert into consumer_details(". $colstr .") values('". $coldatastr ."')");
			$conid = mysql_insert_id();
			$concate = $category;
			
			$subdiv_code 	= $subdiv + 1000;
			$dtr_code		= $dtrno + 1000;
			$cid_code 		= $conid + 1000000000;
			$cid			= $subdiv_code ."". $dtr_code ."". $cid_code;
			mysql_query("update consumer_details set cid='". $cid ."' where id='". $conid ."'");
			
			
			$logdata .= $conno ." consumer imported<br/>";
			
			$readdo = true;
			
		}else{
			$logdata .= $conno ." meter category not exists<br/>";
		}
	}else{
		$logdata .= $conno ." consumer's category not exists<br/>";
	}
}
?>