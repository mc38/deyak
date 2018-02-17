<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if($u = authenticate()){
	
	if(isset($_GET['s']) && $_GET['s'] !=""){
		$where =" and byuser = '". $u ."' and auth = '".base64_decode($_GET['s'])."'";
	
		$query = "select * from zzuserdata where id>0". $where ;
		$q = mysql_query($query);
		
		if(mysql_num_rows($q) >0){
			$j=1;
			
			while($d = mysql_fetch_object($q)){
				
				$name = $d->fname ." ". $d->lname;
				$sex = "Male";
				if($d->sex >0){
					$sex = "Female";
				}
				
				$updata  = '<span style="color:blue;">User is active</span>';
				if($d->uactive !=""){
					$upd = json_decode(base64_decode($d->uactive));
					$updata ='<b>Username: </b><span style="color:blue; text-transform:lowercase;">'.$upd[0].'</span><br />
							  <b>Password: </b><span style="color:green; text-transform:none;">'.$upd[1].'</span><br />';
				}
				
				$status = "";
				$act = '<button type="button" value="'.$d->id.'" onclick="user_block(this);">Block</button>';
				if($d->status == "1"){
					$act = '<button type="button" value="'.$d->id.'" onclick="user_block(this);">Un-Block</button>';
					$status = '<b>Status : </b><span style="color:red;">Blocked</span><br />';
				}
				
				if($d->uactive ==""){
					$act .= '<br/><button type="button" value="'.$d->id.'" onclick="user_restore(this);">Re-store</button>';
				}
				
				$act .= '<div id="create_action_msg_'. $d->id .'"></div>';
				
				
				echo '
					<tr>
						<th class="cus_sln"><span>'.$j.'</span></th>
						<td class="cus_det" valign="top">
							<b>Name : </b>'.strtoupper($name).'<br />
							<b>Contact no : </b>'.$d->contact.'<br />
							<b>Gender : </b>'.$sex.'<br />
							<b>Authority : </b>'.$d->name.'<hr/>
							'.$updata.'
							'.$status.'
						</td>
						<td class="cus_act" valign="top">
							'.$act.'
							<br/><button type="button" value="'.$d->id.'" onclick="user_delete(this);">Delete</button>
						</td>
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
		echo '<tr><td>Select authority</td></tr>';
	}
}
else{
	echo "Unauthorized user";
}
?>