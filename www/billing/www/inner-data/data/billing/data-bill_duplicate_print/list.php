<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../plugin/func/index.php");
require_once("../../../../../config/config.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$mydate = strtotime("01-". $data[0] ."-". $data[1]);
	$conid	= $data[2];
	
	$q = mysql_query("select * from m_data where c_mydate='". $mydate ."' and out_cid='". $conid ."' and in_status<>'' and c_import_status=1 and c_pass_status=1");
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);
		
			
		$parr = $d->out_principal_arrear + $d->out_arrear_surcharge;

		$pread = ""; $pfrp = 0; $pfstatus = "Penalty / Rebate";
		if($d->in_status == 0){
			$total_unit = $d->in_postmeter_read - $d->out_premeter_read;
			$pread = $d->in_postmeter_read;

			$pfrp = $d->in_unit_consumed - $d->in_unit_pf;  $pfstatus = "Rebate";
			if($pfrp<0){
				$pfrp = $pfrp * (-1); $pfstatus = "Penalty";
			}

		}else if(($d->in_status == 3) || ($d->in_status == 4)){
			$total_unit = $d->in_postmeter_read - $d->out_premeter_read;
			$pread = $d->in_postmeter_read ." (". $meter_status[$d->in_status] .")";

			$pfrp = $d->in_unit_consumed - $d->in_unit_pf;  $pfstatus = "Rebate";
			if($pfrp<0){
				$pfrp = $pfrp * (-1); $pfstatus = "Penalty";
			}

		}else{
			$pread = $meter_status[$d->in_status];
			$total_unit = $d->in_unit_consumed ." (Average Unit)";
		}

		$en_brkup = array();
		$en_slab = json_decode(base64_decode($d->in_energy_brkup));
		for($i=0;$i<sizeof($en_slab);$i++){
			$slabdata = $en_slab[$i];
			$en_brkup[] = "(". $slabdata[0] .") ". $slabdata[1] ." X ". $slabdata[2] ." = ". $slabdata[3];
		}
		$energy_brkup = implode("; ", $en_brkup);

		$load_kw = ""; $load_kva ="";
		$load_arr = explode(" ", $d->out_connection_load);
		if(strtoupper($load_arr[1]) == "KW"){$load_kw = $load_arr[0]; if($d->in_pf>0){$load_kva = round((($load_arr[0] * 100)/$d->in_pf),2);}else{$load_kva=$load_kw;}}
		elseif(strtoupper($load_arr[1]) == "KVA"){if($d->in_pf>0){$load_kw = round((($load_arr[0] * $d->in_pf)/100),2);}else{$load_kw = $load_kva;} $load_kva = $load_arr[0];}


		$image_data = "data:image/png;base64,". getlogo() ."";
		echo '
			
			<!DOCTYPE html>
			<html>
				<head>
					<title>APDCL Bill</title>
					<link rel="stylesheet" type="text/css" href="inner-data/data/billing/data-bill_duplicate_print/style/css/bill_style.css" />
				</head>
				<body>
				  <table>
				    <div class="col-lg-12 tab_head padding">
				      <table class="table box">
				        <tr>
				          <td class="head_one">
				          	<img src="'. $image_data .'" style="head_one" />
				          </td>
				          <td class="head_two">
				            <span>ASSAM POWER DISTRIBUTION COMPANY LIMITED</span>
				            <span>ELECTRICITY BILL CUM DISCONNECTION NOTICE</span>
				            <span>HAJO ELECTRICAL SUB-DIVISION</span>
				          </td>
				          <td class="head_three">
				            <span>'. $d->out_oldcid .'</span>
				          </td>
				        </tr>
				      </table>
				    </div>


				    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 left_box padding">
				      <table class="lft_tab">
				        <thead class="tab_two" align="center">
				          <tr>
				            <th colspan="2">
				              <span class="caps">Consumer Details</span>
				            </th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr class="bord">
				            <td colspan="2" class="pad">
				              <span class="caps">'. $d->out_consumer_name .'</span>
				              <span>'. $d->out_consumer_address.'</span>
				            </td>
				          </tr>
				          <tr align="center" class="caps_sm bord">
				            <td>Connected Load (in kw) '. $load_kw .'</td>
				            <td>Connected Demand (in kva) '. $load_kva .'</td>
				          </tr>
				          <tr class="bord color">
				            <td colspan="2"  class="det caps_sm">
				              <table>
				                <tr>
				                  <td>Installation no.</td>
				                  <td>--</td>
				                </tr>
				                <tr>
				                  <td>Consumer Account No.</td>
				                  <td>022000001791</td>
				                </tr>
				                <tr>
				                  <td>DTR</td>
				                  <td style="text-align: right;">030</td>
				                </tr>
				                <tr>
				                  <td>Category</td>
				                  <td>DOM A</td>
				                </tr>
				                <tr>
				                  <td>Old Consumer No.</td>
				                  <td>33/DL/368</td>
				                </tr>
				              </table>
				              <div class="barcode"></div>
				            </td>
				          </tr>
				        </tbody>
				      </table>

				    </div>


				    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 left_box padding">
				      <table class="lft_tab">
				        <thead class="tab_two" align="center">
				          <tr>
				            <th colspan="2">
				              <span class="caps">Bill Details</span>
				            </th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr class="bord color">
				            <td colspan="2"  class="det caps_sm">
				              <table>
				                <tr>
				                  <td>Bill no</td>
				                  <td>1763086</td>
				                </tr>
				                <tr>
				                  <td>Bill Period</td>
				                  <td>01/07/2017 to 01/08/2017</td>
				                </tr>
				                <tr>
				                  <td>Bill Date</td>
				                  <td>16/08/2017</td>
				                </tr>
				                <tr>
				                  <td>No of Days</td>
				                  <td>31</td>
				                </tr>
				                <tr>
				                  <td>Due Date</td>
				                  <td>31/08/2017</td>
				                </tr>
				              </table>
				            </td>
				          </tr>
				          <tr class="bord color">
				            <td colspan="2" class="det">
				              <table align="center" class="med">
				                <tr>
				                  <td>Gross current</td>
				                  <td>Gross Arrear</td>
				                  <td>Gross Adjustment</td>
				                  <td>Net Amount</td>
				                </tr>
				                <tr>
				                  <td></td>
				                  <td></td>
				                  <td></td>
				                  <td>3257</td>
				                </tr>
				                <tr>
				                  <td>Amount in words</td>
				                  <td colspan="3">Three Thousand two hundred and fifty seven only</td>
				                </tr>
				              </table>
				            </td>
				            
				          </tr>
				          <tr class="bord color">
				            <td colspan="2" class="det">
				              <table>
				                <tr>
				                  <td>
				                    <span class="let">Under section 56(1) of IE Act 2003 power supply to your premises will be disconnected as per above clause if you fail to pay the billed amount &#8377; 3257 within 31/08/2017. Ignore if already paid.</span>
				                  </td>
				                </tr>
				              </table>
				            </td>
				          </tr>
				        </tbody>
				      </table>

				    </div>

				    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding">
				      <table class="new_tab">
				        <thead class="tab_two">
				          <tr>
				            <th>
				              <span class="caps">Meter Reading Details</span>
				            </th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr class="bord color">
				            <td class="det">
				              <table align="center" class="med new_tab_child">
				                <tr>
				                  <td>Reading Type</td>
				                  <td>Meter No.</td>
				                  <td>MF</td>
				                  <td>Previous</td>
				                  <td>Present</td>
				                  <td>Difference</td>
				                </tr>
				                <tr>
				                  <td>KWH(N)</td>
				                  <td>AS-008171</td>
				                  <td>1</td>
				                  <td>1130</td>
				                  <td>1130</td>
				                  <td>0</td>
				                </tr>
				              </table>

				              <table align="center" class="med new_tab_child">
				                <tr>
				                  <td>RD (in KVA)</td>
				                  <td></td>
				                  <td>MD (in KVA)</td>
				                  <td></td>
				                  <td>BD (in KVA)</td>
				                  <td></td>
				                  <td>AVG PF</td>
				                  <td></td>
				                </tr>
				              </table>

				              <table align="center" class="med new_tab_child">
				                <tr>
				                  <td>Units Cons</td>
				                  <td>PF Penalty/Rebate</td>
				                  <td>Billable Units</td>
				                </tr>
				                <tr>
				                  <td>30</td>
				                  <td>0/0</td>
				                  <td>30</td>
				                </tr>
				              </table>

				              <table align="center" class="med new_tab_child">
				                <tr>
				                  <td>Power in Hrs</td>
				                  <td></td>
				                  <td>Availibility</td>
				                  <td></td>
				                </tr>
				              </table>

				            </td>
				            
				          </tr>
				        </tbody>
				      </table>
				    </div>


				    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 padding">
				    	<div style="height: 0px;"><div style="height: 450px;" class="watermark">
				    		<img src="'. $image_data .'" class="watermark" />
				    	</div></div>
				       	<table class="new_tab len">
				        <thead class="tab_two" align="center">
				          <tr>
				            <th>
				              <span class="caps">Charge Breakup</span>
				            </th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr class="bord color">
				            <td class="det">
				              <table  class="new_tab_child">
				                <tr>
				                  <td rowspan="6" class="top">1.</td>
				                  <td colspan="2">Energy Charge</td>
				                </tr>
				                <tr>
				                  <td>(a) First 30 Units @ &#8377; 5.65/unit</td>
				                  <td class="med_rt">169.5</td>
				                </tr>
				                <tr>
				                  <td>(b) Next 0 Units @ &#8377; 0/unit</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>(c) Next 0 Units @ &#8377; 0/unit</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>(d) Balance 0 Units @ &#8377; 0/unit</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>(e)Balance less Govt. subsidy 30 Units @ &#8377; 1.01/unit</td>
				                  <td class="med_rt">30.3</td>
				                </tr>
				                <tr>
				                  <td>2.</td>
				                  <td>Total Energy Charge</td>
				                  <td class="med_rt">139.2</td>
				                </tr>
				                <tr>
				                  <td>3.</td>
				                  <td>Fixed charges for 1/kva/kw, @ &#8377; 30/kw/kva</td>
				                  <td class="med_rt">30.55</td>
				                </tr>
				                <tr>
				                  <td>4.</td>
				                  <td>Electricity Duty</td>
				                  <td class="med_rt">3</td>
				                </tr>
				                <tr>
				                  <td>5.</td>
				                  <td>Meter Rent</td>
				                  <td class="med_rt">20.37</td>
				                </tr>
				                <tr>
				                  <td>6.</td>
				                  <td>Overdrawal penalty</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>7.</td>
				                  <td>Adjustment of past bill/load security</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>8.</td>
				                  <td>FPPPA @ &#8377; 0.00/unit X 30</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>9.</td>
				                  <td>Charges for dishonored cheque/bank charges/other charges</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td>10.</td>
				                  <td>Arrears (a) Principal 2951 (b) Surcharges 69</td>
				                  <td class="med_rt">3020</td>
				                </tr>
				                <tr>
				                  <td>11.</td>
				                  <td>Current surcharges on arrear principal</td>
				                  <td class="med_rt">44</td>
				                </tr>
				                <tr>
				                  <td>12.</td>
				                  <td>Current Installment</td>
				                  <td class="med_rt">0</td>
				                </tr>
				                <tr>
				                  <td colspan="2">Amount payable on or before due date</td>
				                  <td class="med_rt"><b>3257</b></td>
				                </tr>
				              </table>
				              <table  class="new_tab_child">
				                <tr>
				                  <td>Other arrears (a) Principal</td>
				                  <td>0</td>
				                  <td>(b) surcharge</td>
				                  <td> 0</td>
				                </tr>
				                <tr>
				                  <td>Installment Pending Nos.</td>
				                  <td>0</td>
				                  <td>Amount</td>
				                  <td>0</td>
				                </tr>
				              </table>
				            </td>
				        </tbody>
				      </table>
				      
				    </div>


				    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 padding">
				       <table class="new_tab len">
				        <thead class="tab_two" align="center">
				          <tr>
				            <th>
				              <span class="caps">Comparative Consumption History</span>
				            </th>
				          </tr>
				        </thead>
				        <tbody>
				          <tr class="bord color">
				            <td class="det">
				            </td>
				          </tr>
				        </tbody>
				      </table>
				      
				    </div>


				    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding">
				       <table class="new_tab">
				          <tr class="bord">
				            <td class="wid">Appeal<br><br> All Our esteemed consumers are requested to provide their Mobile Numbers during bill payment at the counter to help us for broadcasting power outage / Shut down (scheduled / unscheduled) information to you through SMS under Urja Mitra scheme.</td>
				            <td class="wid_right"><b>Centralized Customer Care Number<br>1912<br>0361-231 3069<br>3061-231 3082<br>+91 96780 84219<br>Pay bills online www.apdcl.org<b></td>
				          </tr>
				      </table>
				    </div>


				  </div>
				</body>
			</html>
			<hr/>
			';
	}
	else{
		echo '<div align="center">Bill data not found</div>';
	}
}
else{
	echo '<div align="center">Unauthorized user</div>';
}


function getlogo(){
	$logo = '
	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
	';
	return $logo;
}

?>