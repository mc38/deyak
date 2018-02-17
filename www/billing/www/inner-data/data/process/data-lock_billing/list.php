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
		
		$t_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."'");
		$t_r = mysql_num_rows($t_q);

		$ul_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."' and c_done=0");
		$ul_r = mysql_num_rows($ul_q);

		$l_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."' and c_done=1");
		$l_r = mysql_num_rows($l_q);

		$q_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."' and c_done=0 and c_import_status=1 and c_pass_status=0");
		$q_r = mysql_num_rows($q_q);

		$rl_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."' and c_done=0 and c_import_status=1 and c_pass_status=2");
		$rl_r = mysql_num_rows($rl_q);

		$rf_q = mysql_query("select id from m_data where c_subdiv_id='". $s ."' and c_mydate='". strtotime($sd) ."' and c_done=0 and c_import_status=0 and c_pass_status=2");
		$rf_r = mysql_num_rows($rf_q);
			
		echo '<h3>Billing log</h3><hr/>';
		echo '<input type="hidden" id="mydate" value="'. $sd .'" /><input type="hidden" id="subdiv" value="'. $s .'" />';
		echo '<div id="logdata" align="left">';
		echo '<div><b>Total Billing data - </b>'. $t_r .'</div>';
		echo '<div><b>Total unlocked data - </b>'. $ul_r .'</div>';
		echo '<div><b>Total locked data - </b>'. $l_r .'</div>';
		echo '<hr/>';
		echo '<div><b>Total queued data - </b>'. $q_r .'</div>';
		echo '<div><b>Total rejected data which are not in field - </b>'. $rl_r .'</div>';
		echo '<div><b>Total rejected data which are in field - </b>'. $rf_r .'</div>';
		echo '</div>';
		echo '<hr/>';
		echo '<div style="font-size:14px;">Click on the Lock Button to lock all billing data. After locking the billing for selected billing cycle will be completed</div>';
		echo '<hr/>';
		echo '<div><button type="button" onclick="lock(this);">Lock</button></div>';
		echo '<div id="lock_message"></div>';
			


		
	}else{
		echo "Unauthorized Sub Division";
	}
}
else{
	echo "Unauthorized user";
}
?>