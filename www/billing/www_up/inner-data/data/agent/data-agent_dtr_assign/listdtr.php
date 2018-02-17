<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
if(isset($_GET['d']) && $_GET['d']!=""){	
	$dtr = $_GET['d'];
	
	$query = "select * from agent_dtr where dtr='". $dtr ."' ";
	$q = mysql_query($query);
	if(mysql_num_rows($q)==1){	
		$d = mysql_fetch_object($q);
		
		$aquery = "select name from agent_info where id='".$d->aid."'";
		$aq = mysql_query($aquery);
		$ad = mysql_fetch_object($aq);
		
		$j =1;
				
		$act = '
			<button type="button" style="width:50px;" value="'. $d->id .'" onclick="del_data(this)">Del</button>
			<div id="action_msg_'. $d->id .'"></div>
		';
				
		echo '
			<tr>
				<th class="cus_sln"><span>'.$j.'</span></th>
				<td class="cus_det" valign="middle">'. $ad->name .'</td>
				<td class="cus_det" valign="middle">'. $d->dtr .'</td>
				<td class="cus_act" valign="middle">'. $act .'</td>
			</tr>
		';
	}
	else{
		echo "<tr><td>DTR not assigned</td></tr>";
	}
}
else{
	echo "<tr><td>Data problem</td></tr>";
}
}
else{
	echo "<tr><td>Unauthorized user</td></tr>";
}
?>