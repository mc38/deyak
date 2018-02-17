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
		
		$id					= $data[0];
		$status				= $data[1];
		$postmeter_read		= $data[2];
		$meterpic			= $data[3];
		
		
		$tbq = mysql_query("select * from p_billdata where id='". $id ."'");
		if(mysql_num_rows($tbq) >0){
			$tbd = mysql_fetch_object($tbq);
			$link = $tbd->link;
			
			$reading_date 	= date(rand(2,15) .'-m-Y',$tbd->mydate);
			$billno 		= $tbd->subdiv_id ."00". $datetime;
			
			$cq = mysql_query("select * from p_consumerdata where id='".$link."'");
			$cd = mysql_fetch_object($cq);
			
			$bd = new stdClass();
			
			$bd->id					= $tbd->id;
			$bd->mydate				= $tbd->mydate;
			$bd->subdiv_id			= $tbd->subdiv_id;
			$bd->bookno				= $tbd->bookno;
			$bd->cid				= $tbd->cid;
			$bd->billno				= $billno;
			$bd->premeter_read		= $tbd->premeter_read;
			$bd->premeter_read_date	= DateTime::createFromFormat("d-M-Y", substr($tbd->premeter_read_date,0,11))->format("d-m-Y");
			$bd->status				= $status;
			$bd->postmeter_read		= $postmeter_read;
			$bd->meterpic			= $meterpic;
			$bd->reading_date		= substr($reading_date,0,10);
			$bd->credit				= $tbd->credit;
			$bd->ppunit				= $tbd->ppunit;
			$bd->last_receipt		= $tbd->last_receipt;
			$bd->pend_bill			= $tbd->pend_bill;
			$bd->reserve_unit		= $tbd->reserve_unit;
			$bd->fmeterno			= $tbd->fmeterno;
			$bd->link				= $tbd->link;
			$bd->aid				= $tbd->aid;
			
			
			
			/////bildata
			$bdcol = array();						$bdval = array();
			
			$bdcol[0] = "billno";			$bdval[0] =$bd->$bdcol[0];
			$bdcol[1] = "status";			$bdval[1] =$bd->$bdcol[1];
			$bdcol[2] = "postmeter_read";	$bdval[2] =$bd->$bdcol[2];
			$bdcol[3] = "meterpic";			$bdval[3] =$bd->$bdcol[3];
			$bdcol[4] = "reading_date";		$bdval[4] =$bd->$bdcol[4];
			
			$bd_str_arr = array();
			for($i=0;$i<sizeof($bdcol);$i++){
				$bd_str_arr[$i] = $bdcol[$i] ."='". $bdval[$i] ."'";
			}
			$bd_str = implode(',',$bd_str_arr);
			
				
			$reading_date_tstamp = strtotime($bd->reading_date);
			$bill_from_date_tstamp = strtotime($bd->premeter_read_date);
			
			$bill_to_date_tstamp = $reading_date_tstamp;
			if($bill_to_date_tstamp<$bd->mydate){
				$bill_to_date_tstamp=$bd->mydate;
			}
			
			$day_diff = ($bill_to_date_tstamp - $bill_from_date_tstamp)/(3600*24);
			if( $day_diff < 51 ){	
				
				
				$xml_data = xml_generate($cd,$bd);
				$read_xml = $xml_data[0];
				$bill_xml = $xml_data[1];
				
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
				
				mysql_query("insert into out_reading_xml(". $rcol_str .") values('". $rval_str ."')");
				mysql_query("insert into out_bill_xml(". $bcol_str .") values('". $bval_str ."')");
				
				
			}
			
			$bdquery = "update p_billdata set ". $bd_str ." where id='".$bd->id."'";
			mysql_query($bdquery);
			
			echo $_POST['c'];
		}
		
	}
}
else{
	echo 0;
}
?>