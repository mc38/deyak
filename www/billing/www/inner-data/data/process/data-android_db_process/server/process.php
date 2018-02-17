<?php
ini_set('max_execution_time', 10000);
require_once("../../../../../db/command.php");
require_once("../../../../../plugin/func/authentication.php");
require_once("../../../../../plugin/func/logbook.php");
require_once("../../../../../../config/config.php");

if($u = authenticate()){
	if(
		isset($_POST['c']) && isset($_POST['d'])
		&&
		$_POST['c'] !="" && $_POST['d'] !=""
	){
		$logdata = "";
		
		$data = json_decode(base64_decode($_POST['d']));
		
		$mydate = $data[0];
		$subdiv = $data[1];
		
		
		$q = mysql_query("select * from bill_details where subdiv_id='". $subdiv ."' and mydate='". strtotime($mydate)."' and status='0' limit 0,".$migration_batchno);
		if(mysql_num_rows($q)>0){
			while($d = mysql_fetch_object($q)){
				
				$cq = mysql_query("select * from consumer_details where id='". $d->conid ."'");
				$rq = mysql_query("select * from bill_reading where id='". $d->readid ."'");
				$bq = mysql_query("select * from bill_amount where id='". $d->baid ."'");
				
				if((mysql_num_rows($cq) ==1) && (mysql_num_rows($bq) ==1) && (mysql_num_rows($rq) ==1)){
					$cd = mysql_fetch_object($cq);
					$bd = mysql_fetch_object($bq);
					$rd = mysql_fetch_object($rq);
					
					$cq = mysql_query("select id from m_data where c_bid='". $d->id ."'");
					if(mysql_num_rows($cq)>0){
						$logdata .= 'This billing data is already made for android.<br/>';
					}else{
							
							$subq = mysql_query("select * from settings_subdiv_data where sid='". $subdiv ."'");
							$subd = mysql_fetch_object($subq);
							
							$cateq = mysql_query("select * from settings_consumer_cate where id='". $cd->category ."'");
							$cated = mysql_fetch_object($cateq);
							
							$mq = mysql_query("select * from settings_meter_cate where id='". $cd->meter_cate ."'");
							$md = mysql_fetch_object($mq);
							
							$multibill = 1;
							$mbq = mysql_query("select mydate from bill_details where conid='".$d->conid."' order by id desc limit 1,1");
							if(mysql_num_rows($mbq)>0){
								$mbd = mysql_fetch_object($mbq);
								$multibill = round((($d->mydate - $mbd->mydate)/30),0);
							}

							$slab = array($cated->slab, $cated->pfslab);
							$slabstr = base64_encode(json_encode($slab));
							
							
							$col 		= array();					$coldata		= array();
							
					/*00*/	$col[] 	= "c_bid";						$coldata[]	= $d->id;
					/*01*/	$col[] 	= "c_subdiv_id";				$coldata[]	= $subdiv;
					/*02*/	$col[] 	= "c_mydate";					$coldata[]	= strtotime($mydate);
					/*03*/	$col[] 	= "out_equation_category";		$coldata[]	= 0;
					/*04*/	$col[] 	= "c_ocr";						$coldata[]	= 0;
					/*05*/	$col[] 	= "c_survey";					$coldata[]	= 1;
					/*06*/	$col[] 	= "out_subdivision";			$coldata[]	= $subd->name .'('. $subd->sid .')';
					/*07*/	$col[] 	= "out_dtrno";					$coldata[]	= $cd->dtrno;
					/*08*/	$col[] 	= "out_cid";					$coldata[]	= $cd->cid;
					/*09*/	$col[] 	= "out_oldcid";					$coldata[]	= $cd->oldcid;
					/*10*/	$col[] 	= "out_qrcode";					$coldata[]	= md5($cd->id ."_". $cd->cid);
					/*11*/	$col[] 	= "out_gps_lati";				$coldata[]	= $cd->gps_lati;
					/*12*/	$col[] 	= "out_gps_longi";				$coldata[]	= $cd->gps_longi;
					/*13*/	$col[] 	= "out_gps_alti";				$coldata[]	= $cd->gps_alti;
					/*14*/	$col[] 	= "out_consumer_name";			$coldata[]	= strtoupper($cd->consumer_name);
					/*15*/	$col[] 	= "out_consumer_address";		$coldata[]	= strtoupper($cd->consumer_address);
					/*16*/	$col[] 	= "out_consumer_category";		$coldata[]	= $cated->name;
					/*17*/	$col[] 	= "out_connection_type";		$coldata[]	= $md->phase .' Phase';
					/*18*/	$col[] 	= "out_mfactor";				$coldata[]	= $cd->mfactor;
					/*19*/	$col[] 	= "out_connection_load";		$coldata[]	= $cd->cload .' '. $cd->load_unit;
					/*20*/	$col[] 	= "out_meter_no";				$coldata[]	= strtoupper($cd->meterno);
					/*21*/	$col[] 	= "out_reserve_unit";			$coldata[]	= $rd->avarage_unit;
					/*22*/	$col[] 	= "out_premeter_read_date";		$coldata[]	= $rd->prev_read_date;
					/*23*/	$col[] 	= "out_premeter_read";			$coldata[]	= $rd->prev_read;
					/*24*/	$col[] 	= "out_slab";					$coldata[]	= $slabstr;
					/*25*/	$col[] 	= "out_meter_rent";				$coldata[]	= $md->rent;
					/*26*/	$col[] 	= "out_principal_arrear";		$coldata[]	= $bd->pa;
					/*27*/	$col[] 	= "out_arrear_surcharge";		$coldata[]	= $bd->asr;
					/*28*/	$col[] 	= "out_current_surcharge";		$coldata[]	= 0;
					/*29*/	$col[] 	= "out_adjustment";				$coldata[]	= $bd->adjustment;
					/*30*/	$col[] 	= "out_rate_eduty";				$coldata[]	= $cated->electricity_duty;
					/*31*/	$col[] 	= "out_rate_surcharge";			$coldata[]	= $cated->surcharge;
					/*32*/	$col[] 	= "out_rate_fppa";				$coldata[]	= $cated->fppa;
					/*33*/	$col[] 	= "out_multibill";				$coldata[]	= $multibill;
					/*34*/	$col[] 	= "out_prevbillduedate";		$coldata[]	= $bd->due_datetime;
					/*35*/	$col[] 	= "out_premeterstatus";			$coldata[]	= $rd->pre_meterstatus;
					/*36*/	$col[] 	= "out_cs_pa";					$coldata[]	= $bd->cs_pa;
							
							$colstr		= implode(',',$col);		$coldatastr		= implode("','",$coldata);
							
							mysql_query("insert into m_data(". $colstr .") values('". $coldatastr ."')");
							$did = mysql_insert_id();
							mysql_query("update bill_details set status=1 where id='". $d->id ."'");
							$logdata .= 'Available data - '. mysql_num_rows($q) .'<br/>Android DB is created for this consumer ('. $cd->cid .').<br/>';
						
					}
				}
			}
			echo 3;
		}else{
			echo 2;
		}
	}else{
		echo 1;	
	}
}
else{
	echo 0;
}
?>