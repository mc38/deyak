<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/consumer/data-reading_data_entry/";
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
                            	<td><label>Sub-Division <span id="subdiv_lvl" style="color:#000;">ID</span> <span>*</span></label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="subdiv_srch" type="text" autocomplete="off" spellcheck="false" placeholder="Type Subdiv ID and Check" value="" style="width:560px;" />
                                    <button type="button" id="subdiv_ch_but" style="width:120px; margin-left:7px; margin-right:0px; display:;" onclick="check_subdiv(this);">Check</button>
                                    <button type="button" id="subdiv_re_but" style="width:120px; margin-left:7px; margin-right:0px; display:none;" onclick="reset_subdiv(this);">Reset</button>
                                    <div id="create_subdiv_msg"></div>
                                </td>
                            </tr>
                            <input id="subdiv_id" type="hidden" value="" />
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                        	<tr>
                                <td><label>Date <span>*</span></label></td>
                                <td class="med"></td>
                            </tr>
                            <tr>
                                <td>
                                	<select id="search_month">
                                    	<option value="">Select Month</option>
                                        <?php
											$month=array("January","Fabruary","March","April","May","June","July","August","September","October","November","December");
											for($i=0;$i<sizeof($month);$i++){
												$j = $i+1;
												echo '<option value="'. $j .'">'.$month[$i].'</option>';
											}
										?>
                                    </select>
                                 </td>
                                 <td class="med"></td>
                                 <td>
                                    <select id="search_year">
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
                            	<td><label>Tariff ID</label></td>
                                <td class="med"></td>
                                <td><label>Book no</label></td>
                            </tr>
                            <tr>
                                <td>
                                	<input id="tariff" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Here" />
                                </td>
                                <td class="med"></td>
                            	<td>
                                	<input id="book_no" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Here" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>Year</label></td>
                                <td class="med"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                	<select id="fyear">
                                    	<option value="">Select Year</option>
										<?php
                                        ///year
                                        $to_year = date('Y',$datetime);
                                        for($i=0; $i<5; $i++){
                                            echo '<option value="'.$to_year.'">'.$to_year.'</option>';
                                            $to_year --;
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
                            	<td><label>Consumer Address</label></td>
                                <td class="med"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                	<input id="conadd" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Here (Part of String)" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>Consumer ID</label></td>
                                <td class="med"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                	<input id="conid" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Here (Last 6 digit)" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td colspan="3">
                                	<button type="button" style="width:120px; margin-left:0px; float:right;" onclick="list_search(this);">Search</button>
                                    <button type="button" style="width:120px; margin-right:10px; float:right;" onclick="clear_field(this);">Clear</button>
                                    <div id="search_msg"></div>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Report</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="print_report();">Print</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        <div class="list-scroll stylist-scroll" id="data_list" style="text-align:left; overflow:scroll; font-size:12px; font-family:Arial, Helvetica, sans-serif;">
                        
                        </div>
                        
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>