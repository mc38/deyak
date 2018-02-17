<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$aid = $data[2];
	$rdate = $data[3];
	$bk= $data[4];
	$cid= $data[5];
	
	
			
			$where = "";
			
			if($aid !=""){
				$where .= " and aid='".$aid."'";
			}
			
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			
			if($rdate !=""){
				$where .= " and reading_date like '".date('d-m-Y',strtotime($rdate))."%'";
			}
			
			if($cid !=""){
				$where .= " and cid like '%".$cid."'";
			}
			
			$query ="select * from p_billdata where subdiv_id='".$s."' and mydate='".strtotime($sd)."' and status<>''".$where;
			$q = mysql_query($query);
			
			if(mysql_num_rows($q) >0){
				echo 'Total no of Print : '. mysql_num_rows($q) ."\n\n";
				while($d = mysql_fetch_object($q)){
					$cq = mysql_query("select * from p_consumerdata where id='".$d->link."'");
					$cd = mysql_fetch_object($cq);
					
					$bq = mysql_query("select * from out_bill_xml where link='".$d->link."'");
					if(mysql_num_rows($bq)==1){
						$bd = mysql_fetch_object($bq);
						
						$rq = mysql_query("select * from out_reading_xml where link='".$d->link."'");
						$rd = mysql_fetch_object($rq);
						
						$print_string = "\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "             :: TSECL Billing ::\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "          * Designed and Developed by *\n";
						$print_string .= "           * ARK Informatics (P) Ltd *\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "Sub Div : ". $cd->subdiv ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "Bookno  : ". $d->bookno ."\n";
						$print_string .= "Con ID  : ". $d->cid ."\n";
						$print_string .= "". $cd->consumer_name ."\n";
						$print_string .= "". $cd->consumer_address ."\n\n";
						$print_string .= "Bill No : ". $d->billno ."\n";
						$print_string .= "Bill Period : ". $bd->bill_from_datetime ." to ". $bd->bill_to_datetime ."\n";
						$print_string .= "Bill Date : ". $bd->bill_to_datetime ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "    ";
						$print_string .= "Tariff ID.         : ". $cd->tariff_id ."\n";
						$print_string .= "    ";
						$print_string .= "Meter No.          : ". $cd->meterno ."\n";
						$print_string .= "    ";
						$print_string .= "Connection         : ". $cd->phase ." Phase\n";
						$print_string .= "    ";
						$print_string .= "Multiplying Factor : ". $cd->mfactor ."\n";
						$print_string .= "    ";
						$print_string .= "Contracted Load    : ". $cd->cload ." ". $cd->load_unit ."\n";
						$print_string .= "    ";
						$print_string .= "Curr Rdng          : ". $bd->current_reading ."\n";
						$print_string .= "    ";
						$print_string .= "Prev Rdng          : ". $bd->previous_reading ."\n";
						$print_string .= "    ";
						$print_string .= "PP Unit            : ". $d->ppunit ."\n";
						$print_string .= "    ";
						$print_string .= "Unit Consumed      : ". $rd->unit_consumed ."\n";
						$print_string .= "    ";
						$print_string .= "Unit Billed        : ". $bd->billed_unit ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "                   # BILL #\n";
						
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "  Slab       Unit        Rate       Charge\n";
						$print_string .= "-----------------------------------------------\n";
						
						$slab = json_decode($bd->energy_charge_breakup);
						for($i=0;$i<sizeof($slab);$i++){
							if(is_array($slab[$i])){
								$slab_data= $slab[$i];
							}
							else{
								$slab_str = str_replace(']','',str_replace('[','',$slab[$i]));
								$slab_data = explode(',',$slab_str);
							}
							$print_string .= " ".$slab_data[0]."   ".$slab_data[1]."  Rs ".$slab_data[2]."  Rs ".$slab_data[3]."\n";
						}
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Enery Charge -                   = ". $bd->energy_charge ."\n";
						$print_string .= " Fixed Charge -                   = ". $bd->fixed_charge ."\n";
						$print_string .= " Meter Rent -                     = ". $bd->meter_rent ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Gross -                          = ". $bd->gross_charge ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Rebate Allowed -                 = ". $bd->rebate_charge ."\n";
						$print_string .= " Credit as on date -              = ". $bd->credit_adjustment ."\n";
						$print_string .= " Net Amount -                     = ". $bd->net_charge ."\n";
						$print_string .= " Due Date -                      ". $bd->bill_due_datetime ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Please go after 2 days for payment\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Net Amount -                     = ". $bd->net_charge ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " OutStanding Bills - ". $d->pend_bill ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Last Receipt -  ". $d->last_receipt ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "                * THANK YOU *\n";
						$print_string .= "---::---::---::---::---::---::---::---::---::--\n";
						$print_string .= "                 ACKNOWLEDGEMENT\n";
						$print_string .= "                -----------------\n";
						$print_string .= "   Con No : ". $d->cid ."\n";
						$print_string .= "   Name   : ". $cd->consumer_name ."\n";
						$print_string .= "   Amt    : ". $bd->net_charge ."\n";
						$print_string .= "   Due Dt : ". $bd->bill_due_datetime ."\n\n";
						$print_string .= "   Customer\n";
						$print_string .= "   Signature :-------------------------------\n";
						
						echo $print_string;
						
					}else{
						
						$print_string = "\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "             :: TSECL Billing ::\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "          * Designed and Developed by *\n";
						$print_string .= "           * ARK Informatics (P) Ltd *\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "Sub Div : ". $cd->subdiv ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "Bookno  : ". $d->bookno ."\n";
						$print_string .= "Con ID  : ". $d->cid ."\n";
						$print_string .= "". $cd->consumer_name ."\n";
						$print_string .= "". $cd->consumer_address ."\n\n";
						$print_string .= "Rd Date : ". $rdate ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "    ";
						$print_string .= "Tariff ID.         : ". $cd->tariff_id ."\n";
						$print_string .= "    ";
						$print_string .= "Meter No.          : ". $cd->meterno ."\n";
						$print_string .= "    ";
						$print_string .= "Connection         : ". $cd->phase ." Phase\n";
						$print_string .= "    ";
						$print_string .= "Multiplying Factor : ". $cd->mfactor ."\n";
						$print_string .= "    ";
						$print_string .= "Contracted Load    : ". $cd->cload ." ". $cd->load_unit ."\n";
						$print_string .= "    ";
						$print_string .= "Reading            : ". $d->postmeter_read ."\n";
						$print_string .= "    ";
						$print_string .= "PP Unit            : ". $d->ppunit ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "                   # SLIP #\n";
						
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "Bill cannot be created because of more than two\n";
						$print_string .= "months bills are not created. Please contact to\n";
						$print_string .= "Electrical Sub-Division office to collect  your\n";
						$print_string .= "Bill.\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " OutStanding Bills - ". $d->pend_bill ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= " Last Receipt -  ". $d->last_receipt ."\n";
						$print_string .= "-----------------------------------------------\n";
						$print_string .= "                * THANK YOU *\n";
						$print_string .= "---::---::---::---::---::---::---::---::---::--\n";
						$print_string .= "                 ACKNOWLEDGEMENT\n";
						$print_string .= "                -----------------\n";
						$print_string .= "   Con No : ". $d->cid ."\n";
						$print_string .= "   Name   : ". $cd->consumer_name ."\n";
						$print_string .= "   Customer\n";
						$print_string .= "   Signature :--------------------------------\n";
						
						echo $print_string;
					}
				}
			}
	
}
else{
	echo "Unauthorized user";
}
?>