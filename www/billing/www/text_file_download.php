<?php
$file_name = "temp.txt";

date_default_timezone_set('Asia/Kolkata');
$datetime=date($_SERVER['REQUEST_TIME']);

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=apdcl_upload_'. date('Y-m-d_h-i-s-a',$datetime) .'.txt');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_name));
readfile($file_name);
exit;
?>