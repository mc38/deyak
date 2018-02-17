<?php
ini_set('max_execution_time', 10000);
require_once("db/command.php");
require_once("plugin/func/authentication.php");
require_once("plugin/func/rcrypt.php");

if(authenticate()){
	if($_POST){
	if(
		isset($_POST['s']) && $_POST['s']!="" 
		&& 
		isset($_POST['a']) && $_POST['a']!="" 
		&& 
		isset($_POST['d']) && $_POST['d']!="" 
	){
		
		$subdiv = $_POST['s'];
		$aid	= $_POST['a'];
		$d = $_POST['d'];
		
		$mydate = strtotime("1-".$d);
		
		
		$aq = mysql_query("select id,subdiv,agent_pin,imei,name,contact from agent_info where id='".$aid."' and status='0'");
		if(mysql_num_rows($aq) ==1){
			
			$bcode = 1;
			$bq = mysql_query("select id from agent_info where subdiv='".$subdiv."' order by id");
			if(mysql_num_rows($bq) >0){
				while($bd = mysql_fetch_object($bq)){
					if($bd->id == $aid ){
						break;
					}
					$bcode++;
				}
			}
			$bcode = $bcode ."";
			if(strlen($bcode) <2){
				$bcode = "0".$bcode;
			}
		
			$subq = mysql_query("select sid,accessurl from settings_subdiv_data where id='".$subdiv."'");
			if(mysql_num_rows($subq) ==1){
				$subd = mysql_fetch_object($subq);

				
				if(! file_exists("temp/")){
					mkdir("temp");
				}
				$fname = "deyakdb_". date('d-m-Y_h-i-s_a',$datetime);
				$file = "temp/". $fname;
				
				////////////////////////////////////////////////////////////////////////////////////////
				$ad = mysql_fetch_object($aq);
				//*encrypt details*//
				$crypto = new rcrypt();
				$key = $ad->imei;
				$imei_key = $ad->agent_pin;
				$imei = $crypto->rencode($imei_key,$ad->imei);
				/*_________________________________________________________________*/
				
				$db = new PDO("sqlite:" . $file .".db");
				
				/*__agent_data_______________________________________________________________*/
				$query_agent_struct = '
				CREATE TABLE IF NOT EXISTS `appdata` (
				  `aid` text NOT NULL,
				  `pin` text NOT NULL,
				  `user_detail` text NOT NULL,
				  `user_contact` text NOT NULL,
				  `bcode` text NOT NULL,
				  `imei` text NOT NULL,
				  `mydate` text NOT NULL,
				  `subdiv` text NOT NULL,
				  `due_date` text NOT NULL,
				  `backup_max` text NOT NULL,
				  `upload_max` text NOT NULL,
				  `system` text NOT NULL,
				  `accessurl` text NOT NULL,
				  `holidays` text NOT NULL
				)
				';
				$db->exec($query_agent_struct);
				
				$aname = strtoupper($ad->name);
				$myd = date('d-m-Y',$mydate);
				
				$settings = array();
				$sq = mysql_query("select * from zzdev");
				while($sd = mysql_fetch_object($sq)){
					$settings[$sd->parameter]=$sd->value;
				}

				$hfrmdate = strtotime("-1 days",$mydate);
				$htodate = strtotime("+45 days",$mydate);
				$hq = mysql_query("select datetime from settings_holidays where datetime>". $hfrmdate ." and datetime<". $htodate);
				$holidays = array();
				while($hd = mysql_fetch_object($hq)){
					$holidays[] = $hd->datetime;
				}
				$holistr = base64_encode(json_encode($holidays));
				
				$acol = array();					$aval = array();
						
				$acol[]="aid";						$aval[]=0;
				$acol[]="pin";						$aval[]=$crypto->rencode($key,$ad->agent_pin);
				$acol[]="user_detail";				$aval[]=$crypto->rencode($key,$aname);
				$acol[]="user_contact";				$aval[]=$crypto->rencode($key,$ad->contact);
				$acol[]="bcode";					$aval[]=$crypto->rencode($key,$bcode);
				$acol[]="imei";						$aval[]=$imei;
				$acol[]="mydate";					$aval[]=$crypto->rencode($key,$myd);
				$acol[]="subdiv";					$aval[]=$crypto->rencode($key,$subd->sid);
				$acol[]="due_date";					$aval[]=$crypto->rencode($key,$settings['DUE_DATE']);
				$acol[]="backup_max";				$aval[]=$crypto->rencode($key,$settings['BACKUP_MAX']);
				$acol[]="upload_max";				$aval[]=$crypto->rencode($key,$settings['UPLOAD_MAX']);
				$acol[]="system";					$aval[]=$crypto->rencode($key,$settings['SYSTEM']);
				$acol[]="accessurl";				$aval[]=$crypto->rencode($key,$subd->accessurl);
				$acol[]="holidays";					$aval[]=$crypto->rencode($key,$holistr);
				
				$acol_str = implode(',',$acol);		$aval_str = implode("','",$aval);
				$query = "insert into appdata(". $acol_str .") values('". $aval_str ."')";
				$db->exec($query);
				
				
				
				/*____m_data___________________________________________________*/
				$query_mdata_struct = '
				CREATE TABLE IF NOT EXISTS `mdata` (
				`id` text NOT NULL,
				`bid` text NOT NULL,
				`aid` text,
				`mydate` text NOT NULL,
				`equation_category` text NOT NULL,
				`ocr` text NOT NULL,
				`survey` text NOT NULL,
				`subdivision` text NOT NULL,
				`dtrno` text NOT NULL,
				`cid` text NOT NULL,
				`oldcid` text NOT NULL,
				`qrcode` text NOT NULL,
				`gps_lati` text,
				`gps_longi` text,
				`gps_alti` text,
				`consumer_name` text NOT NULL,
				`consumer_address` text NOT NULL,
				`consumer_category` text NOT NULL,
				`connection_type` text NOT NULL,
				`mfactor` text NOT NULL,
				`connection_load` text NOT NULL,
				`meter_no` text NOT NULL,
				`reserve_unit` text NOT NULL,
				`premeter_read_date` text NOT NULL,
				`premeter_read` text NOT NULL,
				`slab` text NOT NULL,
				`meter_rent` text NOT NULL,
				`principal_arrear` text NOT NULL,
				`arrear_surcharge` text NOT NULL,
				`current_surcharge` text NOT NULL,
				`adjustment` text NOT NULL,
				`rate_eduty` text NOT NULL,
				`rate_surcharge` text NOT NULL,
				`rate_fppa` text NOT NULL,
				`multibill` text NOT NULL,
				`prevbillduedate` text NOT NULL,
				`premeterstatus` text NOT NULL,
				`cs_pa` text NOT NULL,
				`blnk_3` text,
				`blnk_4` text,
				`blnk_5` text,
				`blnk_6` text,
				`blnk_7` text,
				`blnk_8` text,
				`blnk_9` text,
				`n_billno` text,
				`n_status` text,
				`n_reading_date` text,
				`n_postmeter_read` text,
				`n_meterpic` text,
				`n_meterpic_binary` text,
				`n_unit_consumed` text,
				`n_unit_billed` text,
				`n_consumption_day` text,
				`n_due_date` text,
				`n_energy_brkup` text,
				`n_energy_amount` text,
				`n_subsidy` text,
				`n_total_energy_charge` text,
				`n_fixed_charge` text,
				`n_electricity_duty` text,
				`n_fppa_charge` text,
				`n_current_demand` text,
				`n_total_arrear` text,
				`n_net_bill_amount` text,
				`n_net_bill_amount_after_duedate` text,
				`n_gps_verification` text,
				`n_ocr_analysis` text,
				`n_pf` text,
				`n_current_surcharge` text,
				`n_meter_rent` text,
				`n_unit_pf` text,
				`n_apdcl_billno` text,
				`n_curr_reading` text,
				`n_blnk_5` text,
				`n_blnk_6` text,
				`n_blnk_7` text,
				`n_blnk_8` text,
				`n_blnk_9` text,
				`n_survey_gps_lati` text ,
				`n_survey_gps_longi` text,
				`n_survey_gps_alti` text,
				`n_survey_meterheight` text,
				`n_survey_mobno` text,
				`n_survey_meterslno` text,
				`n_survey_metertype` text,
				`n_survey_consumertype` text,
				`n_survey_nwsignal` text,
				`s_name` text,
				`s_cid` text,
				`s_meterno` text,
				`s_address` text,
				`s_reject` text
				)
				';
				$db->exec($query_mdata_struct);
				
				
				
				$db->exec("pragma synchronous = off;");
				//////////////////////////////////////////////////////////////////////////////
				
					
					$dtrarr = array();
					$dtrq = mysql_query("select dtr from agent_dtr where aid='". $ad->id ."' and subdiv='". $subdiv ."'");
					while($dtrd = mysql_fetch_object($dtrq)){
						$dtrarr[] = $dtrd->dtr;
					}
					
					if(sizeof($dtrarr)>0){
						$dtrstr = implode("','",$dtrarr);
						
						$mq = mysql_query("select * from m_data where c_mydate='". $mydate ."' and c_subdiv_id='". $subd->sid ."' and c_import_status=0 and c_pass_status=0");
						if(mysql_num_rows($mq) >0){
							$mdata = '';
							
							while($md = mysql_fetch_object($mq)){
										
								$mcol = array();					$mval = array();
								
								$mcol[]="id";						$mval[]=$crypto->rencode($key,$md->id);
								$mcol[]="bid";						$mval[]=$crypto->rencode($key,$md->c_bid);
								$mcol[]="mydate";					$mval[]=$crypto->rencode($key,$md->c_mydate);
								$mcol[]="equation_category";		$mval[]=$crypto->rencode($key,$md->out_equation_category);
								$mcol[]="ocr";						$mval[]=$crypto->rencode($key,$md->c_ocr);
								$mcol[]="survey";					$mval[]=$crypto->rencode($key,$md->c_survey);
								$mcol[]="subdivision";				$mval[]=$crypto->rencode($key,$md->out_subdivision);
								$mcol[]="dtrno";					$mval[]=$crypto->rencode($key,$md->out_dtrno);
								$mcol[]="cid";						$mval[]=$crypto->rencode($key,$md->out_cid);
								$mcol[]="oldcid";					$mval[]=$crypto->rencode($key,$md->out_oldcid);
								$mcol[]="qrcode";					$mval[]=$crypto->rencode($key,$md->out_qrcode);
								$mcol[]="gps_lati";					$mval[]=$crypto->rencode($key,$md->out_gps_lati);
								$mcol[]="gps_longi";				$mval[]=$crypto->rencode($key,$md->out_gps_longi);
								$mcol[]="gps_alti";					$mval[]=$crypto->rencode($key,$md->out_gps_alti);
								$mcol[]="consumer_name";			$mval[]=$crypto->rencode($key,$md->out_consumer_name);
								$mcol[]="consumer_address";			$mval[]=$crypto->rencode($key,$md->out_consumer_address);
								$mcol[]="consumer_category";		$mval[]=$crypto->rencode($key,$md->out_consumer_category);
								$mcol[]="connection_type";			$mval[]=$crypto->rencode($key,$md->out_connection_type);
								$mcol[]="mfactor";					$mval[]=$crypto->rencode($key,$md->out_mfactor);
								$mcol[]="connection_load";			$mval[]=$crypto->rencode($key,$md->out_connection_load);
								$mcol[]="meter_no";					$mval[]=$crypto->rencode($key,$md->out_meter_no);
								$mcol[]="reserve_unit";				$mval[]=$crypto->rencode($key,$md->out_reserve_unit);
								$mcol[]="premeter_read_date";		$mval[]=$crypto->rencode($key,$md->out_premeter_read_date);
								$mcol[]="premeter_read";			$mval[]=$crypto->rencode($key,$md->out_premeter_read);
								$mcol[]="slab";						$mval[]=$crypto->rencode($key,$md->out_slab);
								$mcol[]="meter_rent";				$mval[]=$crypto->rencode($key,$md->out_meter_rent);
								$mcol[]="principal_arrear";			$mval[]=$crypto->rencode($key,$md->out_principal_arrear);
								$mcol[]="arrear_surcharge";			$mval[]=$crypto->rencode($key,$md->out_arrear_surcharge);
								$mcol[]="current_surcharge";		$mval[]=$crypto->rencode($key,$md->out_current_surcharge);
								$mcol[]="adjustment";				$mval[]=$crypto->rencode($key,$md->out_adjustment);
								$mcol[]="rate_eduty";				$mval[]=$crypto->rencode($key,$md->out_rate_eduty);
								$mcol[]="rate_surcharge";			$mval[]=$crypto->rencode($key,$md->out_rate_surcharge);
								$mcol[]="rate_fppa";				$mval[]=$crypto->rencode($key,$md->out_rate_fppa);
								$mcol[]="multibill";				$mval[]=$crypto->rencode($key,$md->out_multibill);
								$mcol[]="prevbillduedate";			$mval[]=$crypto->rencode($key,$md->out_prevbillduedate);
								$mcol[]="premeterstatus";			$mval[]=$crypto->rencode($key,$md->out_premeterstatus);
								$mcol[]="cs_pa";					$mval[]=$crypto->rencode($key,$md->out_cs_pa);
								$mcol[]="s_reject";					$mval[]="0";
								
								$mcol_str = implode(',',$mcol);		$mval_str = implode("','",$mval);
								
								$mdata = "insert into mdata(". $mcol_str .") values('". $mval_str ."');";
								$db->exec($mdata);
							}
							
							
						
							$db = null;
							
							echo 'Data Process Complete';
							
							//////////////////////file download//////////////////////////////////////////////////////////////
							
							$fhandle = fopen("temp/". $fname .".php",'w');
							$dir = dirname(__FILE__);
							$dbcode = file_get_contents($dir."/dbcode.txt");
							file_put_contents("temp/". $fname .".php",$dbcode);
							fclose($fhandle);
							
							echo '<script>function ddown(){window.location.href = "temp/'.$fname.'.php?fname='.$fname.'";}</script><body onload="ddown();"></body>';
						
						}else{
							echo 'Data is not available';
						}
					}else{
						echo 'Agent is not assigned for any DTR';
					}


			}else{
				echo 'Problem with subdivision data';
			}
		}
		else{
			echo 'Agent is not available';
		}
		
		
		
	}
	else{
		echo 'Error';
	}
	}
	else{
		echo 'Error';
	}
}
else{
	echo "Unauthorized user";
}
?>