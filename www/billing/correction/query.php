<table style="width: 100%;" border="1">
	<tr>
		<th>Consumer no</th>
		<th>Consumer Name</th>
		<th>Arrear from APDCL</th>
		<th>Arrear From Bill</th>
		<th>Arrear Status</th>
		<th>Bill Status</th>
	</tr>

<?php
include "db/command.php";

$query = mysql_query("select consumer_no,consumer_name,principle_arrear from in_data_queue");
if(mysql_num_rows($query)>0){
	while($d = mysql_fetch_object($query)){
		$mq = mysql_query("select out_principal_arrear,in_status from m_data where out_oldcid='". $d->consumer_no ."'");
		if(mysql_num_rows($mq) >0){
			$md = mysql_fetch_object($mq);

			$done = '<span style="color:green;">Done</span>';
			if($md->in_status !=""){
				$done = '<span style="color:red">Not Done</span>';
			}

			$same = '<span style="color:red;">Not Same</span>';
			if($d->principle_arrear == $md->out_principal_arrear){
				$same = '<span style="color:green;">Same</span>';
			}

			echo '
				<tr>
					<td>'. $d->consumer_no .'</td>
					<td>'. $d->consumer_name .'</td>
					<td>'. $d->principle_arrear .'</td>
					<td>'. $md->out_principal_arrear .'</td>
					<td>'. $same .'</td>
					<td>'. $done .'</td>
				</tr>
			';
		}
	}
}

?>
</table>