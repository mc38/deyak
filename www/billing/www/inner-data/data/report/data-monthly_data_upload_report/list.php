<?php
ini_set('max_execution_time', 10000);
require_once("db/command.php");
require_once("../config/config.php");
require_once("plugin/func/authentication.php");
require_once("plugin/libs/excel/Classes/PHPExcel.php");
require_once('plugin/libs/excel/Classes/PHPExcel/IOFactory.php');

if(authenticate()){
	
	
	if(
		isset($_POST['s']) && $_POST['s'] !=""
		&&
		isset($_POST['d']) && $_POST['d'] !=""
		&&
		isset($_POST['c']) 
		&&
		isset($_POST['b']) 
		&&
		isset($_POST['i']) 
	){
		
		
		$subdiv_id 	= $_POST['s'];
		$mydate		= $_POST['d'];
		$category 	= $_POST['c'];
		$dtrno 		= $_POST['b'];
		$conid		= $_POST['i'];
		
		$where = "";
		if($dtrno !=""){
			$where .= " and out_dtrno='".$dtrno."'";
		}
		
		if($conid !=""){
			$where .= " and out_cid like '%".$conid."'";
		}
		
		if($category !=""){
			$where .= " and out_consumer_category = '".$category."'";
		}
		
		$subq = mysql_query("select sid from settings_subdiv_data where id='". $subdiv_id ."'");
		$subd = mysql_fetch_object($subq);
		
		$q = mysql_query("select * from m_data where c_subdiv_id='". $subd->sid ."' and c_mydate='". strtotime($mydate) ."' and in_status<>''". $where);
		if(mysql_num_rows($q) >0){
			
			//Excel
			
			/**
			 * PHPExcel
			 *
			 * Copyright (C) 2006 - 2013 PHPExcel
			 *
			 * This library is free software; you can redistribute it and/or
			 * modify it under the terms of the GNU Lesser General Public
			 * License as published by the Free Software Foundation; either
			 * version 2.1 of the License, or (at your option) any later version.
			 *
			 * This library is distributed in the hope that it will be useful,
			 * but WITHOUT ANY WARRANTY; without even the implied warranty of
			 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
			 * Lesser General Public License for more details.
			 *
			 * You should have received a copy of the GNU Lesser General Public
			 * License along with this library; if not, write to the Free Software
			 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
			 *
			 * @category   PHPExcel
			 * @package    PHPExcel
			 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
			 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
			 * @version    1.7.9, 2013-06-02
			 */
			
			/** Error reporting */
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			
			define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
			
			// Create new PHPExcel object
			//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
			$objPHPExcel = new PHPExcel();
			
			// Set document properties
			//echo date('H:i:s') , " Set document properties" , EOL;
			$objPHPExcel->getProperties()->setCreator("ARKIPL")
										 ->setLastModifiedBy("Mickel Chowdhury")
										 ->setTitle("Data upload report of ". date('F, Y',strtotime($mydate)))
										 ->setSubject("Data upload report of ". date('F, Y',strtotime($mydate)))
										 ->setDescription("Data upload report of ". date('F, Y',strtotime($mydate)))
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Report");
			
			
			$objPHPExcel->setActiveSheetIndex(0);
			
			$colval[]="A"; $colval[]="B"; $colval[]="C"; $colval[]="D"; $colval[]="E"; $colval[]="F"; $colval[]="G"; $colval[]="H"; $colval[]="I"; $colval[]="J"; $colval[]="K"; $colval[]="L"; $colval[]="M"; $colval[]="N"; $colval[]="O"; $colval[]="P"; $colval[]="Q"; $colval[]="R"; $colval[]="S"; $colval[]="T"; $colval[]="U"; $colval[]="V"; $colval[]="W"; $colval[]="X"; $colval[]="Y"; $colval[]="Z"; $colval[]="AA"; $colval[]="AB"; $colval[]="AC"; $colval[]="AD"; $colval[]="AE"; $colval[]="AF"; $colval[]="AG"; $colval[]="AH"; $colval[]="AI"; $colval[]="AJ"; $colval[]="AK"; $colval[]="AL"; $colval[]="AM"; $colval[]="AN"; $colval[]="AO"; $colval[]="AP"; $colval[]="AQ"; $colval[]="AR"; $colval[]="AS"; $colval[]="AT"; $colval[]="AU"; $colval[]="AV"; $colval[]="AW";
			
			
			$th = array();
			$th[] = "Slno";
			$th[] = "Status";
			$th[] = "Bill month";
			$th[] = "Consumer ID";
			$th[] = "Old Consumer no";
			$th[] = "Consumer Name";
			$th[] = "Category";
			$th[] = "Prev Reading Date";
			$th[] = "Prev Reading";
			$th[] = "Curr Reading Date";
			$th[] = "Curr Reading";
			$th[] = "Meter Status";
			$th[] = "Unit consumed";
			$th[] = "M-Factor";
			$th[] = "Unit billed";
			$th[] = "Consumption Day";
			$th[] = "Energy Charge";
			$th[] = "Subsidy";
			$th[] = "Total Energy Charge";
			$th[] = "Fixed Charge";
			$th[] = "Electricity Duty";
			$th[] = "Meter Rent";
			$th[] = "FPPA Charge";
			$th[] = "Current Demand";
			$th[] = "Principle Arrear";
			$th[] = "Arrear Surcharge";
			$th[] = "Current Surcharge";
			$th[] = "Total Arrear";
			$th[] = "Adjustment";
			$th[] = "Net Bill Amount";
			$th[] = "Due Date";
			$th[] = "Net Bill Amount After Due Date";
			
			
			//excel header
			for($i=0;$i<count($th);$i++){
				$objPHPExcel->getActiveSheet()->setCellValue($colval[$i].'3', $th[$i]);
			}
			
			//excel body
			$tot=0;$i=4;
			while($d = mysql_fetch_object($q)){
				$td = array();
				$td[] = $i -3;
				$status = "";
				if($d->c_import_status == 0){
					$status = "Waiting";
				}else{
					if($d->c_pass_status == 0){
						$status = "Queue for verification";
					}else if($d->c_pass_status == 1){
						$status = "Accepted";
					}else if($d->c_pass_status == 2){
						$status = "Rejected";
					}
				}
				$td[] = $status;
				$td[] = date('F, Y',strtotime($mydate));
				$td[] = " ". $d->out_cid;
				$td[] = " ". $d->out_oldcid;
				$td[] = $d->out_consumer_name;
				$td[] = $d->out_consumer_category;
				if($d->out_premeter_read_date != ""){
					$td[] = date('d-m-Y',$d->out_premeter_read_date);
				}else{
					$td[] = "";
				}
				$td[] = $d->out_premeter_read;
				if($d->in_reading_date != ""){
					$td[] = date('d-m-Y',$d->in_reading_date);
				}else{
					$td[] = "";
				}
				$td[] = $d->in_postmeter_read;
				$td[] = $meter_status[$d->in_status];
				
				
					$td[] = $d->in_unit_consumed;
					$td[] = $d->out_mfactor;
					$td[] = $d->in_unit_billed;
					$td[] = $d->in_consumption_day;
					$td[] = number_format((float) $d->in_energy_amount,2,'.','');
					$td[] = number_format((float) $d->in_subsidy,2,'.','');
					$td[] = number_format((float) $d->in_total_energy_charge,2,'.','');
					$td[] = number_format((float) $d->in_fixed_charge,2,'.','');
					$td[] = number_format((float) $d->in_electricity_duty,2,'.','');
					$td[] = number_format((float) $d->in_meter_rent,2,'.','');
					$td[] = number_format((float) $d->in_fppa_charge,2,'.','');
					$td[] = number_format((float) $d->in_current_demand,2,'.','');
					$td[] = number_format((float) $d->out_principal_arrear,2,'.','');
					$td[] = number_format((float) $d->out_arrear_surcharge,2,'.','');
					$td[] = number_format((float) $d->in_current_surcharge,2,'.','');
					$td[] = number_format((float) $d->in_total_arrear,2,'.','');
					$td[] = number_format((float) $d->out_adjustment,2,'.','');
					$td[] = number_format((float) $d->in_net_bill_amount,2,'.','');
					if($d->in_due_date != ""){
						$td[] = date('d-m-Y',$d->in_due_date);
					}else{
						$td[] = "";
					}
					$td[] = number_format((float) $d->in_net_bill_amount_after_duedate,2,'.','');
				
				
				for($j=0;$j<count($th);$j++){
					$objPHPExcel->getActiveSheet()->setCellValue($colval[$j].$i, $td[$j]);
					$tot++;
				}
				$i++;
			}
			
			//excel header
			
			
			$objRichText = new PHPExcel_RichText();
			$objPayable = $objRichText->createTextRun("Data upload report of ". date('F, Y',strtotime($mydate)));
			$objPayable->getFont()->setBold(true);
			$objPayable->getFont()->setSize(16);
			$objPayable->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
			$objPHPExcel->getActiveSheet()->getCell('A1')->setValue($objRichText);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			
			$objPHPExcel->getActiveSheet()->mergeCells('A1:'. $colval[count($th)-1] .'2');
			
			//$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			//$objPHPExcel->getActiveSheet()->getProtection()->setPassword(date('dmY',$mydate);
			//$objPHPExcel->getActiveSheet()->protectCells('A1:'.$colval[count($th)-1].(($tot/count($th))+3), 'PHPExcel');
			
			for($i=0;$i<count($th);$i++){
				$objPHPExcel->getActiveSheet()->getColumnDimension($colval[$i])->setAutoSize(true);
			}
			
			for($i=0;$i<count($th);$i++){
				$objPHPExcel->getActiveSheet()->getStyle($colval[$i].'3')->getFont()->setSize(11);
				$objPHPExcel->getActiveSheet()->getStyle($colval[$i].'3')->getFont()->setBold(true);
			}
			
			
			$filename="data_upload_report_of_". date('F_Y',strtotime($mydate));
			
			/** Include PHPExcel_IOFactory */
			// Redirect output to a client's web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			
			// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
			
			
			
			
		}else{
			echo '<div align="center">No Data Found</div>';
		}
	}
	else{
		echo '<div align="center">Invalid Data</div>';
	}
}
else{
	echo '<div align="center">Unauthorized user</div>';
}
?>