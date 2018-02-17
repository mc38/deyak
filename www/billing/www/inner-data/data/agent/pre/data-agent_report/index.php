<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/agent/data-agent_report/";
?>
<link rel="stylesheet" type="text/css" href="<?php echo $link; ?>style/css/style.css" />

<script>var llink = "<?php echo $link; ?>";</script>
<script src="<?php echo $link ?>java/java.js"></script>
</head>

<body><div class="body" align="center">
    
    <div class="main-container">
    	
		<?php
			include "inner-data/data-control/index.php";
		?>
        <?php
		$sid = "";
		if(isset($_GET['s']) && $_GET['s'] !=""){
			$sid = $_GET['s'];
		}
		?>
        
    	<div class="main-panel">
        
        	
        	<div class="action-tab">
            <h2><?php echo $page_tag; ?></h2>
            </div>
            
            <div class="form-data">
            	
				
                
				
				<div id="detail_show" >
					<span id="tempdata" style="display:;"></span>
				
                
                
                
				
				
                <div class="sub-form">
                	<div class="head">Search Control</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
                        	<tr>
                            	<td><label>Sub-division ID <span>*</span></label></td>
                                <td class="med"></td>
                                <td><label>Date <span>*</span></label></td>
                            </tr>
                            <tr>
                            	<td><input id="search_str" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here" /></td>
                            	<td class="med"></td>
                                <td>
                                	<select id="search_month" style="width:49%;">
                                    	<option value="">Select Month</option>
                                        <?php
											$month=array("January","Fabruary","March","April","May","June","July","August","September","October","November","December");
											for($i=0;$i<sizeof($month);$i++){
												$j = $i+1;
												echo '<option value="'. $j .'">'.$month[$i].'</option>';
											}
										?>
                                    </select>
                                    
                                    <select id="search_year" style="width:49%;">
                                    	<option value="">Select Year</option>
                                        <?php
											date_default_timezone_set('Asia/Kolkata');
											$datetime=date($_SERVER['REQUEST_TIME']);
											$year = date('Y',$datetime)+1;
											for($i=0;$i<2;$i++){
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year--;
											}
										?>
                                    </select>
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            
                            <tr>
                            	<td colspan="3"><button type="button" style="width:120px; margin-left:0px; float:right;" onclick="list_search(this);">Search</button><div id="search_msg"></div></td>
                                <td></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Report</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="print_report();">Print</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        <div class="list-scroll stylist-scroll" id="data_list" style="text-align:left; overflow:scroll; font-size:14px; font-family:Arial, Helvetica, sans-serif;">
                        
                        </div>
                        
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>