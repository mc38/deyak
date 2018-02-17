<?php
include "../../db/command.php";
include "../../plugin/authentication.php";
include "../../plugin/data_transfer.php";
include "../../plugin/rcrypt.php";

$dt = new data_transfer();
if($dt->data_receive()){
	$send_data = "";
	
	if($a = authenticate()){
		
		$crypto = new rcrypt();
		$key = $_POST['i'];

		$query = "select * from m_data where c_pass_status=2 and c_import_status=1 and c_done=0 and in_aid='". $a ."'";
		$q = mysql_query($query);
		if(mysql_num_rows($q) >0){
			$md = mysql_fetch_object($q);

			$mcol = array();					$mval = array();
								
			/*00*/ $mcol[]="id";						$mval[]=$crypto->rencode($key,$md->id);
			/*01*/ $mcol[]="bid";						$mval[]=$crypto->rencode($key,$md->c_bid);
			/*02*/ $mcol[]="mydate";					$mval[]=$crypto->rencode($key,$md->c_mydate);
			/*03*/ $mcol[]="equation_category";			$mval[]=$crypto->rencode($key,$md->out_equation_category);
			/*04*/ $mcol[]="ocr";						$mval[]=$crypto->rencode($key,$md->c_ocr);
			/*05*/ $mcol[]="survey";					$mval[]=$crypto->rencode($key,$md->c_survey);
			/*06*/ $mcol[]="subdivision";				$mval[]=$crypto->rencode($key,$md->out_subdivision);
			/*07*/ $mcol[]="dtrno";						$mval[]=$crypto->rencode($key,$md->out_dtrno);
			/*08*/ $mcol[]="cid";						$mval[]=$crypto->rencode($key,$md->out_cid);
			/*09*/ $mcol[]="oldcid";					$mval[]=$crypto->rencode($key,$md->out_oldcid);
			/*10*/ $mcol[]="qrcode";					$mval[]=$crypto->rencode($key,$md->out_qrcode);
			/*11*/ $mcol[]="gps_lati";					$mval[]=$crypto->rencode($key,$md->out_gps_lati);
			/*12*/ $mcol[]="gps_longi";					$mval[]=$crypto->rencode($key,$md->out_gps_longi);
			/*13*/ $mcol[]="gps_alti";					$mval[]=$crypto->rencode($key,$md->out_gps_alti);
			/*14*/ $mcol[]="consumer_name";				$mval[]=$crypto->rencode($key,$md->out_consumer_name);
			/*15*/ $mcol[]="consumer_address";			$mval[]=$crypto->rencode($key,$md->out_consumer_address);
			/*16*/ $mcol[]="consumer_category";			$mval[]=$crypto->rencode($key,$md->out_consumer_category);
			/*17*/ $mcol[]="connection_type";			$mval[]=$crypto->rencode($key,$md->out_connection_type);
			/*18*/ $mcol[]="mfactor";					$mval[]=$crypto->rencode($key,$md->out_mfactor);
			/*19*/ $mcol[]="connection_load";			$mval[]=$crypto->rencode($key,$md->out_connection_load);
			/*20*/ $mcol[]="meter_no";					$mval[]=$crypto->rencode($key,$md->out_meter_no);
			/*21*/ $mcol[]="reserve_unit";				$mval[]=$crypto->rencode($key,$md->out_reserve_unit);
			/*22*/ $mcol[]="premeter_read_date";		$mval[]=$crypto->rencode($key,$md->out_premeter_read_date);
			/*23*/ $mcol[]="premeter_read";				$mval[]=$crypto->rencode($key,$md->out_premeter_read);
			/*24*/ $mcol[]="slab";						$mval[]=$crypto->rencode($key,$md->out_slab);
			/*25*/ $mcol[]="meter_rent";				$mval[]=$crypto->rencode($key,$md->out_meter_rent);
			/*26*/ $mcol[]="principal_arrear";			$mval[]=$crypto->rencode($key,$md->out_principal_arrear);
			/*27*/ $mcol[]="arrear_surcharge";			$mval[]=$crypto->rencode($key,$md->out_arrear_surcharge);
			/*28*/ $mcol[]="current_surcharge";			$mval[]=$crypto->rencode($key,$md->out_current_surcharge);
			/*29*/ $mcol[]="adjustment";				$mval[]=$crypto->rencode($key,$md->out_adjustment);
			/*30*/ $mcol[]="rate_eduty";				$mval[]=$crypto->rencode($key,$md->out_rate_eduty);
			/*31*/ $mcol[]="rate_surcharge";			$mval[]=$crypto->rencode($key,$md->out_rate_surcharge);
			/*32*/ $mcol[]="rate_fppa";					$mval[]=$crypto->rencode($key,$md->out_rate_fppa);
			/*33*/ $mcol[]="multibill";					$mval[]=$crypto->rencode($key,$md->out_multibill);
			/*34*/ $mcol[]="prevbillduedate";			$mval[]=$crypto->rencode($key,$md->out_prevbillduedate);
			/*35*/ $mcol[]="premeterstatus";			$mval[]=$crypto->rencode($key,$md->out_premeterstatus);
			/*36*/ $mcol[]="cs_pa";						$mval[]=$crypto->rencode($key,$md->out_cs_pa);
			/*37*/ $mcol[]="s_reject";					$mval[]="1";
			
			//$mcol_str = implode(',',$mcol);		$mval_str = implode("','",$mval);
			
			//$mdata = "insert into mdata(". $mcol_str .") values('". $mval_str ."')";

			$mdata = base64_encode(json_encode($mval));

			mysql_query("update m_data set c_import_status=0 and c_pass_status=2 where id='". $md->id ."'");

			$send_data = $mdata;
		}else{
			$send_data = 1;
		}
		
	}else{
		$send_data = 0;
	}
	
	$dt->data_send($send_data);
}
?>