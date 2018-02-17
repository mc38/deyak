<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$query = "select * from zzpagetag order by srl";
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) >0){
		$j=1;
		while($d = mysql_fetch_object($q)){
			
			$shbut ="";
			if($d->status == 0){
				$shbut = '<button type="button" style="float:left;" class="act_but" onclick="tag_sh(this);" value="'.$d->id.'">Hide</button>';
			}
			else{
				$shbut = '<button type="button" style="float:left;" class="act_but" onclick="tag_sh(this);" value="'.$d->id.'">Show</button>';
			}
			
			$upbut ="";
			if($j > 1){
				$upbut = '<button type="button" class="act_but" onclick="tag_up(this);" value="'.$d->srl.'">Up</button>';
			}
			
			$downbut ="";
			if($j < mysql_num_rows($q)){
				$downbut = '<button type="button" class="act_but" onclick="tag_down(this);" value="'.$d->srl.'">Down</button>';
			}
			
			
			$act = $shbut .''.$upbut .''.$downbut.'
				<div id="create_action_msg_'.$d->id.'"></div>
			';
			
			echo '
				<tr>
				<style>.act_but{width:50px !important; margin:5px 2px !important;}</style>;
					<th class="cus_sln"><span>'.$j.'</span></th>
					<td class="cus_det" valign="">
						'.$d->name.'
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