<?php
session_start();
include "/billing/www/db/command.php";
include "/billing/config/config.php";
include "/billing/www/plugin/func/index.php";
date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);
$category = array(); $cateid = array();
$cate_q = mysql_query("select id,name from settings_consumer_cate");
while($cate_d = mysql_fetch_object($cate_q)){
	$category[] = $cate_d;
	$cateid[$cate_d->id] = $cate_d->name;
}
$msg = "";
$ok = false;
$m = "";
$y = "";
$c = "";
if(
	isset($_POST['m']) && ($_POST['m']!="")
	&&
	isset($_POST['y']) && ($_POST['y']!="")
	&&
	isset($_POST['c']) && ($_POST['c']!="")
){
	$m = $_POST['m'];
	$y = $_POST['y'];
	$c = $_POST['c'];
	$nm = $m; if($m<10){$nm = "0". $m;}
	$mydate = strtotime($y ."-". $nm ."-01");
	$where = " and c_pass_status=1 and c_import_status=1";
	$cwhere = "";
	if($c !="" ){
		$cwhere = " and out_oldcid='". $c ."'";
	}
	$q = mysql_query("select * from m_data where c_mydate=". $mydate ." and in_status<>''". $cwhere . $where ."");
	if(mysql_num_rows($q) >0){
		$ok = true;
	}else{
		$msg = "No data is found... Or bill is under verification process.... Contact Sub-division";
	}
}
echo '
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css" />
<body>
<div class="body"  align="center">
<div class="heading"><div class="logo_container"><i class="logo" style="margin-top: 18px;"></i><span class="logo_content">Deyak</span></div></div>
<div>
<span class="head_text">Duplicate Bill Print</span>
</div>
<br/><br/>
<form method="post" action="">
<div class="form_container">
<div class="ip_box">
<span>Billing period :</span>
<select class="period" name="m">
<option value="">Select Month</option>';
$month=array("January","Fabruary","March","April","May","June","July","August","September","October","November","December");
for($i=0;$i<sizeof($month);$i++){
	$j = $i+1;
	$mselected = "";
	if($m == $j){$mselected='selected="selected"';}
	echo '<option value="'. $j .'" '. $mselected .'>'.$month[$i].'</option>';
}
echo '
</select>
<select class="period"  name="y">
<option value="">Select Year</option>';
$year = date('Y',$datetime)+1;
for($i=0;$i<3;$i++){
$yselected = "";
if($y == $year){$yselected='selected="selected"';}
echo '<option value="'.$year.'" '. $yselected .'>'.$year.'</option>';
$year--;
}
echo '
</select>
</div>
<div class="ip_box">
<span>Consumer No - (12 digit, if consumer no length is 11 digit then provide 0 before the number) :</span>
<input name="c" type="text" autocomplete="off" spellcheck="false" value="'. $c .'" />
</div>
<div class="ip_box">
<button type="submit">View</button>
<span style="color: brown;">'. $msg .'</span>
</div>
</div>
</form>';

if($ok){
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


	echo '
	<script>
	function print_report(){
		var d = document.getElementById("data_print").innerHTML;
		if(d !=""){
			var w = window.open();
			w.document.write(d);
			setTimeout(function(){
				w.print();
				w.close();
			},1000);

		}
	}
	</script>
	<button type="button" onclick="print_report();">Print Bill</button>
	<hr/>
	<div id="data_print">
	<html>
	<link rel="stylesheet" type="text/css" href="bill_style.css" />
	<body>
	<div id="data_list">
						<div class="heading" align="center">
							<table><tr>
								<td align="center"><div style="padding-left:70px;"><span>ASSAM POWER DISTRIBUTION COMPANY Ltd.(LAR)</br>Bill for Electricity Supply, APDCL</span></div></td>
								<td><img style="height:70px; width:auto;" src="data:image/jpeg;base64,'. getlogo() .'" /></td>
							</tr></table>
						</div>
						<div class="details_contain" align="center" style="padding-bottom:70px;">
							<table>
								<tr>
								<td>Name of Sub-Division:<span class="data">'. $d->out_subdivision .'</span></td>
								<td></td>
								<td>Field Bill No.:<span class="data">'. $d->in_billno .'</span></td>
							</tr>
							<tr>
								<td>APDCL Bill No.:<span class="data">'. $d->in_apdcl_billno .'</span></td>
								<td>Date of Bill:<span class="data">'. date('d-m-Y',$d->in_reading_date) .'</span></td>
								<td>Due Date:<span class="data">'. date('d-m-Y',$d->in_due_date) .'</span></td>
							</tr>
								<tr>
									<td>Consumer No.:<span class="data">'. $d->out_oldcid .'</span></td>
									<td>Period of Bill From:<span class="data">'. date('d-m-Y',$d->out_premeter_read_date) .'</span></td>
									<td>To:<span class="data">'. date('d-m-Y',$d->in_reading_date) .'</span></td>
								</tr>
								<tr>
									<td colspan="3">DEYAK ID.:<span class="data">'. $d->out_cid .'</span></td>
								</tr>
							</table>
							<table class="ah">
								<tr class="dh">
									<td class="mno" colspan="2">Name of Consumer:<span class="data">'. $d->out_consumer_name .'</span></td>
									<td class="mno" colspan="2">No of Days:<span class="data">'. $d->in_consumption_day .'</span></td>
								</tr>
								<tr>
									<td valign="top" class="ph" colspan="2">Address of Consumer:<span class="data">'. $d->out_consumer_address .'</span></td>
									<td class="ph" colspan="2">
										<span>Meter No:</span><span class="data">'. $d->out_meter_no .'</span></br>
										<span>Connected Load in Kw/KVA:</span><span class="data">'. $d->out_connection_load .'</span></br>
										<span>Connected Demand in Kw/KVA:</span><span class="data">'. $d->out_connection_load .'</span></td>
								</tr>
								<tr>
									<td colspan="4" class="ggg">DTR No:<span class="data">'. $d->out_dtrno .'</span> | Category:<span class="data">'. $d->out_consumer_category .'</span> | Overall MF:<span class="data">'. $d->out_mfactor .'</span> | MD as per Meter Reading..........................KVA</td>
								</tr>
								<tr>
									<td class="ph">Meter Reading</td>
									<td class="ph" align="center">Present</td>
									<td class="ph" align="center">Previous</td>
									<td class="ph" align="center">Difference in Reading</td>
								</tr>
								<tr>
									<td class="ph">Total Kwh (U)</td>
									<td class="ph"><span class="data">'. $pread .'</span></td>
									<td class="ph"><span class="data">'. $d->out_premeter_read .'</span></td>
									<td class="ph"><span class="data">'. $total_unit .'</span></td>
								</tr>
								<tr>
									<td class="ph">Total KVAh (U)</td>
									<td class="ph"><span class="data">0.00</span></td>
									<td class="ph"><span class="data">0.00</span></td>
									<td class="ph"><span class="data">0.00</span></td>
								</tr>
								<tr>
									<td>Bill MD Reading(KVA):<span class="data">0.00</span></td>
									<td colspan="2" align="center">Bill MD(KVA):<span class="data">'. $d->out_connection_load .'</span></td>
									<td class="asd">AVG PF:<span class="data">'. number_format((float) ($d->in_pf /100),2) .'</span></td>
								</tr>
							</table>
							<table class="lmn">
								<tr>
									<td align="center">Units Consumed</td>
									<td align="center">3% LT Metering</td>
									<td align="center">PF '. $pfstatus .'</td>
									<td align="center">DTR Penalty</td>
									<td align="center">Emp Rebate</td>
									<td align="center">HT Rebate</td>
									<td align="center">Units Billed</td>
								</tr>
								<tr>
									<td><span class="data">'. $d->in_unit_consumed .'</span></td>
									<td><span class="data">0</span></td>
									<td><span class="data">'. $pfrp .'</span></td>
									<td><span class="data">0</span></td>
									<td><span class="data">0</span></td>
									<td><span class="data">0</span></td>
									<td><span class="data">'. $d->in_unit_billed .'</span></td>
								</tr>
							</table>
							<div class="abc"><span>Power On Hrs:</span></div>
							<div class="def"><span>Availability(%):</span></div>
							<table class="second">
								<tr class="sec_tab">
									<td class="hh">Sl</td>
									<td class="jk" align="center">Billing Details</td>
									<td class="pk" align="center">In</td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">A</td>
									<td class="jk">
										Energy Charge ->
										<span style="color:#000;">'. $energy_brkup .'</span>
									</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_energy_amount,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">B</td>
									<td class="jk">Subsidy</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_subsidy,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">C</td>
									<td class="jk">Total Energy Charge</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_total_energy_charge,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">D</td>
									<td class="jk">Electricity Duty</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_electricity_duty,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">E</td>
									<td class="jk">Fixed Charge</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_fixed_charge,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">F</td>
									<td class="jk">FPPPA Charge</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_fppa_charge,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">G</td>
									<td class="jk">Meter Rent</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_meter_rent,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">H</td>
									<td class="jk">Adjustment of past Billing/Load Security</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->out_adjustment,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">I</td>
									<td class="jk">Arrear:<span class="data">'. number_format((float)$d->out_principal_arrear,2) .'</span> | (ii)Surcharge:<span class="data">'. number_format((float)$d->out_arrear_surcharge,2) .'</span></td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$parr,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">J</td>
									<td class="jk">Current Surcharge on Arrear Principal</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_current_surcharge,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">K</td>
									<td class="jk">Amount Payable on or before Due Date:</td>
									<td class="pk" align="right"><span class="data">'. number_format((float)$d->in_net_bill_amount,2) .'</span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh">L</td>
									<td class="jk"></td>
									<td class="pk" align="right"><span class="data"></span></td>
								</tr>
								<tr class="sec_tab">
									<td class="hh_h" colspan="3">Amount:<span class="data" style="text-transform:capitalize;">'. rupee_2_str($d->in_net_bill_amount) .'</span></td>

								</tr>
							</table>
							<table class="vv">
								<tr class="thr_tab" align="center">
									<td class="ll">Prepared By:</td>
									<td class="mm">Checked By:</td>
									<td class="nn"></td>
								</tr>
								<tr class="thr_tab" align="center">
									<td class="ll">Bill Clerk/JE/AE</td>
									<td class="mm">Dy. AO/AO/AM</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E & O.E.</td>
									<td class="nn">AM/AEE/AE/SMR</br>Signature with Seal</td>
								</tr>
							</table>
						</div>
		</div>
		</div>
		</body>
		</html>
		';

}}


	echo '
		</body>
		</html>
	';


	function getlogo(){
	$logo = '
			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AAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhCSU0EAAAAAAAAAgAAOEJJTQQCAAAAAAACAAA4QklNBDAAAAAAAAEBADhCSU0ELQAAAAAABgABAAAAAzhCSU0ECAAAAAAAEAAAAAEAAAJAAAACQAAAAAA4QklNBB4AAAAAAAQAAAAAOEJJTQQaAAAAAAM7AAAABgAAAAAAAAAAAAAAWgAAAFoAAAADADEAMAAwAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAABaAAAAWgAAAAAAAAAAAAAAAAAAAAABAAAAAAAAAAAAAAAAAAAAAAAAABAAAAABAAAAAAAAbnVsbAAAAAIAAAAGYm91bmRzT2JqYwAAAAEAAAAAAABSY3QxAAAABAAAAABUb3AgbG9uZwAAAAAAAAAATGVmdGxvbmcAAAAAAAAAAEJ0b21sb25nAAAAWgAAAABSZ2h0bG9uZwAAAFoAAAAGc2xpY2VzVmxMcwAAAAFPYmpjAAAAAQAAAAAABXNsaWNlAAAAEgAAAAdzbGljZUlEbG9uZwAAAAAAAAAHZ3JvdXBJRGxvbmcAAAAAAAAABm9yaWdpbmVudW0AAAAMRVNsaWNlT3JpZ2luAAAADWF1dG9HZW5lcmF0ZWQAAAAAVHlwZWVudW0AAAAKRVNsaWNlVHlwZQAAAABJbWcgAAAABmJvdW5kc09iamMAAAABAAAAAAAAUmN0MQAAAAQAAAAAVG9wIGxvbmcAAAAAAAAAAExlZnRsb25nAAAAAAAAAABCdG9tbG9uZwAAAFoAAAAAUmdodGxvbmcAAABaAAAAA3VybFRFWFQAAAABAAAAAAAAbnVsbFRFWFQAAAABAAAAAAAATXNnZVRFWFQAAAABAAAAAAAGYWx0VGFnVEVYVAAAAAEAAAAAAA5jZWxsVGV4dElzSFRNTGJvb2wBAAAACGNlbGxUZXh0VEVYVAAAAAEAAAAAAAlob3J6QWxpZ25lbnVtAAAAD0VTbGljZUhvcnpBbGlnbgAAAAdkZWZhdWx0AAAACXZlcnRBbGln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NjYxLCAyMDEyLzAyLzA2LTE0OjU2OjI3ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M2IChXaW5kb3dzKSIgeG1wOkNyZWF0ZURhdGU9IjIwMTctMDgtMDRUMTM6Mzk6MTMrMDU6MzAiIHhtcDpNb2RpZnlEYXRlPSIyMDE3LTA4LTA0VDEzOjUwOjQwKzA1OjMwIiB4bXA6TWV0YWRhdGFEYXRlPSIyMDE3LTA4LTA0VDEzOjUwOjQwKzA1OjMwIiBkYzpmb3JtYXQ9ImltYWdlL2pwZWciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo5MTE1MzZDREVENzhFNzExQUYwNDg5NTQwOTgwMjdCMCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo5MDE1MzZDREVENzhFNzExQUYwNDg5NTQwOTgwMjdCMCIgeG1wTU06T3JpZ2luYWxEb2N1bWVudElEPSJ4bXAuZGlkOjkwMTUzNkNERUQ3OEU3MTFBRjA0ODk1NDA5ODAyN0IwIj4gPHhtcE1NOkhpc3Rvcnk+IDxyZGY6U2VxPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0iY3JlYXRlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo5MDE1MzZDREVENzhFNzExQUYwNDg5NTQwOTgwMjdCMCIgc3RFdnQ6d2hlbj0iMjAxNy0wOC0wNFQxMzozOToxMyswNTozMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNvbnZlcnRlZCIgc3RFdnQ6cGFyYW1ldGVycz0iZnJvbSBpbWFnZS9wbmcgdG8gaW1hZ2UvanBlZyIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6OTExNTM2Q0RFRDc4RTcxMUFGMDQ4OTU0MDk4MDI3QjAiIHN0RXZ0OndoZW49IjIwMTctMDgtMDRUMTM6NTA6NDArMDU6MzAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICA8P3hwYWNrZXQgZW5kPSJ3Ij8+/+IMWElDQ19QUk9GSUxFAAEBAAAMSExpbm8CEAAAbW50clJHQiBYWVogB84AAgAJAAYAMQAAYWNzcE1TRlQAAAAASUVDIHNSR0IAAAAAAAAAAAAAAAEAAPbWAAEAAAAA0y1IUCAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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
	';
	return $logo;
	}
?>
