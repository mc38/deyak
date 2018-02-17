<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "www/db/command.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$category = array(); $cateid = array();
$cate_q = mysql_query("select id,name from settings_consumer_cate");
while($cate_d = mysql_fetch_object($cate_q)){
	$category[] = $cate_d;
	$cateid[$cate_d->id] = $cate_d->name;
}

echo '
<form method="post" action="">
<label>Select any date of Month</label><input name="date" type="date"><br/>
<label>Select Category</label>
<select name="h" style="width:100%;">
	<option value="">Select Category</option>
';
	for($i=0;$i<sizeof($category);$i++){
		$hselected = "";
		echo '<option value="'. $category[$i]->id .'">'. $category[$i]->name .'</option>';
	}
echo '
</select>

<br/>
<button>Search</button>

</form>
';

if(
	!empty($_POST['date'])
){
$h = $_POST['h'];
$hwhere = "";
if($h !="" && isset($cateid[$h])){
	$hwhere = " and out_consumer_category='". $cateid[$h] ."'";
}	
$mydate = strtotime(date('Y-m-01',strtotime($_POST['date'])));

$q = mysql_query("select c_mydate,out_cid,out_oldcid,out_consumer_name,out_consumer_category,out_dtrno,out_slab,in_consumption_day,in_unit_billed,in_subsidy,in_energy_brkup from m_data where c_subdiv_id='22' and c_import_status=1 and c_pass_status=1 and c_mydate='". $mydate ."'". $hwhere);

echo '
<h2>Subdivisiion - Hajo(22)</h2>
<h2>Month Year - '. date('F, Y',$mydate) .'</h2>
';

if($hwhere !=""){
	echo '
	<h2>Category - '. $cateid[$h] .'</h2>
	';
}

echo '
<table border="1" style="border-collapse:collapse;">
	<tr>
		<th>Slno</th>
		<th>Deyak ID</th>
		<th>DTR no</th>
		<th>Consumer no</th>
		<th>Consumer Category</th>
		<th>Consumer Name</th>
		<th>Consuption Day</th>
		<th>Billed unit</th>
		<th>Subsidy unit</th>
		<th>Subsidy rate</th>
		<th>Calculation</th>
		<th>Total Subsidy</th>
	</tr>
';

$j=1;
$total_subsidy = 0;
while($d = mysql_fetch_object($q)){
	$slab_brk = json_decode(base64_decode($d->out_slab));
	$slab_unit_brk = json_decode(base64_decode($slab_brk[0]));
	$subsity_slab = array(); $subsity_thres = round((($slab_unit_brk[0]/30)*$d->in_consumption_day),0);
	if($subsity_thres>=$d->in_unit_billed){
		$subsidy_slab = json_decode(base64_decode($slab_unit_brk[1]));
	}else{
		$subsidy_slab = json_decode(base64_decode($slab_unit_brk[2]));
	}
	$subsidy_rate  = 0; $fcharge_rate = 0;
	if(sizeof($subsidy_slab)>0){
		$subsidy_rate = $subsidy_slab[0][4];
		$fcharge_rate = $subsidy_slab[0][3];
	}

	$subsidy_unit = 0;
	$en_slab = json_decode(base64_decode($d->in_energy_brkup));
	$slabdata = $en_slab[0];

	if($subsidy_unit==0){
		$subsidy_unit = $slabdata[1];
	}

	$result =  $subsidy_unit * $subsidy_rate;
	$result = round($result, 2);
	$calculation = $subsidy_unit ." X ". number_format((float)$subsidy_rate,2,".",",") ." = ". number_format((float)$result,2,".",",");

	$color = "";
	if((float)$result != (float)$d->in_subsidy){
		$color = 'style="background:red;"';
	}

	echo '
		<tr>
			<td align="center">'. $j .'</td>
			<td align="center">'. $d->out_cid .'</td>
			<td align="center">'. $d->out_dtrno .'</td>
			<td align="center">'. $d->out_oldcid .'</td>
			<td align="center">'. $d->out_consumer_category .'</td>
			<td>'. $d->out_consumer_name .'</td>
			<td align="center">'. $d->in_consumption_day .'</td>
			<td align="center">'. $d->in_unit_billed .'</td>
			<td align="center">'. $subsidy_unit .'</td>
			<td align="right">Rs '. number_format((float)$subsidy_rate,2,".",",") .'</td>
			<td align="right">'. $calculation .'</td>
			<td '. $color .' align="right">Rs. '. number_format((float)$d->in_subsidy,2,".",",") .'</td>
		</tr>
	';

	$total_subsidy += $d->in_subsidy;
	$j++;

}
echo '
</table>
<div>
<h2>Total Subsidy - Rs '. number_format((float)$total_subsidy,2,".",",") .'</h2>
</div>
';
}
?>