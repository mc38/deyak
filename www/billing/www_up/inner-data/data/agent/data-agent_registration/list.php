<?php
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if($u = authenticate()){
	
	$sdata = json_decode(base64_decode($_GET['s']));
	
	$subdiv = $sdata[0];
	$s = $sdata[1];
	
	
		
		$where ="";
		if($subdiv > 0){
			$where =" where subdiv='".$subdiv."' and name like '".$s."%'";
		}else{
			$where .= "";
		}
		
		$query = "select * from agent_info".$where;
		$q = mysql_query($query);
		
		if(mysql_num_rows($q) >0){
			$j=1;
			while($d = mysql_fetch_object($q)){
				
				$name = $d->name;
				$sex = "Male";
				if($d->sex >0){
					$sex = "Female";
				}
				
				$status = "";
				$act = '<button type="button" value="'.$d->id.'" onclick="agnt_block(this);">Block</button>';
				$backdoor = "";
				if($d->status == "1"){
					$act = '<button type="button" value="'.$d->id.'" onclick="agnt_block(this);">Un-Block</button>';
					$status = '<b>Status : </b><span style="color:red;">Blocked</span><br />';
				}else{
					$backdoor = '<button type="button" style="width:130px;" value="'.$d->id.'" onclick="agnt_backdoor(this);">BackDoor Open</button>';
					if($d->backdoor == 1){
						$backdoor = '<button type="button" style="width:130px;" value="'.$d->id.'" onclick="agnt_backdoor(this);">BackDoor Close</button>';
					}
				}

				
				
				$act .= '<div>'. $backdoor .'</div>';
				$act .= '<div id="create_action_msg_'. $d->id .'"></div>';
				
				
				echo '
					<tr>
						<th class="cus_sln"><span>'.$j.'</span></th>
						<td class="cus_date" valign="top">'.date('d-m-Y',$d->datetime).'<br />'.date('h:i:s A',$d->datetime).'</td>
						<td class="cus_det" valign="top">
							<b>Agent PIN : </b><span style="color:red;">'.$d->agent_pin.'</span><br/>
							<b>Name : </b>'.strtoupper($name).'<br />
							<b>Contact no : </b>'.$d->contact.'<br />
							<b>Gender : </b>'.$sex.'<br />
							'.$status.'
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
	echo "Unauthorized user";
}
?>