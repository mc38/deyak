<?php
include "db/command.php";

$qq = mysql_query("select consumer_no,principle_arrear,cs_pa from in_data_queue");
if(mysql_num_rows($qq) >0){
	while($qd = mysql_fetch_object($qq)){
		$conno = $qd->consumer_no;
		$pa = $qd->principle_arrear;
		$cs_pa = $qd->cs_pa;

		$mq = mysql_query("select id,c_bid from m_data where out_oldcid='". $conno ."' and c_import_status=0");
		if(mysql_num_rows($mq) >0){
			$md = mysql_fetch_object($mq);

			mysql_query("update m_data set out_principal_arrear='". $pa ."',out_cs_pa='". $cs_pa ."' where id='". $md->id ."'");

			$bid = $md->c_bid;

			$bq = mysql_query("select baid from bill_details where id='". $bid ."'");
			if(mysql_num_rows($bq) >0){
				$bd = mysql_fetch_object($bq);

				$baid = $bd->baid;

				mysql_query("update bill_amount set pa='". $pa ."',cs_pa='". $cs_pa ."' where id='". $baid ."'");

				echo $conno ." -> Done <br/>";
			}
		}
	}
}

?>