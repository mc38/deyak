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
$q = mysql_query("select out_oldcid,out_consumer_name,out_principal_arrear from m_data where c_import_status=1");
if(mysql_num_rows($q) >0){
	while($d = mysql_fetch_object($q)){

		$cpa = "-";
		$qq = mysql_query("select principle_arrear from in_data_queue where consumer_no='". $d->out_oldcid ."'");
		if(mysql_num_rows($qq)>0){
			$qd = mysql_fetch_object($qq);
			$cpa = $qd->principle_arrear;
		}
		
		if($cpa != $d->out_principal_arrear){
			echo '
			<tr>
				<td>'. $i .'</td>
				<td>'. $d->out_oldcid .'</td>
				<td>'. $d->out_consumer_name .'</td>
				<td>'. $d->out_principal_arrear .'</td>
				<td>'. $cpa .'</td>
			</tr>
			';
			$i++;
		}
	}
}

?>
</table>