<?php
require_once("filter/type1.php");

//////////////////////////////////////////////////////////////////////////////////////////////

echo '<div align="center" style="border-bottom:1px solid #000;"><h3>One time Consumer data import report</h3></div>';
echo '<b>Report Date Time : </b>' .date('d-m-Y h:i:s a',$datetime).'<hr/>';
echo 'Total no of data = '.$total_data."<br/><br/>";
echo 'Accepted data = '.$accepted_data."<br/><br/><hr/>";

echo 'No of Consumer ID = '.sizeof($nconsumer_list)."<br/><br/>";

echo '<hr/>Number of aborted Data = '.sizeof($aborted_data)."<br/><hr/>";

echo 'New Sub-Division ID = '.sizeof($nsubdiv_list)."<br/><br/>";
echo 'New Meter Type = '.sizeof($nmetertype_list)."<br/><br/>";
echo 'New Tariff ID = '.sizeof($ntarrif_id_list)."<br/><br/><hr/>";
echo 'New Tariff Area = '.sizeof($ntarrif_area_list)."<br/><br/><hr/>";


/////////////////////////////////////////////////////////////////////////////////////////////


$error = $total_data - sizeof($nconsumer_list);
if($error >0){
	echo '<h4>Number of errors <span style="color:red;"> '.$error.'</span></h4>';
}
else{
	echo '<h4><span style="color:green;"> No Error</span></h4>';
}

if(sizeof($nconsumer_list)>0){
	echo '<h3>Number of data to import <span style="color:green;"> '. sizeof($nconsumer_list) .'</span><br/>Clearify the errors and import the whole data or import <span style="color:blue;"> '. sizeof($nconsumer_list) .'</span> no of data</h3><hr/>';
}
else{
	echo '<h4><span style="color:red;">No data</span> is remaining for importation</h4><br/><hr/>';
}

//////////////////////////////////////////////////////////////////////////////////////////////

if($error >0){
	echo '<h4>Number of errors <span style="color:red;"> '.$error.'</span></h4><br/><br/><hr/>';
}
else{
	echo '<h4><span style="color:green;"> No Error</span></h4><br/><br/><hr/>';
}


//////////////////////////////////////////////////////////////////////////////////////////

if(sizeof($nsubdiv_list)>0 || sizeof($nmetertype_list)>0 || sizeof($ntarrif_id_list)>0 ){
	echo '<h3>Get the Details and Manually enter the data first</h3><hr/>';
}

if(sizeof($nsubdiv_list)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Sub-Division</b></td></tr>';
	for($i=0;$i<sizeof($nsubdiv_list);$i++){
		$j = $i+1;
		echo '<tr><td>'.$j.'</td>	<td>'.$nsubdiv_list[$i].'</td>	<td>'.$subdiv_name[$nsubdiv_list[$i].""].'</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}

if(sizeof($nmetertype_list)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Meter Type</b></td></tr>';
	for($i=0;$i<sizeof($nmetertype_list);$i++){
		$j = $i+1;
		echo '<tr><td>'.$j.'</td>	<td>'.$nmetertype_list[$i].'</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}

if(sizeof($ntarrif_id_list)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Tariff ID</b></td></tr>';
	for($i=0;$i<sizeof($ntarrif_id_list);$i++){
		$j = $i+1;
		$dshow = json_decode(base64_decode($ntarrif_id_list[$i]));
		$ac = $dshow[1];
		$dshowd = $dshow[0] .", ".$tariff_area_name[$ac]." ";
		echo '<tr><td>'.$j.'</td>	<td>'.$dshowd.'</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}

if(sizeof($ntarrif_area_list)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Tariff Area Code</b></td></tr>';
	for($i=0;$i<sizeof($ntarrif_area_list);$i++){
		$j = $i+1;
		echo '<tr><td>'.$j.'</td>	<td>'.$ntarrif_area_list[$i].'</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}

if(sizeof($aborted_data)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Aborted Data</b></td></tr>';
	for($i=0;$i<sizeof($aborted_data);$i++){
		$j = $i+1;
		echo '<tr><td valign="top">'.$j.'</td>	<td>';
		var_dump($d[$aborted_data[$i]]);
		echo '</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}
?>