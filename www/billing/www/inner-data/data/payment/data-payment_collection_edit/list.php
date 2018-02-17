<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$ai= $data[2];
	$cd= $data[3];
	
	$subdq = mysql_query("select id from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
			
			$where = "";
			if($cd !=""){
				$where .= " and datetime='".date('d-m-Y',strtotime($cd))."%'";
			}
			
			if($ai !=""){
				$where .= " and aid='".$ai."'";
			}
			
			$query ="select * from payment_data where subdiv='".$s."' and mydate='".strtotime($sd)."'".$where;
			$q = mysql_query($query);
			
			if(mysql_num_rows($q) >0){
				echo 'Subdivision ID : '. $s .'';
				echo '<hr/>';
				echo 'Month : '. date('F, Y', strtotime($sd)) .'';
				echo '<hr/>';
				
				{
					$query_p = "select * from payment_setting where subdiv='".$s."'";
					$pq = mysql_query($query_p);
					$pd = mysql_fetch_object($pq);
					$ctype = $pd->ctype;
					$cdata = $pd->cdata;
					
					$comm = "";
					if($ctype == 1){
						$comm = "Rs ". $cdata ."/- per transaction";
					}else if($ctype ==2){
						$comm = $cdata ."% of collection money";
					}
				}
				echo 'Commission : '. $comm .'';
				echo '<hr/>';
				
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr><th>Slno</th>	<th>Consumer ID</th>	<th>Name</th>	<th>Agent</th>	<th>Collection Date</th>	<th>Collection Amount</th>	<th>Commission</th>		<th>Total</th</tr>';
				
				$am_t =0;
				$co_t =0;
				$to_t =0;
				
				$j =1;
				while($d = mysql_fetch_object($q)){
					$cq = mysql_query("select consumer_name from p_consumerdata where mydate='". strtotime($sd) ."' and subdiv_id='". $s ."' and cid='". $d->cid ."'");
					$cd = mysql_fetch_object($cq);
					
						echo '<tr>';
						echo '<td>'.$j.'</td>';
						echo '<td>'. $d->cid .'</td>	<td>'. $cd->consumer_name .'</td>';
						
						$aq = mysql_query("select name from agent_info where id='".$d->aid."'");
						$ad = mysql_fetch_object($aq);
						$aname = json_decode(base64_decode($ad->name));
						echo '<td style="text-transform:capitalize;">'. $aname[0] .' '. $aname[1] .'</td>';
						
						echo '<td align="center">'. $d->datetime .'</td>';
						echo '<td align="right">
								Rs '. number_format($d->amount,2) .'/-<br/>
								<input id="pd_'. $d->id .'" type="text" value="" placeholder="Type Here"  style="width:60px; margin:0px;" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" /><br/>
								<button type="button" value="'. $d->id .'" style="width:60px; margin:0px;" onclick="pay_edit(this);">Edit</button>
								<div id="action_msg_'. $d->id .'"></div>
							  </td>';
						
						
						$am_t = $am_t + $d->amount;
						$acks ="";
						if($d->prints == 0){
							$acks = '<span style="color:red;">ACK not given</span>';
						}else if($d->prints == 1){
							$acks = '<span style="color:blue;">ACK given</span>';
						}
						
						echo '<td align="right">Rs '. number_format($d->commission,2) .'/-<br/>'. $acks .'</td>';
						$co_t = $co_t + $d->commission;
						
						$total = $d->amount + $d->commission ;
						echo '<td align="right">Rs '. number_format($total,2) .'/-</td>';
						$to_t = $to_t + $total;
						
						echo '</tr>';
						$j++;
				}
				
				echo '
						<tr>
							<th colspan="5">Total</th>
							<td align="right">Rs '. number_format($am_t,2) .'/-</td>
							<td align="right">Rs '. number_format($co_t,2) .'/-</td>
							<td align="right">Rs '. number_format($to_t,2) .'/-</td>
						</tr>
				';
				
				
				echo '</table>';
			}
			else{
				echo '<center><h3 style="color:red;">Empty List</h3></center>';
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