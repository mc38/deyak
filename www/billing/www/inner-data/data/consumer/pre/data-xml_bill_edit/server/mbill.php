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
		
		$postmeter_read		= $data[0];		
		$id					= $data[1];
		
		$tbq = mysql_query("select * from p_billdata where id='". $id ."'");
		if(mysql_num_rows($tbq) >0){
			$tbd = mysql_fetch_object($tbq);
			$link = $tbd->link;
			
			$cq = mysql_query("select * from p_consumerdata where id='".$link."'");
			$cd = mysql_fetch_object($cq);
			
			$bxq = mysql_query("select id from out_bill_xml where link='".$link."' order by id desc");
			$bxd = mysql_fetch_object($bxq);
			
			$rxq = mysql_query("select id from out_reading_xml where link='".$link."' order by id desc");
			$rxd = mysql_fetch_object($rxq);
			
			$bd = new stdClass();
			
			$bd->id					= $tbd->id;
			$bd->mydate				= $tbd->mydate;
			$bd->subdiv_id			= $tbd->subdiv_id;
			$bd->bookno				= $tbd->bookno;
			$bd->cid				= $tbd->cid;
			$bd->billno				= $tbd->billno;
			$bd->premeter_read		= $tbd->premeter_read;
			$bd->premeter_read_date	= DateTime::createFromFormat("d-M-Y", substr($tbd->premeter_read_date,0,11))->format("d-m-Y");
			$bd->status				= $tbd->status;
			$bd->postmeter_read		= $postmeter_read;
			$bd->meterpic			= $tbd->meterpic;
			$bd->reading_date		= substr($tbd->reading_date,0,10);
			$bd->credit				= $tbd->credit;
			$bd->ppunit				= $tbd->ppunit;
			$bd->last_receipt		= $tbd->last_receipt;
			$bd->pend_bill			= $tbd->pend_bill;
			$bd->reserve_unit		= $tbd->reserve_unit;
			$bd->fmeterno			= $tbd->fmeterno;
			$bd->link				= $tbd->link;
			$bd->aid				= $tbd->aid;
			
				
			$reading_date_tstamp = strtotime($bd->reading_date);
			$bill_from_date_tstamp = strtotime($bd->premeter_read_date);
			
			$bill_to_date_tstamp = $reading_date_tstamp;
			if($bill_to_date_tstamp<$bd->mydate){
				$bill_to_date_tstamp=$bd->mydate;
			}
			
			$day_diff = ($bill_to_date_tstamp - $bill_from_date_tstamp)/(3600*24);
			if( $day_diff < 51 ){	
				
				
				/////bildata
				$bdcol = array();						$bdval = array();
				
				$bdcol[0] = "postmeter_read";			$bdval[0] =$bd->$bdcol[0];
				
				$bd_str_arr = array();
				for($i=0;$i<sizeof($bdcol);$i++){
					$bd_str_arr[$i] = $bdcol[$i] ."='". $bdval[$i] ."'";
				}
				$bd_str = implode(',',$bd_str_arr);
				
				
				$xml_data = xml_generate($cd,$bd);
				$read_xml = $xml_data[0];
				$bill_xml = $xml_data[1];
				
				/////reading
				$rcol = array();						$rval = array();
				
				$rcol[0] = "current_reading";			$rval[0] =$read_xml->$rcol[0];
				$rcol[1] = "unit_consumed";				$rval[1] =$read_xml->$rcol[1];
				$rcol[2] = "ppunit";					$rval[2] =$read_xml->$rcol[2];
				
				$r_str_arr = array();
				for($i=0;$i<sizeof($rcol);$i++){
					$r_str_arr[$i] = $rcol[$i] ."='". $rval[$i] ."'";
				}
				$r_str = implode(',',$r_str_arr);
				
				/////billing
				$bcol = array();						$bval = array();
				
				$bcol[0] = "current_reading";			$bval[0] =$bill_xml->$bcol[0];
				$bcol[1] = "billed_unit";				$bval[1] =$bill_xml->$bcol[1];
				$bcol[2] = "energy_charge";				$bval[2] =$bill_xml->$bcol[2];
				$bcol[3] = "fixed_charge";				$bval[3] =$bill_xml->$bcol[3];
				$bcol[4] = "meter_rent";				$bval[4] =$bill_xml->$bcol[4];
				$bcol[5] = "other_charge";				$bval[5] =$bill_xml->$bcol[5];
				$bcol[6] = "diseal_charge";				$bval[6] =$bill_xml->$bcol[6];
				$bcol[7] = "fuel_charge_rate";			$bval[7] =$bill_xml->$bcol[7];
				$bcol[8] = "fuel_charge";				$bval[8] =$bill_xml->$bcol[8];
				$bcol[9] = "gross_charge";				$bval[9] =$bill_xml->$bcol[9];
				$bcol[10]= "rebate_charge";				$bval[10]=$bill_xml->$bcol[10];
				$bcol[11]= "credit_adjustment";			$bval[11]=$bill_xml->$bcol[11];
				$bcol[12]= "net_charge";				$bval[12]=$bill_xml->$bcol[12];
				$bcol[13]= "old_ec";					$bval[13]=$bill_xml->$bcol[13];
				$bcol[14]= "old_uc";					$bval[14]=$bill_xml->$bcol[14];
				$bcol[15]= "sundry";					$bval[15]=$bill_xml->$bcol[15];
				$bcol[16]= "n_rate";					$bval[16]=$bill_xml->$bcol[16];
				$bcol[17]= "energy_charge_breakup";		$bval[17]=$bill_xml->$bcol[17];
				
				$b_str_arr = array();
				for($i=0;$i<sizeof($bcol);$i++){
					$b_str_arr[$i] = $bcol[$i] ."='". $bval[$i] ."'";
				}
				$b_str = implode(',',$b_str_arr);
				
				/////////////////
				$rquery = "update out_reading_xml set ". $r_str ." where id='".$rxd->id."'";
				mysql_query($rquery);
				
				$bquery = "update out_bill_xml set ". $b_str ." where id='".$bxd->id."'";
				mysql_query($bquery);
				
				$bdquery = "update p_billdata set ". $bd_str ." where id='".$bd->id."'";
				mysql_query($bdquery);
				
			}
			
			echo $_POST['c'];
		}
	}
}
else{
	echo 0;
}
?>