<?php
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../spl_func/xml.php");

if(authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		
		$data = json_decode(base64_decode($_POST['d']));
		$s = $data[0];
		//echo base64_decode($s);
		$d = json_decode(base64_decode($s));
		
		$link 					= $d[0];
		$premeter_read_date 	= $d[1];
		$reading_date			= $d[2];
		$status					= $d[3];
		$premeter_read			= $d[4];
		$postmeter_read			= $d[5];
		$ppunit					= $d[6];
		$credit					= $d[7];
		
		$cq = mysql_query("select * from p_consumerdata where id='".$link."'");
		$cd = mysql_fetch_object($cq);
		
		$tbq = mysql_query("select * from p_billdata where link='".$link."'");
		$tbd = mysql_fetch_object($tbq);
		
		
		$billno 				= $cd->subdiv_id ."00".$datetime;
		
		$b_ex_q = mysql_query("select id from out_bill_xml where bill_no='". $billno ."'");
		if(mysql_num_rows($b_ex_q)<1){
			
			$bm_chq = mysql_query("select id from p_billdata_multi where link='". $link ."' and premeter_read_date='".$premeter_read_date ."' and reading_date='". $reading_date ."'");
			if(mysql_num_rows($bm_chq) <1){
			
				$bd = new stdClass();
				
				$bd->id					= $tbd->id;
				$bd->mydate				= $tbd->mydate;
				$bd->subdiv_id			= $tbd->subdiv_id;
				$bd->bookno				= $tbd->bookno;
				$bd->cid				= $tbd->cid;
				$bd->billno				= $billno;
				$bd->premeter_read		= $premeter_read;
				$bd->premeter_read_date	= $premeter_read_date;
				$bd->status				= $status;
				$bd->postmeter_read		= $postmeter_read;
				$bd->meterpic			= $tbd->meterpic;
				$bd->reading_date		= $reading_date;
				$bd->credit				= $credit;
				$bd->ppunit				= $ppunit;
				$bd->last_receipt		= $tbd->last_receipt;
				$bd->pend_bill			= $tbd->pend_bill;
				$bd->reserve_unit		= $tbd->reserve_unit;
				$bd->fmeterno			= $tbd->fmeterno;
				$bd->link				= $tbd->link;
				$bd->aid				= $tbd->aid;
				
				
				$xml_data = xml_generate($cd,$bd);
				$read_xml = $xml_data[0];
				$bill_xml = $xml_data[1];
				
				/////bildata
				$bdcol = array();						$bdval = array();
				
				$bdcol[0] = "mydate";					$bdval[0] =$bd->$bdcol[0];
				$bdcol[1] = "subdiv_id";				$bdval[1] =$bd->$bdcol[1];
				$bdcol[2] = "bookno";					$bdval[2] =$bd->$bdcol[2];
				$bdcol[3] = "cid";						$bdval[3] =$bd->$bdcol[3];
				$bdcol[4] = "billno";					$bdval[4] =$bd->$bdcol[4];
				$bdcol[5] = "premeter_read";			$bdval[5] =$bd->$bdcol[5];
				$bdcol[6] = "premeter_read_date";		$bdval[6] =$bd->$bdcol[6];
				$bdcol[7] = "status";					$bdval[7] =$bd->$bdcol[7];
				$bdcol[8] = "postmeter_read";			$bdval[8] =$bd->$bdcol[8];
				$bdcol[9] = "meterpic";					$bdval[9] =$bd->$bdcol[9];
				$bdcol[10]= "reading_date";				$bdval[10]=$bd->$bdcol[10];
				$bdcol[11]= "credit";					$bdval[11]=$bd->$bdcol[11];
				$bdcol[12]= "ppunit";					$bdval[12]=$bd->$bdcol[12];
				$bdcol[13]= "last_receipt";				$bdval[13]=$bd->$bdcol[13];
				$bdcol[14]= "pend_bill";				$bdval[14]=$bd->$bdcol[14];
				$bdcol[15]= "reserve_unit";				$bdval[15]=$bd->$bdcol[15];
				$bdcol[16]= "fmeterno";					$bdval[16]=$bd->$bdcol[16];
				$bdcol[17]= "link";						$bdval[17]=$bd->$bdcol[17];
				
				$bdcol_str = implode(',',$bdcol);		$bdval_str = implode("','",$bdval);
				
				
				/////reading
				$rcol = array();						$rval = array();
				
				$rcol[0] = "mydate";					$rval[0] =$read_xml->$rcol[0];
				$rcol[1] = "consumer_id";				$rval[1] =$read_xml->$rcol[1];
				$rcol[2] = "subdivision_id";			$rval[2] =$read_xml->$rcol[2];
				$rcol[3] = "book_no";					$rval[3] =$read_xml->$rcol[3];
				$rcol[4] = "bill_from_datetime";		$rval[4] =$read_xml->$rcol[4];
				$rcol[5] = "bill_to_datetime";			$rval[5] =$read_xml->$rcol[5];
				$rcol[6] = "previous_reading";			$rval[6] =$read_xml->$rcol[6];
				$rcol[7] = "current_reading";			$rval[7] =$read_xml->$rcol[7];
				$rcol[8] = "unit_consumed";				$rval[8] =$read_xml->$rcol[8];
				$rcol[9] = "reading_date";				$rval[9] =$read_xml->$rcol[9];
				$rcol[10]= "remarks";					$rval[10]=$read_xml->$rcol[10];
				$rcol[11]= "multiplying_factor";		$rval[11]=$read_xml->$rcol[11];
				$rcol[12]= "ppunit";					$rval[12]=$read_xml->$rcol[12];
				$rcol[13]= "link";						$rval[13]=$read_xml->$rcol[13];
				
				$rcol_str = implode(',',$rcol);			$rval_str = implode("','",$rval);
				
				/////billing
				$bcol = array();						$bval = array();
				
				$bcol[0] = "aid";						$bval[0] =0;
				$bcol[1] = "mydate";					$bval[1] =$bill_xml->$bcol[1];
				$bcol[2] = "consumer_id";				$bval[2] =$bill_xml->$bcol[2];
				$bcol[3] = "subdivision_id";			$bval[3] =$bill_xml->$bcol[3];
				$bcol[4] = "book_no";					$bval[4] =$bill_xml->$bcol[4];
				$bcol[5] = "tariff_id";					$bval[5] =$bill_xml->$bcol[5];
				$bcol[6] = "bill_from_datetime";		$bval[6] =$bill_xml->$bcol[6];
				$bcol[7] = "bill_to_datetime";			$bval[7] =$bill_xml->$bcol[7];
				$bcol[8] = "bill_datetime";				$bval[8] =$bill_xml->$bcol[8];
				$bcol[9] = "bill_generate_datetime";	$bval[9] =$bill_xml->$bcol[9];
				$bcol[10]= "bill_due_datetime";			$bval[10]=$bill_xml->$bcol[10];
				$bcol[11]= "previous_reading";			$bval[11]=$bill_xml->$bcol[11];
				$bcol[12]= "current_reading";			$bval[12]=$bill_xml->$bcol[12];
				$bcol[13]= "billed_unit";				$bval[13]=$bill_xml->$bcol[13];
				$bcol[14]= "energy_charge";				$bval[14]=$bill_xml->$bcol[14];
				$bcol[15]= "fixed_charge";				$bval[15]=$bill_xml->$bcol[15];
				$bcol[16]= "meter_rent";				$bval[16]=$bill_xml->$bcol[16];
				$bcol[17]= "other_charge";				$bval[17]=$bill_xml->$bcol[17];
				$bcol[18]= "diseal_charge";				$bval[18]=$bill_xml->$bcol[18];
				$bcol[19]= "fuel_charge_rate";			$bval[19]=$bill_xml->$bcol[19];
				$bcol[20]= "fuel_charge";				$bval[20]=$bill_xml->$bcol[20];
				$bcol[21]= "gross_charge";				$bval[21]=$bill_xml->$bcol[21];
				$bcol[22]= "rebate_charge";				$bval[22]=$bill_xml->$bcol[22];
				$bcol[23]= "credit_adjustment";			$bval[23]=$bill_xml->$bcol[23];
				$bcol[24]= "net_charge";				$bval[24]=$bill_xml->$bcol[24];
				$bcol[25]= "old_ec";					$bval[25]=$bill_xml->$bcol[25];
				$bcol[26]= "old_uc";					$bval[26]=$bill_xml->$bcol[26];
				$bcol[27]= "sundry";					$bval[27]=$bill_xml->$bcol[27];
				$bcol[28]= "n_rate";					$bval[28]=$bill_xml->$bcol[28];
				$bcol[29]= "bill_no";					$bval[29]=$bill_xml->$bcol[29];
				$bcol[30]= "energy_charge_breakup";		$bval[30]=$bill_xml->$bcol[30];
				$bcol[31]= "link";						$bval[31]=$bill_xml->$bcol[31];
				
				$bcol_str = implode(',',$bcol);			$bval_str = implode("','",$bval);
				
				mysql_query("insert into p_billdata_multi(". $bdcol_str .") values('". $bdval_str ."')");
				mysql_query("insert into out_reading_xml(". $rcol_str .") values('". $rval_str ."')");
				mysql_query("insert into out_bill_xml(". $bcol_str .") values('". $bval_str ."')");
				
				echo $_POST['c'];
			}
		}
	}
}
else{
	echo 0;
}
?>