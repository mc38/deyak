<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$uid =base64_decode($_GET['s']);
	$query = "select * from zzuserdata where id='".$uid."'" ;
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);
		
		$access_str = $d->access;
		if($access_str != ""){
			$accesslist = json_decode(base64_decode($access_str));
			if(sizeof($accesslist)>0){
				
				$access_sep = implode(",",$accesslist);
				$aq = mysql_query("select id,name from zzauth where id in (". $access_sep .") order by id ");
				
				$j =1;
				while($ad = mysql_fetch_object($aq)){
				
					$act = '<button type="button" onclick="auth_del(this);" value="'.$ad->id.'" data-uid="'.$uid.'">Delete</button><div id="create_action_msg_'.$ad->id.'"></div>';
					
					echo '
						<tr>
							<th class="cus_sln"><span>'.$j.'</span></th>
							<td class="cus_det" valign="">
								'.$ad->name.'
							</td>
							<td class="cus_act" valign="top">'.$act.'</td>
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
			echo '<tr><td>Empty List</td></tr>';
		}
	}
	else{
		echo '<tr><td>User data problem</td></tr>';
	}
}
else{
	echo "Unauthorized user";
}
?>