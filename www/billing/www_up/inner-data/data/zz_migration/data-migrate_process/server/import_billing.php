<?php

if($readdo){
	$bq = mysql_query("select id from bill_details where mydate='". strtotime($mydate) ."' and conid='". $conid ."'");
	if(mysql_num_rows($bq) >0){
		$logdata .= 'Bill data exists<br/>';
	}else{
		
		include "import_reading.php";
		if($billdo){
		
			$cateq = mysql_query("select surcharge from settings_consumer_cate where id='". $concate ."'");
			$cated = mysql_fetch_object($cateq);
			$surchrg = $cated->surcharge;
			
			$o_pa 			= $d->principle_arrear;
			$o_as			= $d->arrear_surcharge;
			$o_adj			= $d->adjustment;
			$o_duedate		= $d->due_datetime;
			$o_cs_pa		= $d->cs_pa;
			
			/*important calculation*/
			/*
			$n_pa	= ($o_cd + $o_pa) - $o_adj;
			$n_as	= ($o_as + $o_cs);
			$n_cs	= 0;

			if($o_paydate > $o_duedate){
				$n_cs = round((($n_pa * $surchrg)/100),2);
			}

			if($n_pa > $o_pay){
				$n_pa	= round(($n_pa - $o_pay),2);
				$o_pay  = 0;
			}else{
				$o_pay 	= round(($o_pay - $n_pa),2);
				$n_pa 	= 0;
				if($n_as > $o_pay){
					$n_as = round(($n_as - $o_pay),2);
					$o_pay  = 0;
				}else{
					$o_pay = round(($o_pay - $n_as),2);
					$n_as = 0;
					if($n_cs > $o_pay){
						$n_cs = round(($n_cs - $o_pay),2);
						$o_pay  = 0;
					}else{
						$o_pay = round(($o_pay - $n_cs),2);
						$n_cs = 0;
					}
				}
			}
			$n_adj = $o_pay;

			if($n_cs == 0){
				$n_cs = round((($n_pa * $surchrg)/100),2);
			}
			*/

			$n_pa 		= $o_pa;
			$n_as 		= $o_as;
			$n_adj 		= $o_adj;
			$n_due		= $o_duedate;
			$n_cs_pa	= $o_cs_pa;

			/*important calculation end*/
			
			$col 		= array();						$coldata		= array();
			
			$col[0] 	= "mydate";						$coldata[0]		= strtotime($mydate);
			$col[1] 	= "conid";						$coldata[1]		= $conid;
			$col[2] 	= "datetime";					$coldata[2]		= 0;
			$col[3] 	= "type";						$coldata[3]		= 0;
			$col[4] 	= "pa";							$coldata[4]		= round($n_pa,2);
			$col[5] 	= "asr";						$coldata[5]		= round($n_as,2);
			$col[6] 	= "adjustment";					$coldata[6]		= round($n_adj,2);
			$col[7] 	= "due_datetime";				$coldata[7]		= $n_due;
			$col[8] 	= "cs_pa";						$coldata[8]		= $n_cs_pa;
			
			$colstr		= implode(',',$col);			$coldatastr		= implode("','",$coldata);
			
			mysql_query("insert into bill_amount(". $colstr .") values('". $coldatastr ."')");
			$baid = mysql_insert_id();
			$logdata .= "|->billing data imported<br/>";
		}
	}
}

?>