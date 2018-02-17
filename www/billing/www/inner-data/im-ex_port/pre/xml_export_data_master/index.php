<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/im-ex_port/export_data_master/";
$ulink = $link;
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
            <h2>Master Export XML data</h2>
            </div>
            
            <div class="form-data">
            	
				
                
				
				<div id="detail_show" >
					<span id="tempdata" style="display:;"></span>
				
				<div class="sub-form">
                	<div class="head">Data Type Detail</div>
                    <div class="content">
                    	
                    	<table border="0">
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="data_type">
                                    	<option value="">Select One</option>
                                        <option value="1">Reading Data</option>
                                        <option value="2">Billing Data</option>
                                    </select>
                                </td>
                            </tr>
                            
                            
                        </table>
                    </div>
                </div>
				
                
                <div id="secondary_data" style="display:none">
                
                
                <div class="sub-form">
                	<div class="head">XML File Download</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td colspan="3">
                                	<input id="subdiv" type="text" autocomplete="off" spellcheck="false" placeholder="Enter Sub-Division ID" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>Date</label></td>
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
                                	<input id="tariff" type="text" autocomplete="off" spellcheck="false" placeholder="Data From" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                </td>
                                <td class="med"></td>
                                <td>
                                	<input id="book" type="text" autocomplete="off" spellcheck="false" placeholder="Data Total" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
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
                                	<input id="conid" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here (Last 6 Digit)" onkeydown="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                </td>
                            </tr>
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                        	<tr>
                            	<td align="center" colspan="3">
                                	<button type="button" id="file_download_but" style="margin:0;">Download</button>
                                    
                                   	<form id="download_form" action="?t=0_2_2" method="post" target="_blank">
                                    	<input type="hidden" name="n" id="d" value="" />
                                        <input type="hidden" name="d" id="sd" value="" />
                                        <input type="hidden" name="b" id="sdd" value="" />
                                        <input type="hidden" name="t" id="dt" value="" />
                                        <input type="hidden" name="o" id="db" value="" />
                                        <input type="hidden" name="c" id="dc" value="" />
                                    </form>
                                    
                                    
                                    <style>
                                    .loading_holder{
										width:100%;
										height:20px;
										border:1px solid #000;
										box-shadow:0px 0px 2px 1px rgba(0,0,0,0.3);
										border-radius:10px;
										text-align:left;
									}
									.loading{
										width:30%;
										height:100%;
										background:linear-gradient(rgba(152,38,26,0.6),rgba(152,38,26,1));
										border-radius:10px;
										float:left;
									}
                                    </style>
                                    
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
		        </div>             
                
                </div>
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>