<?php
ini_set('max_execution_time', 10000);
require_once("db/command.php");
require_once("plugin/func/authentication.php");

if(authenticate()){
	if($_POST){
	if(
		isset($_POST['s']) && $_POST['s']!="" 
		&& 
		isset($_POST['a']) && $_POST['a']!="" 
		&& 
		isset($_POST['d']) && $_POST['d']!="" 
	){
		
		$subdiv = $_POST['s'];
		$aid	= $_POST['a'];
		$d = $_POST['d'];
		
		$mydate = strtotime("1-".$d);
		
		$booklist = array();
		$bookq = mysql_query("select book from booklist where subdiv='". $subdiv ."'");
		while($bookd = mysql_fetch_object($bookq)){
			$booklist[]=$bookd->book;
		}
		
		$aq = mysql_query("select id,agent_pin,name,subdiv from agent_info where id='".$aid."' and status='0'");
		if(mysql_num_rows($aq) ==1){
			
			$bcode = 1;
			$bq = mysql_query("select id from agent_info where subdiv='".$subdiv."' order by id");
			if(mysql_num_rows($bq) >0){
				while($bd = mysql_fetch_object($bq)){
					if($bd->id == $aid ){
						break;
					}
					$bcode++;
				}
			}
			$bcode = $bcode ."";
			if(strlen($bcode) <2){
				$bcode = "0".$bcode;
			}
			
			$query_p = "select * from payment_setting where subdiv='".$subdiv."'";
			$pq = mysql_query($query_p);
			$pd = mysql_fetch_object($pq);
			$ctype = $pd->ctype;
			$cdata = $pd->cdata;
			
			
			$bq = mysql_query("select id from p_billdata where mydate='". $mydate ."' and subdiv_id='".$subdiv."'");
			if(mysql_num_rows($bq) >0){
				
				
				if(! file_exists("temp/")){
					mkdir("temp");
				}
				$fname = "tsecldb_". date('d-m-Y_h-i-s_a',$datetime);
				$file = "temp/". $fname;
				
				////////////////////////////////////////////////////////////////////////////////////////
				
				$db = new PDO("sqlite:" . $file .".db");
				
				$query_agent_struct = '
				CREATE TABLE IF NOT EXISTS `appdata` (
				  `aid` text NOT NULL,
				  `pin` text NOT NULL,
				  `user_detail` text NOT NULL,
				  `bcode` text NOT NULL,
				  `mydate` text NOT NULL,
				  `subdiv` text NOT NULL,
				  `ctype` text NOT NULL,
				  `cdata` text NOT NULL
				)
				';
				$db->exec($query_agent_struct);
				
				$ad = mysql_fetch_object($aq);
				$aname_arr = json_decode(base64_decode($ad->name));
				$aname = strtoupper($aname_arr[0] ." ". $aname_arr[1]);
				$myd = date('d-m-Y',$mydate);
				$subd = $ad->subdiv;
				
				$db->exec("insert into appdata(aid,pin,user_detail,bcode,mydate,subdiv,ctype,cdata) values('". $ad->id ."','". $ad->agent_pin ."','". $aname ."','". $bcode ."','". $myd ."','". $subd ."','". $ctype ."','". $cdata ."')");
				
				$query_consumerdata_struct = '
				CREATE TABLE IF NOT EXISTS `consumerdata` (
				  `cid` text NOT NULL,
				  `bookno` text NOT NULL,
				  `meterno` text NOT NULL,
				  `consumer_name` text NOT NULL,
				  `consumer_address` text NOT NULL,
				  `phase` text NOT NULL,
				  `cload` text NOT NULL,
				  `load_unit` text NOT NULL,
				  `tariff_id` text NOT NULL,
				  `category_name` text NOT NULL,
				  `meter_rent` text NOT NULL,
				  `slab` text NOT NULL,
				  `mfactor` text NOT NULL,
				  `subdiv` text NOT NULL
				)
				';
				$db->exec($query_consumerdata_struct);
				
				$query_billdata_struct = '
				CREATE TABLE IF NOT EXISTS `billdata` (
				  `cid` text NOT NULL,
				  `billno` text,
				  `premeter_read` text NOT NULL,
				  `premeter_read_date` text NOT NULL,
				  `status` text,
				  `postmeter_read` text,
				  `meterpic` text,
				  `reading_date` text,
				  `credit` text NOT NULL,
				  `ppunit` text NOT NULL,
				  `last_receipt` text NOT NULL,
				  `pend_bill` text NOT NULL,
				  `reserve_unit` text NOT NULL,
				  `fmeterno` text,
				  `link` text NOT NULL,
				  `aid` text
				)
				';
				$db->exec($query_billdata_struct);
				
				
				$query_billxml_struct = '
				CREATE TABLE IF NOT EXISTS `out_bill_xml` (
				  `consumer_id` text NOT NULL,
				  `subdivision_id` text NOT NULL,
				  `book_no` text NOT NULL,
				  `tariff_id` text NOT NULL,
				  `bill_from_datetime` text NOT NULL,
				  `bill_to_datetime` text NOT NULL,
				  `bill_datetime` text NOT NULL,
				  `bill_generate_datetime` text NOT NULL,
				  `bill_due_datetime` text NOT NULL,
				  `previous_reading` text NOT NULL,
				  `current_reading` text NOT NULL,
  				  `billed_unit` text NOT NULL,
				  `energy_charge` text NOT NULL,
				  `fixed_charge` text NOT NULL,
				  `meter_rent` text NOT NULL,
				  `other_charge` text NOT NULL,
				  `diseal_charge` text NOT NULL,
				  `fuel_charge_rate` text NOT NULL,
				  `fuel_charge` text NOT NULL,
				  `gross_charge` text NOT NULL,
				  `rebate_charge` text NOT NULL,
				  `credit_adjustment` text NOT NULL,
				  `net_charge` text NOT NULL,
				  `old_ec` text NOT NULL,
				  `old_uc` text NOT NULL,
				  `sundry` text NOT NULL,
				  `n_rate` text NOT NULL,
				  `bill_no` text NOT NULL,
				  `energy_charge_breakup` text NOT NULL,
				  `link` text NOT NULL
				)
				';
				$db->exec($query_billxml_struct);
				
				$query_readingxml_struct = '
				CREATE TABLE IF NOT EXISTS `out_reading_xml` (
				  `consumer_id` text NOT NULL,
				  `subdivision_id` text NOT NULL,
				  `book_no` text NOT NULL,
				  `bill_from_datetime` text NOT NULL,
				  `bill_to_datetime` text NOT NULL,
				  `previous_reading` text NOT NULL,
				  `current_reading` text NOT NULL,
				  `unit_consumed` text NOT NULL,
				  `reading_date` text NOT NULL,
				  `remarks` text NOT NULL,
				  `multiplying_factor` text NOT NULL,
				  `ppunit` text NOT NULL,
				  `link` int(11) NOT NULL
				)
				';
				$db->exec($query_readingxml_struct);
				
				$query_payment_struct = '
				CREATE TABLE IF NOT EXISTS `payment_data` (
				  `mydate` text NOT NULL,
				  `subdiv` text NOT NULL,
				  `cid` text NOT NULL,
				  `ackno` text NOT NULL,
				  `datetime` text NOT NULL,
				  `amount` text NOT NULL,
				  `commission` text NOT NULL,
				  `aid` text NOT NULL,
				  `prints` text NOT NULL
				)
				';
				$db->exec($query_payment_struct);
				
				
				$db->exec("pragma synchronous = off;");
				
				//////////////////////////////////////////////////////////////////////////////
				for($i=0;$i<sizeof($booklist);$i++){
					
					$bill_book_q = mysql_query("select * from p_billdata where mydate='". $mydate ."' and subdiv_id='".$subdiv."' and bookno='".$booklist[$i]."'");
					if(mysql_num_rows($bill_book_q) >0){
						$bdata = "";		$cdata ="";
					
						while($bill_book_d = mysql_fetch_object($bill_book_q)){
							
							$cq = mysql_query("select * from p_consumerdata where mydate='". $mydate ."' and subdiv_id='". $subdiv ."' and id='". $bill_book_d->link ."'");
							if(mysql_num_rows($cq) ==1){
								$cd = mysql_fetch_object($cq);
							
								////////////////////////////////////////////////////////////////////////////////////////////
								
								$ccol = array();					$cval = array();
								
								$ccol[0]="cid";						$cval[0]=$cd->$ccol[0];
								$ccol[1]="bookno";					$cval[1]=$cd->$ccol[1];
								$ccol[2]="meterno";					$cval[2]=$cd->$ccol[2];
								$ccol[3]="consumer_name";			$cval[3]=$cd->$ccol[3];
								$ccol[4]="consumer_address";		$cval[4]=$cd->$ccol[4];
								$ccol[5]="phase";					$cval[5]=$cd->$ccol[5];
								$ccol[6]="cload";					$cval[6]=$cd->$ccol[6];
								$ccol[7]="load_unit";				$cval[7]=$cd->$ccol[7];
								$ccol[8]="tariff_id";				$cval[8]=$cd->$ccol[8];
								$ccol[9]="category_name";			$cval[9]=$cd->$ccol[9];
								$ccol[10]="meter_rent";				$cval[10]=$cd->$ccol[10];
								$ccol[11]="slab";					$cval[11]=$cd->$ccol[11];
								$ccol[12]="mfactor";				$cval[12]=$cd->$ccol[12];
								$ccol[13]="subdiv";					$cval[13]=$cd->$ccol[13];
								
								$ccol_str = implode(',',$ccol);		$cval_str = implode("','",$cval);
								
								/////////////////////////////////////////////////////////////////////////////////////////////
								$bcol = array();					$bval = array();
								
								$bcol[0]= "cid";					$bval[0]=$bill_book_d->$bcol[0];
								$bcol[1]= "billno";					$bval[1]=$bill_book_d->$bcol[1];
								$bcol[2]= "premeter_read";			$bval[2]=$bill_book_d->$bcol[2];
								$bcol[3]= "premeter_read_date";		$bval[3]=$bill_book_d->$bcol[3];
								$bcol[4]= "status";					$bval[4]=$bill_book_d->$bcol[4];
								$bcol[5]= "postmeter_read";			$bval[5]=$bill_book_d->$bcol[5];
								$bcol[6]= "meterpic";				$bval[6]=$bill_book_d->$bcol[6];
								$bcol[7]= "reading_date";			$bval[7]=$bill_book_d->$bcol[7];
								$bcol[8]= "credit";					$bval[8]=$bill_book_d->$bcol[8];
								$bcol[9]= "ppunit";					$bval[9]=$bill_book_d->$bcol[9];
								$bcol[10]= "last_receipt";			$bval[10]=$bill_book_d->$bcol[10];
								$bcol[11]= "pend_bill";				$bval[11]=$bill_book_d->$bcol[11];
								$bcol[12]= "reserve_unit";			$bval[12]=$bill_book_d->$bcol[12];
								$bcol[13]= "link";					$bval[13]=$bill_book_d->id;
								$bcol[14]= "aid";					$bval[14]=$bill_book_d->$bcol[14];
								
								$bcol_str = implode(',',$bcol);		$bval_str = implode("','",$bval);
								
								/////////////////////////////////////////////////////////////////////////////////////////////
								$cdata .= "insert into consumerdata(". $ccol_str .") values('". $cval_str ."');";
								$bdata .= "insert into billdata(". $bcol_str .") values('". $bval_str ."');";
								
							}
						
						}
						
						$db->exec($cdata);
						$db->exec($bdata);
					}
				}
				
				$db = null;
				
				//////////////////////file download//////////////////////////////////////////////////////////////
				
				$fhandle = fopen("temp/". $fname .".php",'w');
				$dir = dirname(__FILE__);
				$dbcode = file_get_contents($dir."/dbcode.txt");
				file_put_contents("temp/". $fname .".php",$dbcode);
				fclose($fhandle);
				
				echo '<script>function ddown(){window.location.href = "temp/'.$fname.'.php?fname='.$fname.'";}</script><body onload="ddown();"></body>';
				
				
			}
			else{
				echo 'No data available for download';
			}
		}
		else{
			echo 'Agent is not available';
		}
	}
	else{
		echo 'Error';
	}
	}
	else{
		echo 'Error';
	}
}
else{
	echo "Unauthorized user";
}
?>