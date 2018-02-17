<?php
ini_set('max_execution_time', 10000);
require_once("../db/command.php");
require_once("../plugin/func/authentication.php");


	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id,name from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		$subdd = mysql_fetch_object($subdq);
		$mdays = date('t',strtotime($sd));
		///////////////////////////////////////////////////////////////////////////////////////
		
		
		$q = mysql_query("select * from public_xml where subdiv='".$s."' and mydate='".strtotime($sd)."' order by id");
		if(mysql_num_rows($q)>0){
			$t = 0;
			$pub_xml = array();
			while($d = mysql_fetch_object($q)){
				$temp[0] = date('d-m-Y',$d->datetime);
				$temp[1] = $d->datafrom;
				$temp[2] = $d->total;
				$temp[3] = $t;
				
				$pub_xml[]=$temp;
				$t = $t + $d->total;
			}
			
			$bx_arr_2d = array();
			$download_xml = array();
			for($i=0;$i<sizeof($pub_xml);$i++){
				
				$xdate = $pub_xml[$i][0];
				$xfrom = $pub_xml[$i][1];
				$xtotl = $pub_xml[$i][2];
				
				$pdate = '';
				$ptotl = '';
				if($i>0){
					if(strtotime($pub_xml[$i-1][0]) < strtotime($xdate)){
						$pdate = $pub_xml[$i-1][0];
						$ptotl = $pub_xml[$i][3];
					}
				}
				
				
				$btemp = array_fill(0,$mdays,0);
				$xq = mysql_query("select down,bill_datetime from out_bill_xml where subdivision_id='".$s."' and mydate='".strtotime($sd)."' order by id limit ". $xfrom .",".$xtotl);
				while($xd = mysql_fetch_object($xq)){
					if($xd->down == '1'){
						$m = strtotime($xd->bill_datetime);
						$btemp_index =((int) date('d',$m))-1;
						$btemp[$btemp_index] ++;
					}
				}
				
				
				
				$dtemp[0] = $xdate;
				$dtemp[1] = $xfrom;
				$dtemp[2] = $xtotl;
				$dtemp[3] = $pdate;
				$dtemp[4] = $ptotl;
				$dtemp[5] = 0;
				
				$download_xml[]=$dtemp;
				$bx_arr_2d[]=$btemp;
			}
			
			
			$bc_arr = array_fill(0,$mdays,0);
			for($i=0;$i< sizeof($bx_arr_2d);$i++){
				for($j=0;$j<sizeof($bx_arr_2d[$i]);$j++){
					$bc_arr[$j] += $bx_arr_2d[$i][$j];
				}
			}
			
			
			for($i=0;$i<sizeof($download_xml);$i++){
				$dhigh = array();
				
				$total_xml=0;
				$to =(int) date('d',strtotime($download_xml[$i][0]));
				for($x=0;$x<$to;$x++){
					$total_xml += $bx_arr_2d[$i][$x];
					$dhigh[] = $i."_".$x;
				}
				
				if($download_xml[$i][3] != ""){
					$f =(int) date('d',strtotime($download_xml[$i][3]));
					for($j=0;$j<$i;$j++){
						for($x=$f;$x<$to;$x++){
							$total_xml += $bx_arr_2d[$j][$x];
							$dhigh[] = $j."_".$x;
						}
					}
				}
				
				$dhigh_str = base64_encode(json_encode($dhigh));
				
				$download_xml[$i][5] = $total_xml;
				$download_xml[$i][6] = $dhigh_str;
			}
			
					
			/////////////////////////all echo /////////////////////////////////////////////////////////////////
			
		
			echo '<h3>'. $subdd->name .' Subdivision Bill date wise Processed XML data report</h3>';
			echo '<table border="1" style="border:1px solid #000; border-spacing:1px">';
			
			$bc_tot = 0;
			//////////////////////////////////////////
			echo '	<tr align="center">	<th>Date</th>';
			for($i=1;$i<=16;$i++){
				echo '	<th>'. $i .'</th>';
			}
			echo '	</tr>';
			//////////////////////////////////////////
			echo '	<tr align="center">	<th>Qunt</th>';
			for($i=0;$i<16;$i++){
				$bc_tot += $bc_arr[$i];
				echo '	<td>'. $bc_arr[$i] .'</td>';
			}
			echo '	</tr>';
			///////////////////////////////////////////
			echo '	<tr align="center">	<th>Date</th>';
			for($i=17;$i<=sizeof($bc_arr);$i++){
				echo '	<th>'. $i .'</th>';
			}
			echo '	</tr>';
			////////////////////////////////////////////
			echo '	<tr align="center">	<th>Qunt</th>';
			for($i=16;$i<sizeof($bc_arr);$i++){
				$bc_tot += $bc_arr[$i];
				echo '	<td>'. $bc_arr[$i] .'</td>';
			}
			echo '	</tr>';
			////////////////////////////////////////////
			echo '	<tr align="center">	<th>Total</th>	<td colspan="17" style="font-size:20px; text-align:left;">'. $bc_tot .'</td></tr>';
			
			echo '</table>';
			
			
			////////////////////////////////////////////////////////////
			
			$j=1;
			echo '<table border="1" style="border:1px solid #000; border-spacing:1px">';
			echo '<tr><td colspan="4" style="text-transform:capitalize; font-size:18px;">'. $subdd->name .' Subdivision XML data</td></tr>';
			echo '	<tr align="center">	<th>Slno</th>	<th>Date</th>	<th>Data</th>	<th>Download</th></tr>';
			$all_ctotl = 0;
			for($i=0;$i<sizeof($download_xml);$i++){
				
				$xdate = $download_xml[$i][0];
				$xfrom = $download_xml[$i][1];
				$xtotl = $download_xml[$i][2];
				$pdate = $download_xml[$i][3];
				$ptotl = $download_xml[$i][4];
				$ctotl = $download_xml[$i][5];
				
				$dhighlight = $download_xml[$i][6];
				
				echo '<tr class="high_trig" align="center" data-high="'. $dhighlight .'" data-show="0" onclick="highlight_d(this);" style="cursor:pointer;">
							<td>'.$j.'</td>	
							<td>'.$xdate.'</td>	
							<td>'.$ctotl.'</td>	
							<td>
								<button type="button" data-type="1" data-subdiv="'.$s.'" data-mydate="'.$sd.'" data-xdate="'.$xdate.'" data-xfrom="'.$xfrom.'" data-xtotl="'.$xtotl.'" data-pdate="'.$pdate.'" data-ptotl="'.$ptotl.'" data-ctotl="'.$ctotl.'" onclick="file_download(this);">Reading XML</button> 
								<button type="button" data-type="2" data-subdiv="'.$s.'"  data-mydate="'.$sd.'" data-xdate="'.$xdate.'" data-xfrom="'.$xfrom.'" data-xtotl="'.$xtotl.'" data-pdate="'.$pdate.'" data-ptotl="'.$ptotl.'" data-ctotl="'.$ctotl.'" onclick="file_download(this);">Bill XML</button>
							</td>
					 </tr>';
				
				$all_ctotl = $all_ctotl + $ctotl;
					 
				$j++;
			}
			echo '		<tr align="center">	<td colspan="2">Total</td>	<td>'. $all_ctotl .'</td> <td>Click on the above list to view which data are involved</td></tr>';
			echo '</table>';
			
			
			
			echo '<p style="font-size:18px; font-weight:bold;">Total Break up (X axis - Bill Date / Y axis - process date)</p>';
			
			echo '<table border="1">';
			echo '<tr><th>No</th>';
			for($i=0;$i<$mdays;$i++){
				$x = $i+1;
				echo '<th>'. $x .'</th>';
			}
			echo '</tr>';
			
			for($i=0;$i<sizeof($bx_arr_2d);$i++){
				$x = $i+1;
				$day = (int) date('d',strtotime($download_xml[$i][0]));
				echo '<tr><th>'. $x .'('. $day .')</th>';
				for($j=0;$j<sizeof($bx_arr_2d[$i]);$j++){
					echo '<td class="high_eff" id="h_'. $i .'_'. $j .'">'. $bx_arr_2d[$i][$j] .'</td>';
				}
				echo '</tr>';
			}
			echo '</table>';
			
			
		}
		else{
			echo '<center><h3 style="color:red;">Empty List</h3></center>';
		}
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}

?>