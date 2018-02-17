<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	
	$where ="";
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$where =" where name like '".base64_decode($_GET['s'])."%'";
	}
	$query = "select * from settings_consumer_cate".$where;
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) >0){
		$j=1;
		
		while($d = mysql_fetch_object($q)){
			
			$name = $d->name;
			$tariff_id =  $d->tariff_id;
			$eduty = $d->electricity_duty;
			$schrg = $d->surcharge;
			$fppa  = $d->fppa;
			
			$slab_out=""; $slab_out_f ="";
			$mslab = $d->slab;
			$mslab_data = json_decode(base64_decode($mslab));
			
			if($mslab_data[0] >0){
				
				$slab = $mslab_data[1];
				$slab_data = json_decode(base64_decode($slab));
				
				if($slab !=""){
					
					if($slab_data[0] >0)
					
					
					$slab_out_f .='<div class="sub-content">
									<table border="1">
										<tr><td colspan="5">Special Slab</td></tr>
										<tr align="center">
											<th>SL</th>
											<th>Meter Slab Reading</th>
											<th>Meter Slab Amount</th>
											<th>Fixed Charge</th>
											<th>Subsidy</th>
										</tr>
						';
						
					$slab_make_complete = false;	
						
					for($ii=0;$ii< sizeof($slab_data);$ii++){
						$ij = $ii +1;
						
						$slabshow = $slab_data[$ii][0] .' to '. $slab_data[$ii][1];
						if($slab_data[$ii][1] ==""){
							$sto = $slab_data[$ii][0] -1;
							$slabshow = "Over" . $sto;
							$slab_make_complete = true;
						}
						
						if($slab_data[$ii][1] =="" && $slab_data[$ii][0] ==1){
							$slabshow = "All Units";
							$slab_make_complete = true;
						}
						
						
						$slab_out_f .='<tr align="center">
										<td>'.$ij.'</td>
										<td>'.$slabshow.'</td>
										<td>Rs '. number_format($slab_data[$ii][2],2).'</td>
										<td>Rs '. number_format($slab_data[$ii][3],2).'</td>
										<td>Rs '. number_format($slab_data[$ii][4],2).'</td>
									</tr>
						';
					}
					$slab_out_f .='</table></div>';
				}
				
			}
			
			$slab = $mslab_data[2];
			$slab_data = json_decode(base64_decode($slab));
			
			if($slab !=""){
				
				if($slab_data[0] >0)
				
				
				$slab_out .='<div class="sub-content">
								<table border="1">
									<tr><td colspan="5">Main Slab</td></tr>
									<tr align="center">
										<th>SL</th>
										<th>Meter Slab Reading</th>
										<th>Meter Slab Amount</th>
										<th>Fixed Charge</th>
										<th>Subsidy</th>
									</tr>
					';
					
				$slab_make_complete = false;	
					
				for($ii=0;$ii< sizeof($slab_data);$ii++){
					$ij = $ii +1;
					
					$slabshow = $slab_data[$ii][0] .' to '. $slab_data[$ii][1];
					if($slab_data[$ii][1] ==""){
						$sto = $slab_data[$ii][0] -1;
						$slabshow = "Over" . $sto;
						$slab_make_complete = true;
					}
					
					if($slab_data[$ii][1] =="" && $slab_data[$ii][0] ==1){
						$slabshow = "All Units";
						$slab_make_complete = true;
					}
					
					
					$slab_out .='<tr align="center">
									<td>'.$ij.'</td>
									<td>'.$slabshow.'</td>
									<td>Rs '. number_format($slab_data[$ii][2],2).'</td>
									<td>Rs '. number_format($slab_data[$ii][3],2).'</td>
									<td>Rs '. number_format($slab_data[$ii][4],2).'</td>
								</tr>
					';
				}
				$slab_out .='</table></div>';
			}
			

			$pfeff = array();
			$pfeff['+'] = "Penalty";
			$pfeff['-'] = "Rebate";

			$pfslab = '<span style="color:red;">Power Factor is not available</span>';
			if($d->pfslab !=""){
				$pf = json_decode(base64_decode($d->pfslab));

				$lowpfslab = '';
				$pflow = json_decode(base64_decode($pf[1]));
				for($i=0; $i<sizeof($pflow); $i++){
					$j = $i +1;

					$pfrange = $pflow[$i][0] .' to '. $pflow[$i][1] ;
					if($pflow[$i][1] == ""){
						$pfrange = 'Below '. $pflow[$i][0];
					}

					$lowpfslab .= '
						<tr>
							<td align="center">'. $j .'</td>
							<td align="center">'. $pfrange .'</td>
							<td align="center">'. $pfeff[$pflow[$i][2]] .'</td>
							<td align="center">'. $pflow[$i][2] .''. $pflow[$i][3] .'%</td>
						</tr>
					';
				}

 				$hghpfslab = '';
				$pfhgh = json_decode(base64_decode($pf[2]));
				for($i=0; $i<sizeof($pfhgh); $i++){
					$j = $i +1;

					$pfrange = $pfhgh[$i][0] .' to '. $pfhgh[$i][1] ;
					if($pfhgh[$i][1] == ""){
						$pfrange = 'Above '. $pfhgh[$i][0];
					}

					$hghpfslab .= '
						<tr>
							<td align="center">'. $j .'</td>
							<td align="center">'. $pfrange .'</td>
							<td align="center">'. $pfeff[$pfhgh[$i][2]] .'</td>
							<td align="center">'. $pfhgh[$i][2] .''. $pfhgh[$i][3] .'%</td>
						</tr>
					';
				}



				$pfslab = '
				<table border="1">
					<tr><td colspan="4"><b>Power Factor Threshold</b> - '. $pf[0] .'</td></tr>
					<tr><th colspan="4">Lower than '. $pf[0] .'</th></tr>
					<tr>
						<th align="center">Slno</th>
						<th align="center">Slab of PF</th>
						<th align="center">Effect</th>
						<th align="center">Rate per 1% PF Decrease</th>
					</tr>
					'. $lowpfslab .'
					<tr><th colspan="4">Upper than '. $pf[0] .'</th></tr>
					<tr>
						<th align="center">Slno</th>
						<th align="center">Slab of PF</th>
						<th align="center">Effect</th>
						<th align="center">Rate per 1% PF Increase</th>
					</tr>
					'. $hghpfslab .'
				</table>
				';
			}

			
			echo '
				<tr>
					<th class="cus_sln" valign="top"><span>'.$j.'</span></th>
					<td class="cus_det" valign="top">
						<b>Name : </b>'.strtoupper($name).'<br />
						<b>Code : </b>'. $tariff_id .'<br />
						<div id="slab_'.$d->id.'">
						<hr/>
						<b>Electricity Duty : </b>Rs '. number_format($eduty,2) .'/unit<br />
						<b>Surcharge : </b>'. $schrg .'%<br />
						<b>FPPPA : </b>Rs '. number_format($fppa,2) .'/unit<br />
						'.$slab_out_f.'<br />
						'.$slab_out.'<br />
						</div>
						<hr/>
						<div>
							'. $pfslab .'
						</div>
					</td>
				</tr>
			
			';
			$j++;
		}
	}
	else{
		echo '<tr><td>Empty List</td></tr>';
	}
}
else{
	echo "Unauthorized user";
}
?>