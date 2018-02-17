<table border="1" style="border-collapse: collapse;">
	<tr>
		<th>Slno</th>
		<th>Consumer No</th>
		<th>Consumer Name</th>
		<th>CD</th>
		<th>PA</th>
		<th>AS</th>
		<th>CS</th>
		<th>TA</th>
		<th>NBA</th>
		<th>Status</th>
		<th>N_TA</th>
		<th>N_NBA</th>
	</tr>
<?php
include "db/command.php";
$i =1;
$q = mysql_query("select id,out_oldcid,out_consumer_name,in_current_demand,out_principal_arrear,out_arrear_surcharge,in_current_surcharge,out_adjustment,in_total_arrear,in_net_bill_amount from m_data where c_import_status=1");
if(mysql_num_rows($q) >0){
	while($d = mysql_fetch_object($q)){
		$mid = $d->id;

		$cpa = "-";
		$qq = mysql_query("select principle_arrear from in_data_queue where consumer_no='". $d->out_oldcid ."'");
		if(mysql_num_rows($qq)>0){
			$qd = mysql_fetch_object($qq);
			$cpa = $qd->principle_arrear;
		}

		$cd = $d->in_current_demand;
		$pa = $d->out_principal_arrear;
		$as = $d->out_arrear_surcharge;
		$cs = $d->in_current_surcharge;
		$ad = $d->out_adjustment;
		$ta = $d->in_total_arrear;
		$nba= $d->in_net_bill_amount;

		$ta_c = round(($pa + $as + $cs),2);
		$nba_c = round((($ta_c + $cd) - $ad),0);
		
		$ta_status = "Same";$nba_status = "Same";
		if($ta != $ta_c){$ta_status ="Not same";}
		if($nba != $nba_c){$nba_status ="Not same";}
		
		if($ta != $ta_c && $nba != $nba_c){

			echo '
			<tr>
				<td>'. $i .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $cd .'</td>
				<td>'. $pa .'</td>
				<td>'. $as .'</td>
				<td>'. $cs .'</td>
				<td>'. $ta .'</td>
				<td>'. $nba .'</td>
				<td>TA -> '. $ta_status .'<br/>NBA -> '. $nba_status .'</td>
				<td>'. $ta_c .'</td>
				<td>'. $nba_c .'</td>
			</tr>
			';
			
			mysql_query("update m_data set in_total_arrear='". $ta_c ."',in_net_bill_amount='". $nba_c ."' where id='". $mid ."'");

			$i++;
		}
	}
}

?>
</table>