<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	$query = "select * from zzdev";
	$q = mysql_query($query);
	
	$j =1;
	if(mysql_num_rows($q) >0){
		while($d = mysql_fetch_object($q)){
			
			$act = '
				<button type="button" style="width:50px;" value="'. base64_encode(json_encode($d)) .'" onclick="edit_data(this)">Edit</button>
				<button type="button" style="width:50px;" value="'. $d->id .'" onclick="del_data(this)">Del</button>
				<div id="action_msg_'. $d->id .'"></div>
			';
			
			echo '
				<tr>
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="middle">'. $d->parameter .'</td>
					<td class="cus_det" valign="middle">'. $d->value .'</td>
					<td class="cus_act" valign="middle">'. $act .'</td>
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