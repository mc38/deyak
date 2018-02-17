<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	
	$bres = mysql_query("select * from out_bill_xml where subdivision_id='".$s."' and mydate='".strtotime($sd)."' and down='2'");
	
	if(mysql_num_rows($bres)>0){
		$j=1;
		echo '<div><b>Total no of data</b>: '.mysql_num_rows($bres).'</div>';
		echo '<hr/>';
		echo '	<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '		<tr align="center">';
		echo '			<th>SlNo</th>';
		echo '			<th>Consumer Details</th>';
		echo '			<th>Date/Billno</th>';
		echo '			<th>Reading Details</th>';
		echo '			<th>Slab Details</th>';
		echo '		</tr>';
		
		$j=1;
		
		$ppunit = array();
		
		while($bd = mysql_fetch_object($bres)){
			$link = $bd->link;
			
			$cq = mysql_query("select * from p_consumerdata where id='".$link."'");
			$cd = mysql_fetch_object($cq);
				
			$pbq = mysql_query("select * from p_billdata where link='". $link ."'");
			$pbd = mysql_fetch_object($pbq);
			
			$rq = mysql_query("select * from out_reading_xml where link='". $link ."'");
			$rd = mysql_fetch_object($rq);
			
			
			echo '		<tr>';
			
			echo '			<td valign="top" align="center" style="width:20px;"><b>'. $j .' .</b></td>';
			
			echo '			<td valign="top" align="center" style="width:150px;">';
			echo '					<table border="0" style="border-spacing:0px; text-align:left; width:150px;">';
			echo '						<tr><th>ID</th>						<td>'. $cd->cid .'</td></tr>';
			echo '						<tr><th>Name</th>					<td>'. $cd->consumer_name .'</td></tr>';
			echo '						<tr><th>Address</th>				<td>'. $cd->consumer_address .'</td></tr>';
			echo '						<tr><th>Bookno</th>					<td>'. $cd->bookno .'</td></tr>';
			echo '						<tr><th>Tariff</th>					<td>'. $cd->tariff_id .'</td></tr>';
			echo '						<tr><th>Category</th>				<td>'. $cd->category_name .'</td></tr>';
			echo '						<tr><th>Load</th>					<td>'. $cd->cload .' '. $cd->load_unit .'</td></tr>';
			echo '						<tr><th>Phase</th>					<td>'. $cd->phase .' Phase</td></tr>';
			echo '						<tr><th>M.F.</th>					<td>'. $cd->mfactor .'</td></tr>';
			echo '						<tr><th>M. no</th>					<td>'. $cd->meterno .' ( EBS )</td></tr>';
			echo '						<tr><th>M. no</th>					<td>'. $pbd->fmeterno .' ( Field )</td></tr>';
			echo '					</table>';
			echo '			</td>';
			
			
			
			
			$ashow = "-";
			if($bd->aid >0){
				$aq = mysql_query("select name,contact from agent_info where id='".$bd->aid."'");
				$ad = mysql_fetch_object($aq);
				
				$aname = json_decode(base64_decode($ad->name));
				$ashow = '<b>'.$aname[0].' '.$aname[1].'<br/>M:</b> '.$ad->contact ;
			}
			
			
			echo '			<td valign="top" align="center" style="width:100px; text-transform:capitalize;">';
			echo '				<br/><b>'.$bd->bill_no.'</b>';
			echo '				<hr/>'. $bd->bill_from_datetime .' <br/><b>to</b><br/> '. $bd->bill_to_datetime ;
			echo '				<hr/>Due Date :<br/>'. $bd->bill_due_datetime ;
			echo '				<hr/>'. $ashow;
			echo '				<hr/><button value="'. $link .'" onclick="bremove(this);"; type="button" style="margin:0px;">Remove</button>';
			echo '			</td>';
							
							
			echo '			<td valign="top" style="width:120px;">';
			echo '				<table align="center">';
			echo '					<tr align="center"><th>Previous</th>	<th>Current</th><tr/>';
			echo '					<tr align="center"><td>'.$bd->previous_reading.'</td>	<td>'.$bd->current_reading.'</td><tr/>';
			echo '					<tr align="center"><td colspan="2"><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $pbd->meterpic .'" /></td><tr/>';
			echo '					<tr><th align="left">Consumed</th>	<td align="right">'.$rd->unit_consumed.'</td><tr/>';
			echo '					<tr><th align="left">Billed</th>	<td align="right">'.$bd->billed_unit.'</td><tr/>';
			echo '				</table>';
			echo '			</td>';
			echo '			<td valign="top" style="width:220px;">';
			echo '				<table align="center">';
			
			$slab = json_decode($bd->energy_charge_breakup);
			for($i=0;$i<sizeof($slab);$i++){
				if(is_array($slab[$i])){
					$slab_data= $slab[$i];
				}
				else{
					$slab_str = str_replace(']','',str_replace('[','',$slab[$i]));
					$slab_data = explode(',',$slab_str);
				}
				echo '				<tr><td align="left"><b>'. $slab_data[0] .'</b></td>	<td align="right">'. $slab_data[1] .'</td>	<td align="right">'. $slab_data[2] .'</td>	<td align="right">'. $slab_data[3] .'</td></tr>';
			}
			echo '					<tr><td colspan="4"><hr/></td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Energy Charge</b></td>	<td align="right">'. $bd->energy_charge .'</td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Fixed Charge</b></td>	<td align="right">'. $bd->fixed_charge .'</td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Meter Rent</b></td>		<td align="right">'. $bd->meter_rent .'</td></tr>';
			echo '					<tr><td colspan="4"><hr/></td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Gross Charge</b></td>	<td align="right">'. $bd->gross_charge .'</td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Rebate</b></td>			<td align="right">'. $bd->rebate_charge .'</td></tr>';
			echo '					<tr><td colspan="4"><hr/></td></tr>';
			echo '					<tr><td align="left" colspan="3"><b>Net Charge</b></td>		<td align="right">'. $bd->net_charge .'</td></tr>';
			echo '				</table>';
			echo '			</td>';
			
			echo '		</tr>';
			
			
			
			$j++;
			
			
			
			
		}
		
		echo '</table>';
	}
	else{
		echo '<div>Empty List</div>';
	}
}
else{
	echo "Unauthorized user";
}
?>