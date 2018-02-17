<?php 
require_once("plugin/func/authentication.php");
if($u=authenticate()){
	
	if(((isset($_GET['t']) && $_GET['t'] == '1_3_1'))){
		include "inner-data/data/report/data-monthly_data_upload_report/list.php";
	}
}
	

?>