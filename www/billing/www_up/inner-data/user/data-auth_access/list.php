<?php
require_once("../../../db/command.php");
require_once("../../../plugin/func/authentication.php");

if(authenticate()){
	
	$aid =base64_decode($_GET['s']);
	$query = "select * from zzauth where id='".$aid."'" ;
	$q = mysql_query($query);
	
	if(mysql_num_rows($q) ==1){
		$d = mysql_fetch_object($q);
		
		$access_str = $d->access;
		if($access_str != ""){
			$accesslist = json_decode(base64_decode($access_str));
			if(sizeof($accesslist)>0){
				
				$tquery = "select id,name from zzpagetag order by srl";
				$tq = mysql_query($tquery);
				
				$tag_array = array(); $tag_array_n = array();
				if(mysql_num_rows($tq) >0){
					while($td = mysql_fetch_object($tq)){
						$tag_array[]=$td->id;
						$tag_array_n[$td->id]=$td->name;
					}
				}
				
				$active_tag= array();
				for($i=0;$i<sizeof($tag_array);$i++){
					$pq = mysql_query("select id,name from zzpage where link ='". $tag_array[$i] ."' order by srl ");
					if(mysql_num_rows($pq) >0){
						while($pd = mysql_fetch_object($pq)){
							if(in_array($pd->id,$accesslist)){
								if(! in_array($tag_array[$i],$active_tag)){
									$active_tag[]=$tag_array[$i];
								}
								$show_list[$tag_array[$i]][]=$pd;
							}
						}
					}
				}
				
				for($i=0;$i<sizeof($active_tag);$i++){
				
					echo '
						<tr><td colspan="3" style="padding-left:5px; font-weight:bold; border-top:1px solid #000;">'.$tag_array_n[$active_tag[$i]].'</td></tr>
					';
					
					$j=1;
					for($ii=0;$ii<sizeof($show_list[$active_tag[$i]]);$ii++){
						
						$data = $show_list[$active_tag[$i]][$ii];
						
						$act = '<button type="button" onclick="page_del(this);" value="'.$data->id.'" data-aid="'.$aid.'">Delete</button><div id="create_action_msg_'.$data->id.'"></div>';
						
						echo '
							<tr>
								<th class="cus_sln"><span>'.$j.'</span></th>
								<td class="cus_det" valign="">
									'.$data->name.'
								</td>
								<td class="cus_act" valign="top">'.$act.'</td>
							</tr>
						
						';
						
						$j++;
					}
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