<?php
ini_set('max_execution_time', 10000);
require_once("../../../../db/command.php");
require_once("../../../../plugin/func/authentication.php");

if(authenticate()){
	
	$gdata = base64_decode($_GET['s']);
	$data = json_decode($gdata);
	
	$s = $data[0];
	$sd= $data[1];
	$rd= $data[2];
	$cd= $data[3];
	
	$col_arr = array();
	$row_arr = array();
	
	////// type arr 
	{
		///book no
		$type_code = 0;
		$booklist_q = mysql_query("select book from booklist where subdiv='".$s."'");
		while($booklist_d = mysql_fetch_object($booklist_q)){
			if($rd == $type_code){
				$row_arr[]=$booklist_d->book;
			}else if($cd == $type_code){
				$col_arr[]=$booklist_d->book;
			}
		}
		
		///tariff id
		$type_code = 1;
		$tariff_q = mysql_query("select tariff_id from consumer_cate");
		while($tariff_d = mysql_fetch_object($tariff_q)){
			$tariff_dd = json_decode(base64_decode($tariff_d->tariff_id));
			$tariff_id = $tariff_dd[0];
			
			if($rd == $type_code){
				if(! in_array($tariff_id,$row_arr)){
					$row_arr[]=$tariff_id;
				}
			}else if($cd == $type_code){
				if(! in_array($tariff_id,$col_arr)){
					$col_arr[]=$tariff_id;
				}
			}
		}
		
		///category
		$type_code = 2;
		$cate_q = mysql_query("select name from consumer_cate");
		while($cate_d = mysql_fetch_object($cate_q)){
			$cname = $cate_d->name;
			if($rd == $type_code){
				if(! in_array($cname,$row_arr)){
					$row_arr[]=$cname;
				}
			}else if($cd == $type_code){
				if(! in_array($cname,$col_arr)){
					$col_arr[]=$cname;
				}
			}
		}
		
		///year
		$type_code = 3;
		$yr_q = mysql_query("select premeter_read_date from p_billdata where subdiv_id='".$s."' and mydate='". strtotime($sd) ."' order by premeter_read_date");
		$yr_d = mysql_fetch_object($yr_q);
		$from_year = date('Y',strtotime($yr_d->premeter_read_date));
		$to_year = date('Y',$datetime);
		for($i=$from_year; $i<=$to_year; $i++){
			if($rd == $type_code){
				$row_arr[]=$i."";
			}else if($cd == $type_code){
				$col_arr[]=$i."";
			}
		}
	}
	
	
	$type_arr = array("Book no","Tariff ID","Category","Year");
	$type_tbl = array("p_consumerdata","p_consumerdata","p_consumerdata","p_billdata");
	$type_col = array("bookno","tariff_id","category_name","premeter_read_date");
	
	$subdq = mysql_query("select id from subdiv_data where sid='".$s."'");
	if(mysql_num_rows($subdq)==1){
		
		echo 'Subdivision ID : '. $s .'';
		echo '<hr/>';
		echo 'Month : '. date('F, Y', strtotime($sd)) .'';
		echo '<hr/>';
		
		$row_name = $type_arr[$rd];
		$col_name = $type_arr[$cd];
		
		echo '<table border="1" style="border:1px solid #000; border-spacing:0px; text-transform:capitalize;">';
		echo '	<tr><th align="center">'.$col_name.'<br/><font style="font-size:8px;">vs</font><br/>'.$row_name.'</th>';
		for($i=0;$i<sizeof($col_arr);$i++){
			echo '	<th align="center">'. $col_arr[$i] .'</th>';
		}
		echo '	<th align="center">Total</th>';
		echo '	</tr>';
		
		$fdata = array("p_consumerdata.id");
		if($rd<3){	$fdata[]= "p_consumerdata.". $type_col[$rd];	}else{	$fdata[]= "p_billdata.". $type_col[$rd];	}
		if($cd<3){	$fdata[]= "p_consumerdata.".$type_col[$cd];		}else{	$fdata[]= "p_billdata.".$type_col[$cd];		}
		$fdata_str = implode(',',$fdata);
		
		$join_str ="";
		if($rd>2 || $cd>2){
			$join_str = " inner join p_billdata on p_consumerdata.id=p_billdata.link";
		}
		
		$query = "select ". $fdata_str ." from p_consumerdata". $join_str ." where p_consumerdata.mydate='". strtotime($sd) ."'";
		$q = mysql_query($query);
		
		if(mysql_num_rows($q) >0){
			$d_arr = array_fill(0,sizeof($row_arr),array_fill(0,sizeof($col_arr),0));
			while($d = mysql_fetch_object($q)){
				
				//////////////row////////////////
				$rdata = $d->$type_col[$rd];
				if($rd ==1){
					$rdata = substr($rdata,0,5);
				}else if($rd ==3){
					$rdata = substr($rdata,7,4);
				}
				
				//////////////col////////////////
				$cdata = $d->$type_col[$cd];
				if($cd ==1){
					$cdata = substr($cdata,0,5);
				}else if($cd ==3){
					$cdata = substr($cdata,7,4);
				}
				
				//////////////////////////////
				$r_index = array_search($rdata,$row_arr);
				$c_index = array_search($cdata,$col_arr);
				
				$d_arr[$r_index][$c_index] = $d_arr[$r_index][$c_index] +1;
			}
			
			$tot = array_fill(0,sizeof($col_arr),0);
			
			for($r=0;$r<sizeof($row_arr);$r++){
				$dtot =0;
				
				echo '	<tr>';
				echo '		<th>'. $row_arr[$r] .'</th>';
				
				for($c = 0;$c<sizeof($col_arr);$c++){
					echo '	<td align="right">'. $d_arr[$r][$c] .'</td>';
					$dtot = $dtot + $d_arr[$r][$c];
					$tot[$c] = $tot[$c] + $d_arr[$r][$c];
				}
				echo '	<td align="right">'. $dtot .'</td>';
				echo '	</tr>';
				
			}
			
			{
				$dtot =0;
				echo '	<tr>';
				echo '		<th>Total</th>';
				for($c = 0;$c<sizeof($tot);$c++){
					echo '	<td align="right">'. $tot[$c] .'</td>';
					$dtot = $dtot + $tot[$c];
				}
				echo '	<td align="right">'. $dtot .'</td>';
				echo '	</tr>';
			}
			
			echo '</table>';
			
		
		}
		else{
			echo '<center><h3 style="color:red;">Empty Data</h3></center>';
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