<?php
////////////////////////////////////////////////////////////////////////////////////////
$a_q = $dbh->query("select * from appdata");
$a_d = $a_q->fetch(PDO::FETCH_OBJ);

$caq = mysql_query("select id from agent_info where id='". $a_d->aid ."' and status ='0'");
if(mysql_num_rows($caq) >0){
		
		$aid = $a_d->aid;
		$link = $d;
		
		$ob_q = $dbh->query("select * from out_bill_xml where link='". $link ."'");
		$ob_d = $ob_q->fetch(PDO::FETCH_OBJ);
		
		$or_q = $dbh->query("select * from out_reading_xml where link='". $link ."'");
		$or_d = $or_q->fetch(PDO::FETCH_OBJ);
		
		$b_q  = $dbh->query("select * from billdata where link='". $link ."'");
		$b_d  = $b_q->fetch(PDO::FETCH_OBJ);
		
		$bch_q = mysql_query("select mydate,subdiv_id,bookno from p_billdata where link='".$link."' and aid='0'");
		if(mysql_num_rows($bch_q) >0){
			$td = mysql_fetch_object($bch_q);
			
			if(sizeof($ob_d)>0){
					$data = $ob_d;
					
					$chq = mysql_query("select id from out_bill_xml where link='". $link ."'");
					if(mysql_num_rows($chq)<1 ){
						
						mysql_query("delete from trash_out_bill_xml where link='". $link ."'");
						
						$bcol = array();						$bval = array();
						
						$bcol[0] = "aid";						$bval[0] =$aid;
						$bcol[1] = "mydate";					$bval[1] =$td->mydate;
						$bcol[2] = "consumer_id";				$bval[2] =$data->$bcol[2];
						$bcol[3] = "subdivision_id";			$bval[3] =$data->$bcol[3];
						$bcol[4] = "book_no";					$bval[4] =$data->$bcol[4];
						$bcol[5] = "tariff_id";					$bval[5] =$data->$bcol[5];
						$bcol[6] = "bill_from_datetime";		$bval[6] =$data->$bcol[6];
						$bcol[7] = "bill_to_datetime";			$bval[7] =$data->$bcol[7];
						$bcol[8] = "bill_datetime";				$bval[8] =$data->$bcol[8];
						$bcol[9] = "bill_generate_datetime";	$bval[9] =$data->$bcol[9];
						$bcol[10]= "bill_due_datetime";			$bval[10]=$data->$bcol[10];
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
						
						mysql_query("insert into trash_out_bill_xml(". $bcol_str .") values('". $bval_str ."')");
					}
			}
			
			if(sizeof($or_d)>0){
					$data = $or_d;
					
					$chq = mysql_query("select id from out_reading_xml where link='". $link ."'");
					if(mysql_num_rows($chq)<1 ){
						
						mysql_query("delete from trash_out_reading_xml where link='". $link ."'");
						
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
						
						mysql_query("insert into trash_out_reading_xml(". $rcol_str .") values('". $rval_str ."')");
					}
			}
			
			if(sizeof($b_d)>0){
					$data = $b_d;
					
					$dchq = mysql_query("select id from trash_p_billdata where link='". $link ."' and reading_date='".$data->reading_date."'");
					$chq = mysql_query("select id from p_billdata where link='". $link ."' and aid='0'");
					if(mysql_num_rows($chq)>0 && mysql_num_rows($dchq)<1){
						
						$pcol = array();						$pval = array();
						
						$pcol[0] = "mydate";					$pval[0] =$td->mydate;
						$pcol[1] = "subdiv_id";					$pval[1] =$td->subdiv_id;
						$pcol[2] = "bookno";					$pval[2] =$td->bookno;
						$pcol[3] = "cid";						$pval[3] =$data->$pcol[3];
						$pcol[4] = "billno";					$pval[4] =$data->$pcol[4];
						$pcol[5] = "status";					$pval[5] =$data->$pcol[5];
						$pcol[6] = "postmeter_read";			$pval[6] =$data->$pcol[6];
						$pcol[7] = "meterpic";					$pval[7] =$data->$pcol[7];
						$pcol[8] = "reading_date";				$pval[8] =$data->$pcol[8];
						$pcol[9] = "fmeterno";					$pval[9] =$data->$pcol[9];
						$pcol[10]= "link";						$pval[10]=$data->$pcol[10];
						$pcol[11]= "aid";						$pval[11]=$aid;
						
						$pcol_str = implode(',',$pcol);			$pval_str = implode("','",$pval);
						
						mysql_query("insert into trash_p_billdata(". $pcol_str .") values('". $pval_str ."')");
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