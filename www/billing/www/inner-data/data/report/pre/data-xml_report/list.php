<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	
	$subdq = mysql_query("select id,name from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		
		$category 	= array();
		$code		= array();
		$cdata		= array();
		
		$cate_q = mysql_query("select name,tariff_id from consumer_cate");
		if(mysql_num_rows($cate_q) >0){
			while($cate_d = mysql_fetch_object($cate_q)){
				$tariff_d = json_decode(base64_decode($cate_d->tariff_id));
				$tariff_id=$tariff_d[0];
				
				if(! in_array($cate_d->name,$category)){
					$category[]=$cate_d->name;
					$code[] = substr($tariff_id,0,3);
					$cdata[] = array(0,0,0,0,0,0,0,0);
				}
				
				$index = array_search($cate_d->name,$category);
				if(strpos($code[$index],substr($tariff_id,0,3)) === false){
					$code[$index] = $code[$index] .", ". substr($tariff_id,0,3);
				}
				
			}
			
			
			$tq = mysql_query("select id from p_consumerdata where subdiv_id='".$s."' and mydate='". strtotime($sd)."'");
			if(mysql_num_rows($tq) >0){
				
				for($i=0; $i<sizeof($category); $i++){
					
					$cq = mysql_query("select id from p_consumerdata where subdiv_id='".$s."' and mydate='". strtotime($sd)."' and category_name='". $category[$i] ."'");
					$cdata[$i][0] = $cdata[$i][0] + mysql_num_rows($cq);
					
					$tar_str = implode("%' or tariff_id like '",explode(', ',$code[$i]));
					$tar_str = " and (tariff_id like '". $tar_str ."%')";
					
					$link_array = array();
					
					$bquery = "select id,gross_charge,link from out_bill_xml where subdivision_id='". $s ."' and mydate='". strtotime($sd) ."'". $tar_str ;
					
					$bq = mysql_query($bquery);
					if(mysql_num_rows($bq)>0){
						while($bd = mysql_fetch_object($bq)){
							$cdata[$i][1] = $cdata[$i][1] + 1;
							$cdata[$i][2] = $cdata[$i][2] + $bd->gross_charge;
							if(!in_array($bd->link,$link_array)){
								$link_array[]=$bd->link;
							}
							else{
								$cdata[$i][5] = $cdata[$i][5] + 1;
							}
						}
					}
					
					$cdata[$i][3] = $cdata[$i][3] + sizeof($link_array);
					
					$done_query = "select p_billdata.id from p_billdata inner join p_consumerdata on p_billdata.link= p_consumerdata.id where p_consumerdata.subdiv_id='".$s."' and p_consumerdata.mydate='". strtotime($sd)."' and p_consumerdata.category_name='". $category[$i] ."' and p_billdata.subdiv_id='".$s."' and p_billdata.mydate='". strtotime($sd)."' and p_billdata.status<>''";
					$done_q = mysql_query($done_query);
					$cdata[$i][6] = $cdata[$i][6] + mysql_num_rows($done_q);
					
					$cdata[$i][4] = $cdata[$i][4] + ($cdata[$i][6] - $cdata[$i][3]);
					
					$done_query = "select p_billdata.id from p_billdata inner join p_consumerdata on p_billdata.link= p_consumerdata.id where p_consumerdata.subdiv_id='".$s."' and p_consumerdata.mydate='". strtotime($sd)."' and p_consumerdata.category_name='". $category[$i] ."' and p_billdata.subdiv_id='".$s."' and p_billdata.mydate='". strtotime($sd)."' and p_billdata.status=''";
					$done_q = mysql_query($done_query);
					$cdata[$i][7] = $cdata[$i][7] + mysql_num_rows($done_q);
					
					
					
				}	
				
					
				/////////////////////////
				echo 'Subdivision ID : '. $s .'';
				echo '<hr/>';
				echo 'Month : '. date('F, Y', strtotime($sd)) .'';
				echo '<hr/>';
				if(sizeof($category)>0){
					echo '<h2>Overall XML Data report</h2>';
					echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
					echo '	<tr>
								<th align="left" style="width:130px;" rowspan="2">Category</th>	
								<th align="center" style="width:100px;" rowspan="2">Code</th>	
								<th align="center" rowspan="2">Consumer</th>	
								<th align="center" rowspan="2">XML</th>	
								<th align="center" rowspan="2">Amount (Rs)</th>
								<th align="center" style="color:#2A00FF;" rowspan="2">Multi Bill</th>
								<th align="center" style="color:#088D53;" colspan="3">Done</th>	
								<th align="right" style="color:#98261A;" rowspan="2">Un Done</th>
							</tr>
							<tr>
								<th>Pro</th>	
								<th>Not Pro</th>	
								<th>Total</th>
							</tr>
							';
					
					$con = 0;
					$xml = 0;
					$amn = 0;
					$mul = 0;
					$pr	 = 0;
					$npr = 0;
					$dn	 = 0;
					$udn = 0;
					
					for($i=0;$i<sizeof($category);$i++){
						echo '	<tr>
									<td style="text-transform:capitalize;">'. $category[$i] .'</td>	
									<td align="center">'. $code[$i] .'</td>	
									<td align="right">'. $cdata[$i][0] .'</td>	
									<td align="right">'. $cdata[$i][1] .'</td>	
									<td align="right">'. number_format($cdata[$i][2],2) .'</td>
									<td align="right">'. $cdata[$i][5] .'</td>
									<td align="right">'. $cdata[$i][3] .'</td>
									<td align="right">'. $cdata[$i][4] .'</td>	
									<td align="right">'. $cdata[$i][6] .'</td>	
									<td align="right">'. $cdata[$i][7] .'</td>
									
								</tr>';
						
						$con = $con + $cdata[$i][0];
						$xml = $xml + $cdata[$i][1];
						$amn = $amn + $cdata[$i][2];
						$mul = $mul + $cdata[$i][5];
						$pr  = $pr  + $cdata[$i][3];
						$npr = $npr + $cdata[$i][4];
						$dn  = $dn  + $cdata[$i][6];
						$udn = $udn + $cdata[$i][7];
					}
						echo '	<tr>
									<th style="text-transform:capitalize;" colspan="2">Total</th>	
									<td align="right">'. $con .'</td>	
									<td align="right">'. $xml .'</td>	
									<td align="right">'. number_format($amn,2) .'</td>	
									<td align="right">'. $mul .'</td>
									<td align="right">'. $pr .'</td>
									<td align="right">'. $npr .'</td>
									<td align="right">'. $dn .'</td>	
									<td align="right">'. $udn .'</td>
								</tr>';
					
					echo '</table>';
					
				}else{
					echo '<center><h3 style="color:red;">Empty Data</h3></center>';
				}
			
				
			}
			else{
				echo '<center><h3 style="color:red;">Empty Data</h3></center>';
			}
			
		}
		else{
			echo '<center><h3 style="color:red;">Empty Data</h3></center>';
		}
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>