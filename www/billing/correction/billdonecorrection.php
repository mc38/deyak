<table border="1" style="border-collapse: collapse;">
	<tr>
		<th>Slno</th>
		<th>Consumer No</th>
		<th>Consumer Name</th>
		<th>Old Arrear</th>
		<th>Correct Arrear</th>
	</tr>
<?php
include "db/command.php";
$i =1;
$q = mysql_query("select id,out_oldcid,out_consumer_name,out_adjustment,out_principal_arrear,out_arrear_surcharge,in_current_surcharge,in_current_demand from m_data where c_import_status=1");
if(mysql_num_rows($q) >0){
	//$d = mysql_fetch_object($q);
	while($d = mysql_fetch_object($q)){
		$mid = $d->id;

		$as = $d->out_arrear_surcharge;
		$ad = $d->out_adjustment;

		$cs = $d->in_current_surcharge;
		$cd = $d->in_current_demand;

		$cpa = "-";
		$qq = mysql_query("select principle_arrear from in_data_queue where consumer_no='". $d->out_oldcid ."'");
		if(mysql_num_rows($qq)>0){
			$qd = mysql_fetch_object($qq);
			$cpa = $qd->principle_arrear;
		}
		
		if($cpa != $d->out_principal_arrear){

			$total_arrear = round(($cpa + $as + $cs),2);
			$nba = round((($cd + $total_arrear)-$ad),0);

			mysql_query("update m_data set out_principal_arrear='". $cpa ."',in_total_arrear='". $total_arrear ."',in_net_bill_amount='". $nba ."' where id='". $mid ."'");

			echo '
			<tr>
				<td>'. $i .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->out_principal_arrear .'</td>
				<td>'. $cpa .' + '. $as .'+'. $cs .'</td>
			</tr>
			';
			$i++;
		}
	}
}

?>
</table>