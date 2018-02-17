<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$s = json_decode(base64_decode($_GET['s']));
		
		$sid = $s[0];
		$cid = $s[1];
		
		$mydate = strtotime(date('1-m-Y',$datetime));
		
		$cq = mysql_query("select * from p_consumerdata where cid like '%".$cid."' and subdiv_id='".$sid."' and mydate='". $mydate ."'");
		if(mysql_num_rows($cq) ==1){
			$cd = mysql_fetch_object($cq);
			
			echo '';
			echo '	<h3>Consumer Details</h3><hr/>';
			echo '	<table border="1" style="border:1px solid #000; border-spacing:0px;">';
			echo '		<tr><th>ID</th>						<td>'. $cd->cid .'</td></tr>';
			echo '		<tr><th>Name</th>					<td>'. $cd->consumer_name .'</td></tr>';
			echo '		<tr><th>Address</th>				<td>'. $cd->consumer_address .'</td></tr>';
			echo '		<tr><th>Bookno</th>					<td>'. $cd->bookno .'</td></tr>';
			echo '		<tr><th>Tariff</th>					<td>'. $cd->tariff_id .'</td></tr>';
			echo '		<tr><th>Category</th>				<td>'. $cd->category_name .'</td></tr>';
			echo '		<tr><th>Load</th>					<td>'. $cd->cload .' '. $cd->load_unit .'</td></tr>';
			echo '		<tr><th>Phase</th>					<td>'. $cd->phase .' Phase</td></tr>';
			echo '		<tr><th>Multiplying Factor</th>		<td>'. $cd->mfactor .'</td></tr>';
			echo '		<tr><th>Meter no of EBS</th>		<td>'. $cd->meterno .'</td></tr>';
			echo '	</table>';
			echo '';
			echo '<hr/>';
			
			echo '';
			echo '	<h3>Bill Details</h3><hr/>';
			echo '	<table border="1" style="border:1px solid #000; border-spacing:0px;">';
			echo '		<tr align="center">';
			echo '			<th>Date/Billno</th>';
			echo '			<th>Reading Details</th>';
			echo '			<th>Slab Details</th>';
			echo '			<th>Agent Details</th>';
			echo '		</tr>';
			
			$bq = mysql_query("select * from out_bill_xml where consumer_id='". $cd->cid ."' order by id desc");
			if(mysql_num_rows($bq) >0){
				while($bd = mysql_fetch_object($bq)){
					
					$mbq = mysql_query("select * from p_billdata_multi where link='". $bd->link ."'");
					if(mysql_num_rows($mbq)<1){
					
						$pbq = mysql_query("select * from p_billdata where link='". $bd->link ."'");
						$pbd = mysql_fetch_object($pbq);
						
						$rq = mysql_query("select * from out_reading_xml where link='". $bd->link ."'");
						$rd = mysql_fetch_object($rq);
						
						echo '		<tr>';
						echo '			<td valign="top" align="center"><br/>'. date('F, Y',$bd->mydate) .'<hr/><b>'.$bd->bill_no.'</b><hr/>'. $bd->bill_from_datetime .' <b>to</b> '. $bd->bill_to_datetime .'<hr/>Due Date :<br/>'. $bd->bill_due_datetime .'</td>';
						echo '			<td valign="top">';
						echo '				<table align="center">';
						echo '					<tr align="center"><th>Previous</th>	<th>Current</th><tr/>';
						echo '					<tr align="center"><td>'.$bd->previous_reading.'</td>	<td>'.$bd->current_reading.'</td><tr/>';
						echo '					<tr align="center"><td colspan="2"><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $pbd->meterpic .'" /></td><tr/>';
						echo '					<tr><th align="left">PPunit</th>	<td align="right">'.$pbd->ppunit.'</td><tr/>';
						echo '					<tr><th align="left">Consumed</th>	<td align="right">'.$rd->unit_consumed.'</td><tr/>';
						echo '					<tr><th align="left">Billed</th>	<td align="right">'.$bd->billed_unit.'</td><tr/>';
						echo '				</table>';
						echo '			</td>';
						echo '			<td valign="top">';
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
							echo '				<tr><td align="left"><b>'. $slab_data[0] .' - </b></td>	<td align="right">'. $slab_data[1] .'</td>	<td align="right">X '. $slab_data[2] .'</td>	<td align="right">= '. $slab_data[3] .'</td></tr>';
						}
						echo '					<tr><td colspan="4"><hr/></td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Energy Charge</b></td>	<td align="right">'. $bd->energy_charge .'</td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Fixed Charge</b></td>	<td align="right">'. $bd->fixed_charge .'</td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Meter Rent</b></td>		<td align="right">'. $bd->meter_rent .'</td></tr>';
						echo '					<tr><td colspan="4"><hr/></td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Gross Charge</b></td>	<td align="right">'. $bd->gross_charge .'</td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Rebate</b></td>			<td align="right">'. $bd->rebate_charge .'</td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Credit Adj.</b></td>			<td align="right">'. $bd->credit_adjustment .'</td></tr>';
						echo '					<tr><td colspan="4"><hr/></td></tr>';
						echo '					<tr><td align="left" colspan="3"><b>Net Charge</b></td>		<td align="right">'. $bd->net_charge .'</td></tr>';
						echo '				</table>';
						echo '			</td>';
						
						
						$ashow = "-";
						if($bd->aid >0){
							$aq = mysql_query("select name,contact from agent_info where id='".$bd->aid."'");
							$ad = mysql_fetch_object($aq);
							
							$aname = json_decode(base64_decode($ad->name));
							$ashow = '<b>'.$aname[0].' '.$aname[1].'<br/>M:</b> '.$ad->contact ;
						}
						
						if($bd->down){
							$ashow .= '<br/><span style="color:#00f;">Processed</span>';
						}
						
						echo '			<td valign="top" align="center" style="text-transform:capitalize;">'. $ashow .'</td>';
						
						echo '		</tr>';
					}
					else{
						echo '<tr><td colspan="4">Empty Bill Data</td></tr>';
					}
				}
			}
			else{
				echo '<tr><td colspan="4">Empty Bill Data</td></tr>';
			}
			echo '	</table>';
			echo '';
		}
		else{
			echo '<div align="center">Invalid Consumer ID</div>';
		}
	}
	else{
		echo '<div align="center">Invalid Data</div>';
	}
}
else{
	echo "Unauthorized user";
}
?>