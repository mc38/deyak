<?php

require_once("filter/type2.php");
//////////////////////////////////////////////////////////////////////////////////////////////
//var_dump($data);

echo '<div align="center" style="border-bottom:1px solid #000;"><h3>Monthly Reading data import report</h3></div>';
echo '<b>Report Date Time : </b>' .date('d-m-Y h:i:s a',$datetime).'<hr/>';
echo 'Total no of data = '.$total_data."<br/><br/>";
echo 'Accepted data = '.$accepted_data."<br/><br/><hr/>";

echo 'Total correct Consumer data = '.sizeof($consumer_list)."<br/><br/>";

echo '<hr/>Number of aborted Data = '.sizeof($aborted_data)."<br/><hr/>";

echo 'Consumer ID not registered = '.sizeof($consumer_error)."<br/><br/>";


/////////////////////////////////////////////////////////////////////////////////////////////


$error = sizeof($aborted_data) + sizeof($consumer_error) ;
if($error >0){
	echo '<h4>Number of errors <span style="color:red;"> '.$error.'</span></h4>';
}
else{
	echo '<h4><span style="color:green;"> No Error</span></h4>';
}

if(sizeof($consumer_list)>0){
	echo '<h3>Number of data to import <span style="color:green;"> '. sizeof($consumer_list) .'</span><br/>Clearify the errors and import the whole data or import <span style="color:blue;"> '. sizeof($consumer_list) .'</span> no of data</h3><hr/>';
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


/////////////////////////////////////////////////////////////////////////////////////////////

if(sizeof($consumer_error)>0  ){
	echo '<h3>Check the errors</h3><hr/>';
}

if(sizeof($consumer_error)>0){
	echo '<table border="1">';
	echo '<tr><td colspan="2"><b>Consumer not registered</b></td></tr>';
	for($i=0;$i<sizeof($consumer_error);$i++){
		$j = $i+1;
		echo '<tr><td valign="top">'. $j .'</td><td>';
		$table = $d[$consumer_error[$i]];
		var_dump($table);
		echo '</td></tr>';
	}
	echo '</table><br/><br/><hr/>';
}




?>