<?php
require_once("../../../plugin/func/rcrypt.php");
$r = new rcrypt();
require_once("filter/type.php");

//////////////////////////////////////////////////////////////////////////////////////////////

echo '<div align="center" style="border-bottom:1px solid #000;"><h3>Android DB import report</h3></div>';
echo '<b>Report Date Time : </b>' .date('d-m-Y h:i:s a',$datetime).'<hr/>';
if(mysql_num_rows($caq) >0){

	$name = $cad->name;
	
	echo 'Agent Name : <b>'. $name .'</b>';
	echo '<hr/>';
	echo 'Total no of data = '.$total_data."<br/><br/>";
	echo '<hr/>';
	echo '<h3>Bill Data check please</h3>';
	echo '<table border="" style="border:1px solid #000; border-spacing:1px;">';
	echo '	<tr>
				<th>Slno</th>
				<th>Consumer no</th>
				<th>Consumer Name</th>
				<th>Billing Unit</th>
				<th>Net Bill Amount</th>
			</tr>';

	$key = $r->getrkey($cad->imei);

	$j=1;
	for($i=0;$i<sizeof($ob_d);$i++){
				
		echo '<tr>
				<td>'. $j .'</td>	
				<td>'. $r->rdecode($key, $ob_d[$i]->oldcid) .'</td>		
				<td>'. $r->rdecode($key, $ob_d[$i]->consumer_name) .'</td>	
				<td>'. $r->rdecode($key, $ob_d[$i]->n_unit_billed) .'</td>
				<td>'. $r->rdecode($key, $ob_d[$i]->n_net_bill_amount) .'</td>
			</tr>';
		$j++;
			
	}
	echo '</table>';
}
else{
	echo '<div style="color:red;">Agent is blocked</div>';
}
?>