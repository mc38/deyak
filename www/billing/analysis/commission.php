<form action="" method="post" enctype="multipart/form-data">
	Select csv file to upload:
	<input type="date" name="d">
	<input type="number" name="c" placeholder="Commission percentage">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<?php
$target_dir = "";
$target_file = $target_dir . "data.csv";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"]) && !empty($_POST['d']) && !empty($_POST['c'])) {
    $uploadOk = 1;
	if($imageFileType != "csv" ) {
	    echo "Sorry, only CSV files are allowed.";
	    $uploadOk = 0;
	}

	if ($uploadOk == 1) {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	    	$mydate = strtotime($_POST['d']);
	    	fileProcess($mydate,$_POST['c']);
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}

function fileProcess($md,$comm){
	$data = file("data.csv");
	$mdate = date('F-Y',$md);
	$i = 0;

	$total_arkipl_commission = 0;
	$total_iispl_commission = 0;
	echo '
	<table border="1">
		<tr>
			<th>Slno</th>
			<th>Conno</th>
			<th>Name</th>
			<th>Payment</th>
			<th>Commission</th>
			<th>ARKIPL Commission (Fixed + Dyna)</th>
			<th>IISPL Commission (Fixed + Dyna)</th>
		</tr>
	';

	$j = 1;
	foreach ($data as $line) {
		if($i>0){
			$array = explode(",", $line);
			$chdatearr = explode(" ", $array[7]);
			if($chdatearr[1]."-".$chdatearr[2] == $mdate){
				$payment = $array[8];
				$commission = ($payment * $comm)/100;

				$fixed_arkipl_com = 7;
				$fixed_iispl_com = 8;

				$dyna_arkipl_com = 0;
				$dyna_iispl_com = 0;

				if($commission<=15){
					$fixed_arkipl_com = ($commission * 7)/15;
					$fixed_iispl_com = ($commission * 8)/15;
				}else{
					$remain_c = $commission - 15;
					if($remain_c>15 && $remain_c<=30){
						$dyna_arkipl_com = ($remain_c * 8)/15;
						$dyna_iispl_com = ($remain_c * 7)/15;

					}else if($remain_c>30 && $remain_c<=50){
						$dyna_arkipl_com = ($remain_c * 19)/35;
						$dyna_iispl_com = ($remain_c * 16)/35;

					}else if($remain_c>50 && $remain_c<=75){
						$dyna_arkipl_com = ($remain_c * 33)/60;
						$dyna_iispl_com = ($remain_c * 27)/60;

					}else if($remain_c>75 && $remain_c<=100){
						$dyna_arkipl_com = ($remain_c * 47)/85;
						$dyna_iispl_com = ($remain_c * 38)/85;

					}else if($remain_c>100 && $remain_c<=200){
						$dyna_arkipl_com = ($remain_c * 102)/185;
						$dyna_iispl_com = ($remain_c * 83)/185;

					}else if($remain_c>200 && $remain_c<=500){
						$dyna_arkipl_com = ($remain_c * 267)/485;
						$dyna_iispl_com = ($remain_c * 218)/485;

					}else if($remain_c>500 && $remain_c<=2500){
						$dyna_arkipl_com = ($remain_c * 1367)/2485;
						$dyna_iispl_com = ($remain_c * 1118)/2485;

					}else{
						$dyna_arkipl_com = ($remain_c /2);
						$dyna_iispl_com = ($remain_c /2);

					}
				}

				$fixed_arkipl_com = round($fixed_arkipl_com, 2);
				$fixed_iispl_com = round($fixed_iispl_com, 2);
				$dyna_arkipl_com = round($dyna_arkipl_com, 2);
				$dyna_iispl_com = round($dyna_iispl_com, 2);

				$total_arkipl = $fixed_arkipl_com + $dyna_arkipl_com;
				$total_iispl = $fixed_iispl_com + $dyna_iispl_com;
				$total_com = $total_arkipl + $total_iispl;

				$total_arkipl_commission += $total_arkipl;
				$total_iispl_commission += $total_iispl;
				

				echo '
					<tr>
						<td>'. $j .'</td>
						<td>'. $array[0] .'</td>
						<td>'. $array[10] .'</td>
						<td>'. $payment .'</td>
						<td>'. $commission .'</td>
						<td>'. $fixed_arkipl_com .' + '. $dyna_arkipl_com .' = '. $total_arkipl .'</td>
						<td>'. $fixed_iispl_com .' + '. $dyna_iispl_com .' = '. $total_iispl .'</td>
					</tr>
				';
				$j++;
			}
		}
		$i++;
	}

	echo '
	</table>
	<hr/>
	<h2>ARKIPL commission = '. $total_arkipl_commission .'</h2>
	<h2>IISPL commission = '. $total_iispl_commission .'</h2>
	';
}

?>