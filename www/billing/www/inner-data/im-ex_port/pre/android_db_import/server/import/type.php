<?php
require_once("../filter/type.php");
////////////////////////////////////////////////////////////////////////////////////////
if(mysql_num_rows($caq) >0){
	if(sizeof($a_d)>0 && ( sizeof($ob_d)>0 || sizeof($or_d)>0 || sizeof($b_d)>0 || sizeof($p_d)>0 )){
		
		$aid = $a_d->aid;
		
		if(sizeof($b_d)>0){
			for($i=0;$i<sizeof($b_d);$i++){
				$data = $b_d[$i];
				$dchq = mysql_query("select id from trash_p_billdata where link='".$data->link ."' and reading_date='".$data->reading_date ."'");
				if(mysql_num_rows($dchq) <1){
					mysql_query("update p_billdata set billno='". $data->billno ."',status='". $data->status ."',postmeter_read='". $data->postmeter_read ."',meterpic='". $data->meterpic ."',reading_date='". $data->reading_date ."',fmeterno='". $data->fmeterno ."',aid='".$aid."' where id='". $data->link ."' and aid='0'");
				}
				$reading_date[$data->link]=$data->reading_date;
				$status[$data->link]=$data->status;
				$postmeter_read[$data->link]=$data->postmeter_read;
			}
		}
		
		if(sizeof($ob_d)>0){
			for($i=0;$i<sizeof($ob_d);$i++){
				$data = $ob_d[$i];
				$link = $data->link;
				
				$dchq = mysql_query("select id from trash_p_billdata where link='".$link ."' and reading_date='".$reading_date[$data->link] ."'");
				$chpb= mysql_query("select id from p_billdata where link='".$link."' and status='". $status[$data->link] ."' and reading_date='".$reading_date[$data->link] ."' and postmeter_read='". $postmeter_read[$data->link] ."'");
				$chq = mysql_query("select id from out_bill_xml where link='". $link ."'");
				if(mysql_num_rows($chq)<1 && mysql_num_rows($dchq)<1 && mysql_num_rows($chpb)>0){
					
					$tq = mysql_query("select mydate from p_billdata where id='".$link."'");
					$td = mysql_fetch_object($tq);
					
					$due_date =date('t-m-Y',$td->mydate);
					if(date('w',strtotime($due_date))<1){
						$due_date = date('d-m-Y',strtotime('-1 day',strtotime($due_date)));
					}
					
					$bill_date = $data->bill_datetime;
					if(strtotime($bill_date)<$td->mydate){
						$bill_date = date('d-m-Y',$td->mydate);
					}
					
					$bcol = array();						$bval = array();
					
					$bcol[0] = "aid";						$bval[0] =$aid;
					$bcol[1] = "mydate";					$bval[1] =$td->mydate;
					$bcol[2] = "consumer_id";				$bval[2] =$data->$bcol[2];
					$bcol[3] = "subdivision_id";			$bval[3] =$data->$bcol[3];
					$bcol[4] = "book_no";					$bval[4] =$data->$bcol[4];
					$bcol[5] = "tariff_id";					$bval[5] =$data->$bcol[5];
					$bcol[6] = "bill_from_datetime";		$bval[6] =$data->$bcol[6];
					$bcol[7] = "bill_to_datetime";			$bval[7] =$data->$bcol[7];
					$bcol[8] = "bill_datetime";				$bval[8] =$bill_date;
					$bcol[9] = "bill_generate_datetime";	$bval[9] =$bill_date;
					$bcol[10]= "bill_due_datetime";			$bval[10]=$due_date;
					$bcol[11]= "previous_reading";			$bval[11]=$data->$bcol[11];
					$bcol[12]= "current_reading";			$bval[12]=$data->$bcol[12];
					$bcol[13]= "billed_unit";				$bval[13]=$data->$bcol[13];
					$bcol[14]= "energy_charge";				$bval[14]=$data->$bcol[14];
					$bcol[15]= "fixed_charge";				$bval[15]=$data->$bcol[15];
					$bcol[16]= "meter_rent";				$bval[16]=$data->$bcol[16];
					$bcol[17]= "other_charge";				$bval[17]=$data->$bcol[17];
					$bcol[18]= "diseal_charge";				$bval[18]=$data->$bcol[18];
					$bcol[19]= "fuel_charge_rate";			$bval[19]=$data->$bcol[19];
					$bcol[20]= "fuel_charge";				$bval[20]=$data->$bcol[20];
					$bcol[21]= "gross_charge";				$bval[21]=$data->$bcol[21];
					$bcol[22]= "rebate_charge";				$bval[22]=$data->$bcol[22];
					$bcol[23]= "credit_adjustment";			$bval[23]=$data->$bcol[23];
					$bcol[24]= "net_charge";				$bval[24]=$data->$bcol[24];
					$bcol[25]= "old_ec";					$bval[25]=$data->$bcol[25];
					$bcol[26]= "old_uc";					$bval[26]=$data->$bcol[26];
					$bcol[27]= "sundry";					$bval[27]=$data->$bcol[27];
					$bcol[28]= "n_rate";					$bval[28]=$data->$bcol[28];
					$bcol[29]= "bill_no";					$bval[29]=$data->$bcol[29];
					$bcol[30]= "energy_charge_breakup";		$bval[30]=$data->$bcol[30];
					$bcol[31]= "link";						$bval[31]=$data->$bcol[31];
					
					$bcol_str = implode(',',$bcol);			$bval_str = implode("','",$bval);
					
					mysql_query("insert into out_bill_xml(". $bcol_str .") values('". $bval_str ."')");
				}
			}
		}
		
		if(sizeof($or_d)>0){
			for($i=0;$i<sizeof($or_d);$i++){
				$data = $or_d[$i];
				$link = $data->link;
				
				$dchq = mysql_query("select id from trash_p_billdata where link='".$link ."' and reading_date='".$reading_date[$data->link] ."'");
				$chpb= mysql_query("select id from p_billdata where link='".$link."' and status='". $status[$data->link] ."' and reading_date='".$reading_date[$data->link] ."' and postmeter_read='". $postmeter_read[$data->link] ."'");
				$chq = mysql_query("select id from out_reading_xml where link='". $link ."'");
				if(mysql_num_rows($chq)<1 && mysql_num_rows($dchq)<1 && mysql_num_rows($chpb)>0){
					
					$tq = mysql_query("select mydate from p_billdata where id='".$link."'");
					$td = mysql_fetch_object($tq);
					
					$rcol = array();						$rval = array();
					
					$rcol[0] = "mydate";					$rval[0] =$td->mydate;
					$rcol[1] = "consumer_id";				$rval[1] =$data->$rcol[1];
					$rcol[2] = "subdivision_id";			$rval[2] =$data->$rcol[2];
					$rcol[3] = "book_no";					$rval[3] =$data->$rcol[3];
					$rcol[4] = "bill_from_datetime";		$rval[4] =$data->$rcol[4];
					$rcol[5] = "bill_to_datetime";			$rval[5] =$data->$rcol[5];
					$rcol[6] = "previous_reading";			$rval[6] =$data->$rcol[6];
					$rcol[7] = "current_reading";			$rval[7] =$data->$rcol[7];
					$rcol[8] = "unit_consumed";				$rval[8] =$data->$rcol[8];
					$rcol[9] = "reading_date";				$rval[9] =$data->$rcol[9];
					$rcol[10]= "remarks";					$rval[10]=$data->$rcol[10];
					$rcol[11]= "multiplying_factor";		$rval[11]=$data->$rcol[11];
					$rcol[12]= "ppunit";					$rval[12]=$data->$rcol[12];
					$rcol[13]= "link";						$rval[13]=$data->$rcol[13];
					
					$rcol_str = implode(',',$rcol);			$rval_str = implode("','",$rval);
					
					mysql_query("insert into out_reading_xml(". $rcol_str .") values('". $rval_str ."')");
				}
			}
		}
		
		if(sizeof($p_d)>0){
			for($i=0;$i<sizeof($p_d);$i++){
				$data = $p_d[$i];
				
				$pchq = mysql_query("select id from payment_data where ackno='".$data->ackno ."' and cid='". $data->cid ."' and aid='". $data->aid ."'");
				if(mysql_num_rows($pchq)<1){
					
					$pcol = array();						$pval = array();
					
					$pcol[0] = "mydate";					$pval[0] = strtotime($data->$pcol[0]);
					$pcol[1] = "subdiv";					$pval[1] =$data->$pcol[1];
					$pcol[2] = "cid";						$pval[2] =$data->$pcol[2];
					$pcol[3] = "ackno";						$pval[3] =$data->$pcol[3];
					$pcol[4] = "datetime";					$pval[4] =$data->$pcol[4];
					$pcol[5] = "amount";					$pval[5] =$data->$pcol[5];
					$pcol[6] = "commission";				$pval[6] =$data->$pcol[6];
					$pcol[7] = "aid";						$pval[7] =$data->$pcol[7];
					$pcol[8] = "prints";					$pval[8] =$data->$pcol[8];
					
					$pcol_str = implode(',',$pcol);			$pval_str = implode("','",$pval);
					
					mysql_query("insert into payment_data(". $pcol_str .") values('". $pval_str ."')");
				}
			}
		}
		
		
		echo $_POST['c'];
	}
	else{
		echo 2;
	}
}
else{
	echo 2;
}

?>