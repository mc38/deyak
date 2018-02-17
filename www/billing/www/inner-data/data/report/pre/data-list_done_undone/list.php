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
	$r = $data[4];
	
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
		
		$tr_arr = array();
		if($tr !=""){
			$tr_arr = explode(',',$tr);
			for($i=0;$i<sizeof($tr_arr);$i++){
				$trq = mysql_query("select id from consumer_cate where tariff_id like '%". base64_encode($tr_arr[$i]) ."%'");
				if(mysql_num_rows($trq) >0){
					$noerror = false;
					$msg = "<center>Tariff ID Invalid</center>";
					break;
				}
			}
		}
		
		if($noerror){
			
			$where = "";
			if($bk !=""){
				$where .= " and bookno='".$bk."'";
			}
			if($tr !=""){
				$tr_arr = explode(',',$tr);
				$where_t = array();
				for($i=0;$i<sizeof($tr_arr);$i++){
					$where_t[]= "tariff_id like '".$tr_arr[$i]."%'";
				}
				$where_st = implode(" or ",$where_t);
				$where .= " and (".$where_st.")";
			}
			
			$query ="select id,bookno,cid,consumer_name,consumer_address,category_name,tariff_id from p_consumerdata where subdiv_id='".$s."' and mydate='".strtotime($sd)."'".$where;
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
				
				$done =0;
				$undone =0;
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>	<th>Name &amp; Address</th>	<th>Tariff ID</th>	<th>Current Reading</th>	<th>Status</th></tr>';
				
				
				$j =1;
				for($i=0;$i<sizeof($cbook);$i++){
					
					$dlist = array();
					
					$bprint = true;	$x=0;
					for($ii=0;$ii<sizeof($consumer_list[$cbook[$i]]);$ii++){
						$data = $consumer_list[$cbook[$i]][$ii];
						
						$rdata ='';
						$sh = 0;
						
						$bq = mysql_query("select id,postmeter_read from p_billdata where link='".$data->id."' and status<>''");
						$creading = '-';
						if( mysql_num_rows($bq) <1){
							if($r ==0 || $r ==2){ 
								$rdata = '<td style="background:#98261A;" align="center"><b style="color:#fff;">Un Done</b></td>';
								$sh = 1;
								$undone ++;
							}
						}else{
							$bd = mysql_fetch_object($bq);						
							$creading = $bd->postmeter_read;
							
							if($r ==0 || $r ==1 ){
								$rdata = '<td style="background:#088D53;" align="center"><b style="color:#fff;">Done</b></td>';
								$sh = 1;
								$done ++;
							}else if($r == 3){
								$xq = mysql_query("select id from out_bill_xml where link='". $data->id ."' and down='1'");
								if(mysql_num_rows($xq) < 1){
									$rdata = '<td style="background:#FEC942;" align="center"><b style="color:#000;">no XML</b></td>';
									$sh = 1;
									$done ++;
								}
							}
						}
						
						if($sh == 1){
							$dlist[$x] = array($data,$rdata,$creading);
							$x++;
						}
					}
				
					
					if(sizeof($dlist) >0){
					
						for($x=0;$x<sizeof($dlist);$x++){
						
							echo '<tr>';
							echo '	<td>'.$j.'</td>';
							if($bprint){
								echo '<td valign="top" rowspan="'. sizeof($dlist) .'">'.$cbook[$i].'</td>';
								$bprint = false;
							}
							$data = $dlist[$x][0];
							
						
							echo '	<td>'. $data->cid .'</td>	<td><b>'. $data->consumer_name .'</b><br/>'.$data->consumer_address.'</td>	<td><b style="text-transform:capitalize;">'. $data->category_name .'</b><br/>'. $data->tariff_id .'</td><td>'. $dlist[$x][2].'</td>';
							echo $dlist[$x][1];
							echo '</tr>';
							
							$j++;
						}
					}
					
				}
				echo '</table>';
				echo '<hr/>';
				
				echo '<table style="font-size:22px; width:200px; border-bottom:1px solid #000;" border="0">';
				echo '	<tr><th style="color:#088D53;">Done</th>		<td align="right">'.$done .'</td>';
				echo '	<tr><th style="color:#98261A;">Un Done</th>	<td align="right">'.$undone .'</td>';
				$dutot = $done + $undone;
				echo '	<tr><th style="border-top:1px solid #000;">Total</th>		<td align="right" style="border-top:1px solid #000;">'.$dutot .'</td>';
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