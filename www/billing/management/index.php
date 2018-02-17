<?php
session_start();
include "../db/command.php";
require_once("../plugin/func/authentication.php");

if($u = authenticate()){

echo '
<!DOCTYPE html>
<html>
';

	include "inner/h.php";

echo '
<body>

	<div class="body"  align="center">
';

		include "inner/head.php";

echo '
		<div>
			<span class="head_text">Management Report and Analysis section</span>
		</div>
		
		<div class="but_container">

			<div class="ip_box">
				<form action="report_1.php"><button type="submit">Report 1 - Expected Unit Deviation</button></form>
			</div>
			<div class="ip_box">
				<form action="report_2.php"><button type="submit">Report 2 - List of Back Reading</button></form>
			</div>
			<div class="ip_box">
				<form action="report_3.php"><button type="submit">Report 3 - DTR wise Bill amount</button></form>
			</div>
			<div class="ip_box">
				<form action="report_agriculture.php"><button type="submit">Agriculture Consumer Report</button></form>
			</div>
			<div class="ip_box">
				<form action="report_amount.php"><button type="submit">Amount Details</button></form>
			</div>
			<div class="ip_box">
				<form action="report_4.php"><button type="submit">Report 4 - Analysis of Total Arrear</button></form>
			</div>
			<div class="ip_box">
				<form action="report_5.php"><button type="submit">Report 5 - DTR wise bill breakup</button></form>
			</div>
			<div class="ip_box">
				<form action="report_6.php"><button type="submit">Report 6 - DTR wise Meter Status breakup</button></form>
			</div>
			<div class="ip_box">
				<form action="report_ledger.php"><button type="submit">Ledger Report (MRI)</button></form>
			</div>
			<div class="ip_box">
				<form action="report_7.php"><button type="submit">Report 7 - Consumption Summary</button></form>
			</div>
			<div class="ip_box">
				<form action="report_8.php"><button type="submit">Report 8 - Management Meter Status Analysis</button></form>
			</div>
			<div class="ip_box">
				<form action="report_9.php"><button type="submit">Report 9 - Agent wise Meter Status breakup</button></form>
			</div>
			<div class="ip_box">
				<form action="report_10.php"><button type="submit">Billing Cycle wise Meter Status Analysis</button></form>
			</div>

		</div>
	</div>
</body>
</html>
';
}else{
echo '
	<!DOCTYPE html>
	<html>
	<head>
		<title>Management Report and Analysis Section</title>
	    <link rel="stylesheet" href="style/style.css" type="text/css">
	</head>
	<body>

		<div class="body"  align="center">
			<div class="heading">
	            <div class="logo_container">
	                <i class="logo"></i>
	                <span class="logo_content">Deyak</span>
	            </div>
			</div>
			<div>
				<span class="head_text">Management Report and Analysis Section</span>
			</div>
			<br/><br/>
			<div class="form_container">
				<span style="color: red; font-size:36px; ">UNAUTHORIZED ACCESS</span>
				<div class="img"></div>
				<div class="ip_box" style="float:none;">
					<form target="" action="../"><button type="submit">Login</button></form>
				</div>
			</div>
	</body>
	</html>
';
}