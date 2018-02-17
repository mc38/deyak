<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
require_once("../../../../../config/config.php");

if($u = authenticate() ){
		
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$sid = $data[2];
	
	
	if($sid == 0 || ($sid>0 && check_subdivision($u,$sid))){
		
		$q = mysql_query("select id from bill_details where subdiv_id='". $s ."' and mydate='". strtotime($sd) ."' and status='0'");
		if(mysql_num_rows($q) >0){
			
			echo '<h3>Android DB Process log</h3><hr/>';
			echo '<input type="hidden" id="mydate" value="'. $sd .'" /><input type="hidden" id="subdiv" value="'. $s .'" />';
			echo '<div id="logdata"></div>';
			echo '<input type="hidden" id="imdata" value="'. mysql_num_rows($q) .'" />Available data - <span id="imdatashow">'. mysql_num_rows($q) .'</span>';
			echo '<table id="batch_list" border="1">';
			
			$total_data = mysql_num_rows($q);
			
			$showme = true;
			
			$total_batch = ($total_data / $migration_batchno);
			if($total_data % $migration_batchno >0){
				$total_batch = $total_batch +1;
			}
			
			for($j=1;$j<=$total_batch;$j++){
				
				$datano = $migration_batchno;
				if($total_data<$migration_batchno){
					$datano = $total_data;
				}
				
				$but_action = '';
				if($showme){
					$but_action = 'onclick="bprocess(this);"';
				}else{
					$but_action = 'disabled="disabled"';
				}
				
				echo '
					<tr>
						<td class="cus_det" valign="">
							<b>Batch : </b>'. $j .'&nbsp;&nbsp;&nbsp;( '. $datano .' no of data )
						</td>
						<td class="cus_act" align="center">
							<button type="button" '. $but_action .'>Process</button>
						</td>
					</tr>
				';
				
				$showme = false;
				$total_data = $total_data - $migration_batchno;
			}
			
			echo '</table>';


		}
		else{
			echo '<tr><td>Empty List</td></tr>';
		}
	}else{
		echo "Unauthorized Sub Division";
	}
}
else{
	echo "Unauthorized user";
}
?>