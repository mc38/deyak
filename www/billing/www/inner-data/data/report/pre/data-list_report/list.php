<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$bk= $data[2];
	$tr= $data[3];
	
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
		
		if($tr !=""){
			$trq = mysql_query("select id from consumer_cate where tariff_id like '%". base64_encode($tr) ."%'");
			if(mysql_num_rows($trq) >0){
				$noerror = false;
				$msg = "<center>Tariff ID Invalid</center>";
			}
		}
		
		if($noerror){
			
			$where = "";
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			if($tr !=""){
				$where .= " and tariff_id like '".$tr."%'";
			}
			$query ="select bookno,cid,consumer_name,tariff_id from p_consumerdata where subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
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
				
				if($tr !=""){
					echo 'Tariff ID : '. $tr .'';
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
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>	<th>Name</th>	<th>Tariff ID</th></tr>';
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
						echo '<td>'. $data->cid .'</td>	<td>'. $data->consumer_name .'</td>	<td>'. $data->tariff_id .'</td>';
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