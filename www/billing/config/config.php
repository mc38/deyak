<?php
$metertype = array();
$metertype[0]= "";
$metertype[1]= "Digital";
$metertype[2]= "Analog";


$consumertype = array();
$consumertype[0]= "";
$consumertype[1]= "Individual";
$consumertype[2]= "Governmental Office";
$consumertype[3]= "Private Office";
$consumertype[4]= "Industry";
$consumertype[5]= "Other";

$migration_batchno = 10;

//meter status
$meter_status = array();
$meter_status[0] = "Meter OK";
$meter_status[1] = "Faulty Meter";
$meter_status[2] = "Meter Locked";
$meter_status[3] = "Meter Overflow";
$meter_status[4] = "Meter Changed";
$meter_status[5] = "Meter Stopped";
$meter_status[6] = "Meter Missing";
$meter_status[7] = "Reading not taken";
$meter_status[8] = "Alloted";
$meter_status[9] = "Not Alloted";

//file link
//$snapshot_link_snapshot_report = "http://serverhost/deyak/electricity/apdcl/billing/file/file.php";
$snapshot_link_snapshot_report = "http://192.168.0.2/srwww/deyak/electricity/apdcl/billing/file/file.php";

?>