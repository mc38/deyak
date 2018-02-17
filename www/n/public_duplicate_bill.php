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
?>
