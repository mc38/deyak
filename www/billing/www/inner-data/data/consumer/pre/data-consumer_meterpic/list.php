<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$ai= $data[2];
	$bk= $data[3];
	
	$subdq = mysql_query("select id from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
	
		
		$booklist = array();
		$bkq = mysql_query("select book from booklist where subdiv='".$s."'");
		if(mysql_num_rows($bkq)>0){
			while($bkd = mysql_fetch_object($bkq)){
				$booklist[]=$bkd->book;
			}
		}
		
		$noerror = true; $msg ="";
		if($bk !="" && !in_array($bk,$booklist)){
			$noerror = false;
			$msg = "<center>Book no Invalid</center>";
		}
		
		if($noerror){
			
			$where = "";
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			
			if($ai !=""){
				$where .= " and aid='".$ai."'";
			}
			
			$query ="select id,bookno,status,postmeter_read,meterpic,fmeterno,link,aid from p_billdata where status<>'' and subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
			$q = mysql_query($query);
			
			if(mysql_num_rows($q) >0){
				echo 'Subdivision ID : '. $s .'';
				echo '<hr/>';
				echo 'Month : '. date('F, Y', strtotime($sd)) .'';
				echo '<hr/>';
				
				if($bk !=""){
					echo 'Book no : '. $bk .'';
					echo '<hr/>';
				}
				
				$cbook = array();
				$consumer_list = array();
				while($d = mysql_fetch_object($q)){
					$consumer_list[$d->bookno][]=$d;
					if(! in_array($d->bookno,$cbook)){
						$cbook[]=$d->bookno;
					}
				}
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>	<th>Name</th>	<th>Agent</th>	<th>Actual Field Meter No</th>	<th>Reading</th>	<th>Meter Pic</th></tr>';
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					$bprint = true;
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						echo '<tr>';
						echo '<td>'.$j.'</td>';
						if($bprint){
							echo '<td valign="top" rowspan="'. sizeof($consumer_list[$cbook[$i]]) .'">'.$cbook[$i].'</td>';
							$bprint = false;
						}
						$data = $consumer_list[$cbook[$i]][$ii];
						
						$cq = mysql_query("select cid,consumer_name from p_consumerdata where id='".$data->link."'");
						$cd = mysql_fetch_object($cq);
						
						echo '<td>'. $cd->cid .'</td>	<td>'. $cd->consumer_name .'</td>';
						
						$aq = mysql_query("select name from agent_info where id='".$data->aid."'");
						$ad = mysql_fetch_object($aq);
						$aname = json_decode(base64_decode($ad->name));
						echo '<td style="text-transform:capitalize;">'. $aname[0] .' '. $aname[1] .'</td>';
						echo '<td>'. $data->fmeterno .'</td>';
						
						$reading ="";
						if($data->status == '0'){
							$reading = $data->postmeter_read;
						}else if($data->status == '1'){
							$reading = "Door Closed";
						}else if($data->status == '2'){
							$reading = "Meter Tempered";
						}else if($data->status == '3'){
							$reading = "Meter Stopped";
						}
						echo '<td>'.$reading.'</td>';
						echo '<td><img style="width:100px; height:100px;" src="data:image/jpeg;base64,'. $data->meterpic .'" /></td>';
						
						echo '</tr>';
						$j++;
					}
				}
				echo '</table>';
			}
			else{
				echo '<center><h3 style="color:red;">Empty List</h3></center>';
			}
		}
		else{
			echo '<center><h3 style="color:red;">'.$msg.'</h3></center>';;
		}
	}
	else{
		echo '<center><h3 style="color:red;">Invalid subdivision</h3></center>';
	}
}
else{
	echo "Unauthorized user";
}
?>