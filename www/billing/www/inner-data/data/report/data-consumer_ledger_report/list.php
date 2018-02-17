<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$s = json_decode(base64_decode($_GET['s']));
		
		$sid = $s[0];
		$sdt = $s[1];
		$tar = $s[2];
		$bkn = $s[3];
		$cid = $s[4];
		
		$where = "";
		if($bkn !=""){
			$where .= " and bookno='".$bkn."'";
		}
		
		if($cid !=""){
			$where .= " and cid like '%".$cid."'";
		}
		
		if($tar !=""){
			$where .= " and tariff_id like '".$tar."%'";
		}
		
		
		echo '	<h3>Bill Details</h3><hr/>';
		echo '	<table border="1" style="border:1px solid #000; border-spacing:0px;">';
		echo '		<tr align="center">';
		echo '			<th>SlNo</th>';
		echo '			<th>Consumer Details</th>';
		echo '			<th>Date/Billno</th>';
		echo '			<th>Reading Details</th>';
		echo '			<th>Slab Details</th>';
		echo '		</tr>';
		
		$j=1;
		
		$cq = mysql_query("select * from p_consumerdata where subdiv_id='".$sid."' and mydate='".strtotime($sdt)."'".$where);
		if(mysql_num_rows($cq) >0){
			while($cd = mysql_fetch_object($cq)){
				
				$bq = mysql_query("select * from out_bill_xml where link='". $cd->id ."' order by id");
				if(mysql_num_rows($bq) >0){
					
					$link = ""; $ppunit =0;
					
					while($bd = mysql_fetch_object($bq)){
						
						
							$pbq = mysql_query("select * from p_billdata where link='". $bd->link ."'");
							$pbd = mysql_fetch_object($pbq);
							
							$rq = mysql_query("select * from out_reading_xml where link='". $bd->link ."'");
							$rd = mysql_fetch_object($rq);
							
							
							if($link != $bd->link){
								$link = $bd->link;
								$ppunit = $pbd->ppunit;
							}else{
								$ppunit = $ppunit - $rd->unit_consumed;
							}
							
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
							echo '			</td>';
											
											
							echo '			<td valign="top" style="width:120px;">';
							echo '				<table align="center">';
							echo '					<tr align="center"><th>Previous</th>	<th>Current</th><tr/>';
							echo '					<tr align="center"><td>'.$bd->previous_reading.'</td>	<td>'.$bd->current_reading.'</td><tr/>';
							echo '					<tr align="center"><td colspan="2"><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $pbd->meterpic .'" /></td><tr/>';
							echo '					<tr><th align="left">PPunit</th>	<td align="right">'. $ppunit .'</td><tr/>';
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
							echo '					<tr><td align="left" colspan="3"><b>Rebate</b></td>			<td align="right">'. $bd->credit_adjustment .'</td></tr>';
							echo '					<tr><td colspan="4"><hr/></td></tr>';
							echo '					<tr><td align="left" colspan="3"><b>Net Charge</b></td>		<td align="right">'. $bd->net_charge .'</td></tr>';
							echo '				</table>';
							echo '			</td>';
							
							echo '		</tr>';
							
							
							
							$j++;
					}
				}
			}
			
		}else{
			echo '<tr><td colspan="4">No Data Found</td></tr>';
		}
		
		echo '	</table>';
	}
	else{
		echo '<div align="center">Invalid Data</div>';
	}
}
else{
	echo "Unauthorized user";
}
?>