<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$query = "select id,name from zzpagetag order by srl";
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) >0){
		while($d = mysql_fetch_object($q)){
		
			$j=1;
			
			echo '
				<tr><td colspan="3" style="padding-left:5px; font-weight:bold; border-top:1px solid #000;">'.$d->name.'</td></tr>
			';
			
			$pquery = "select * from zzpage where link='".$d->id."' order by srl";
			$pq = mysql_query($pquery);
			
			if(mysql_num_rows($pq) >0){
				while($pd = mysql_fetch_object($pq)){
					
					
					$shbut ="";
					if($pd->status == 0){
						$shbut = '<button type="button" style="float:left;" class="act_but" onclick="page_sh(this);" value="'.$pd->id.'">Hide</button>';
					}
					else{
						$shbut = '<button type="button" style="float:left;" class="act_but" onclick="page_sh(this);" value="'.$pd->id.'">Show</button>';
					}
					
					$upbut ="";
					if($j > 1){
						$upbut = '<button type="button" class="act_but" onclick="page_up(this);" value="'.$pd->srl.'" data-link="'.$d->id.'">Up</button>';
					}
					
					$downbut ="";
					if($j < mysql_num_rows($pq)){
						$downbut = '<button type="button" class="act_but" onclick="page_down(this);" value="'.$pd->srl.'" data-link="'.$d->id.'">Down</button>';
					}
					
					
					$act = $shbut .''.$upbut .''.$downbut.'
						<div id="create_action_msg_'.$pd->id.'"></div>
					';
					
					echo '
						<tr>
						<style>.act_but{width:50px !important; margin:5px 2px !important;}</style>
							<th class="cus_sln"><span>'.$j.'</span></th>
							<td class="cus_det" valign="">
								<b>'.$pd->name.'</b>
								<p style="margin-top:0px;font-size:14px; text-transform:none;">'. $pd->location .'</p>
							</td>
							<td class="cus_act" valign="top">'.$act.'</td>
						</tr>
					
					';
					
					
					$j++;
				}
			}
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