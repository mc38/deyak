<?php
include "db/command.php";

$total = 0;
$mtotal = 0;
$batotal = 0;

$qq = mysql_query("select consumer_no,principle_arrear,cs_pa from in_data_queue");
if(mysql_num_rows($qq) >0){
	while($qd = mysql_fetch_object($qq)){
		$conno = $qd->consumer_no;
		$pa = $qd->principle_arrear;
		$cs_pa = $qd->cs_pa;

		$total++;

		$mq = mysql_query("select id,c_bid from m_data where out_oldcid='". $conno ."' and c_import_status=0 and out_principal_arrear='". $pa ."' and out_cs_pa='". $cs_pa ."'");
		if(mysql_num_rows($mq) >0){
			$md = mysql_fetch_object($mq);
			$mtotal++;

			$bid = $md->c_bid;

			$bq = mysql_query("select baid from bill_details where id='". $bid ."'");
			if(mysql_num_rows($bq) >0){
				$bd = mysql_fetch_object($bq);

				$baid = $bd->baid;

				$baq = mysql_query("select id from bill_amount where id='". $baid ."' and pa='". $pa ."' and cs_pa='". $cs_pa ."'");
				if(mysql_num_rows($baq) >0){
					$batotal++;
				}
			}
		}
	}
}
echo $total ." -> ". $mtotal ." -> ". $batotal;
?>