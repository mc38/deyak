<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");
include "../../../../../config/config.php";

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s 	= $data[0];
	$fd = $data[1];
	$td = $data[2];
	
	$where ="";
	if($fd !="" && $td !=""){
		$where .= " and c_import_datetime>". strtotime($fd) ." and c_import_datetime<". strtotime('+1day',strtotime($td));
	}
	
	$q = mysql_query("select * from m_data where in_status<>'' and c_import_status=1 and c_pass_status=0 and c_subdiv_id='". $s ."'". $where ."");
	if(mysql_num_rows($q) >0){

		echo '<h3>Bulk Approve Process log</h3><hr/>';
		echo '<input type="hidden" id="fd" value="'. $fd .'" />';
		echo '<input type="hidden" id="td" value="'. $td .'" />';
		echo '<input type="hidden" id="subdiv" value="'. $s .'" />';
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

	}else{
		echo '<div align="center">Empty list</div>';
	}
}
else{
	echo '<div align="center">Unauthorized user</div>';
}
?>