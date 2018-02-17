<?php
require_once("filter/type.php");

//////////////////////////////////////////////////////////////////////////////////////////////

echo '<div align="center" style="border-bottom:1px solid #000;"><h3>Android DB import report</h3></div>';
echo '<b>Report Date Time : </b>' .date('d-m-Y h:i:s a',$datetime).'<hr/>';
if(mysql_num_rows($caq) >0){
	$name = $a_d->user_detail;
	
	echo 'Agent Name : <b>'. $name .'</b>';
	echo '<hr/>';
	echo 'Total no of Bill data = '.$total_bill_data."<br/><br/>";
	echo 'Total no of Bill xml data = '.$total_bill_out_data."<br/><br/>";
	echo 'Total no of Reading xml data = '.$total_read_out_data."<br/><br/>";
	echo 'Total no of Payment data = '.$total_pay_data."<br/><br/>";
	echo '<hr/>';
	echo '<h3>Bill Data check please</h3>';
	echo '<table border="" style="border:1px solid #000; border-spacing:1px;">';
	echo '	<tr><th>Slno</th>	<th>Consumer ID</th>	<th>Consumer Name</th>	<th>Tariff ID</th>	<th>Prev Reading</th>	<th>PPUnit</th>	<th>Curr Reading</th>	<th>Meter Pic</th>	<th>Gross Charge</th>	<th>EBS Meter no</th>	<th>Field Meter no</th>	<th>Action</th></tr>';
	
	$meter_pic = array();
	for($i=0;$i<sizeof($b_d);$i++){
		$meter_pic[$b_d[$i]->link]=$b_d[$i]->meterpic;
		$fmeterno[$b_d[$i]->link]=$b_d[$i]->fmeterno;
		$status[$b_d[$i]->link]=$b_d[$i]->status;
		$reading_date[$b_d[$i]->link]=$b_d[$i]->reading_date;
		$ppunit[$b_d[$i]->link]=$b_d[$i]->ppunit;
	}
	
	$j=1;
	for($i=0;$i<sizeof($ob_d);$i++){
		
		$link = $ob_d[$i]->link;
		$t_cb_q = mysql_query("select id from trash_p_billdata where link ='".$link."' and reading_date='".$reading_date[$ob_d[$i]->link]."'");
		$cb_q = mysql_query("select id from out_bill_xml where link ='".$link."'");
		if((mysql_num_rows($cb_q) <1) ){
			$cc_q = mysql_query("select id,consumer_name,meterno from p_consumerdata where id='".$link."'");
			if(mysql_num_rows($cc_q) == 1){
				$cc_d = mysql_fetch_object($cc_q);
				$color= "";
				if($ob_d[$i]->gross_charge > 2000 && $status[$ob_d[$i]->link]<1){
					$color = ' style="background:#55150E; color:#fff;"';
				}
				
				$act ="-";
				if($status[$ob_d[$i]->link]<1){
					if( mysql_num_rows($t_cb_q) >0){
						$t_cb_d = mysql_fetch_object($t_cb_q);
						$act = '<div style="text-align:center; color:#000;">This Data is trashed.</div><button type="button" style="width:50px; margin:0px;" onclick="add_con(this,'.$link.');" value="'. $t_cb_d->id .'">ADD</button>';
					}
					else{
						$act = '<button type="button" style="width:50px; margin:0px;" onclick="delete_con(this);" value="'. $link .'">DEL</button>';
					}
				}
				
				echo '<tr'.$color.'>
						<td>'.$j.'</td>	<td>'.$ob_d[$i]->consumer_id.'</td>		
						<td>'.$cc_d->consumer_name.'</td>		
						<td>'.$ob_d[$i]->tariff_id.'</td>	
						<td>'.$ob_d[$i]->previous_reading.'</td>		
						<td>'.$ppunit[$ob_d[$i]->link].'</td>	
						<td>'.$ob_d[$i]->current_reading.'</td>	
						<td><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $meter_pic[$ob_d[$i]->link] .'" /></td>		
						<td>'.$ob_d[$i]->gross_charge.'</td>
						<td>'.$cc_d->meterno.'</td>	
						<td>'.$fmeterno[$ob_d[$i]->link].'</td>	
						<td style="background:#fff;" align="center">'. $act .'<div id="action_msg_'.$link.'"></div></td>
					</tr>';
				$j++;
			}
		}
	}
	echo '</table>';
}
else{
	echo '<div style="color:red;">Agent is blocked</div>';
}
?>