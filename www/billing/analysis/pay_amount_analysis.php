<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "www/db/command.php";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

$mydate = array();
$mydate_list = array();
$start_date = strtotime("2017-08-01");
$mydate[] = $start_date; 
$mydate_list[$start_date] = array(0,0);
$mydate_con[$start_date] = 0;
for($i=0; $i<4; $i++){
	$myd = strtotime(date("Y-m-01",strtotime("+35day",$mydate[$i])));
	$mydate[] = $myd;
	$mydate_list[$myd] = array(0,0);
	$mydate_con[$myd] = 0;
}

$q = mysql_query("select c_mydate,c_import_status,out_principal_arrear,out_arrear_surcharge,in_current_demand,in_net_bill_amount from m_data where c_subdiv_id='22'");

while($d = mysql_fetch_object($q)){
	$a1 = $d->out_principal_arrear + $d->out_arrear_surcharge;
	//$a1 = $d->out_principal_arrear;

	$a2 = $d->in_net_bill_amount;
	//$a2 = $d->in_current_demand;

	
	if($d->c_import_status==1){
		$mydate_con[$d->c_mydate] ++;
		$mydate_list[$d->c_mydate][0] += $a1;
		$mydate_list[$d->c_mydate][1] += $a2;
	}
}

echo '
<table border="1" style="border-collapse:collapse;">
	<tr>
		<th>Month, Year</th>
		<th>Need to Collect</th>
		<th>Remaining</th>
		<th>Paid</th>
	</tr>
';

for($i=0;$i<sizeof($mydate)-1;$i++){
	$j = $i+1;
	$nc = $mydate_list[$mydate[$i]][1];
	$rem = ($mydate_list[$mydate[$j]][0] * $mydate_con[$mydate[$i]])/$mydate_con[$mydate[$j]];
	$pay_amount = $nc - $rem;
	//echo $pay_amount ."<br/>";

	echo '
		<tr>
			<td>'. date('F, Y', (int)$mydate[$i]) .'</td>
			<td>Rs. '. number_format((float)$nc,2,".",",") .'</td>
			<td>Rs. '. number_format((float)$rem,2,".",",") .'</td>
			<td>Rs. '. number_format((float)$pay_amount,2,".",",") .'</td>
		</tr>
	';

}
echo '</table>';

?>