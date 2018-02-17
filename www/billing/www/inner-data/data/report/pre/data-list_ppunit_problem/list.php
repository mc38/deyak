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
				$where .= " and p_consumerdata.bookno='".$bk."'";
			}
			if($tr !=""){
				$tr_arr = explode(',',$tr);
				$where_t = array();
				for($i=0;$i<sizeof($tr_arr);$i++){
					$where_t[]= "p_consumerdata.tariff_id like '".$tr_arr[$i]."%'";
				}
				$where_st = implode(" or ",$where_t);
				$where .= " and (".$where_st.")";
			}
			
			$query ="select p_consumerdata.id, p_consumerdata.bookno, p_consumerdata.cid, p_consumerdata.consumer_name, p_consumerdata.consumer_address, p_consumerdata.category_name, p_consumerdata.tariff_id, p_billdata.premeter_read, p_billdata.postmeter_read, p_billdata.ppunit from p_billdata inner join p_consumerdata on p_billdata.link=p_consumerdata.id where p_billdata.subdiv_id='".$s."' and p_billdata.mydate='".strtotime($sd)."' and p_billdata.status<>'' and p_billdata.status<1".$where;
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
				$i=0;
				while($d = mysql_fetch_object($q)){
					$pr = $d->premeter_read + $d->ppunit ;
					if((int)$pr >= (int)$d->postmeter_read ){
						$consumer_list[$d->bookno][]=$d;
						if(! in_array($d->bookno,$cbook)){
							$cbook[]=$d->bookno;
						}
						$i++;
					}
				}
				
				echo '<h4>Total no of data : </h4><h3>'. $i .'</h3>';
				echo '<hr/>';
				echo '<table border="1" style="border:1px solid #000; border-spacing:0px">';
				echo '	<tr><th>Slno</th>	<th>Book no</th>	<th>Consumer ID</th>	<th>Name &amp; Address</th>	<th>Tariff ID</th>		<th align="center">Unit</th></tr>';
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
						echo '<td>'. $data->cid .'</td>	<td><b>'. $data->consumer_name .'</b><br/>'.$data->consumer_address.'</td>	<td><b style="text-transform:capitalize;">'. $data->category_name .'</b><br/>'. $data->tariff_id .'</td>';
						$munit = $data->premeter_read + $data->ppunit;
						echo '<td align="right"><b style="float:left;">PREV</b> '. $data->premeter_read .' <b>+</b> '. $data->ppunit .'(PP) <b>=</b> '.$munit .'<hr/><b style="float:left;">CURR</b> '.$data->postmeter_read .'</td>';
						
						echo '</tr>';
						$j++;
					}
				}
				
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