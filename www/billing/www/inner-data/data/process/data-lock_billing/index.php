<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/process/data-lock_billing/";
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
                	<div class="head">Search Consumer</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
                        	<tr>
                            	<td><label>Sub-division ID</label></td>
                                <td class="med"></td>
                                <td><label>Date</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	<?php
									if($subdiv >0){
										$subq = mysql_query("select name,sid from settings_subdiv_data where id=".$subdiv);
										$subd = mysql_fetch_object($subq);
										echo '
										<input type="text" autocomplete="off" spellcheck="false" value="'. $subd->name .'" disabled="disabled" />
										<input type="hidden" id="search_str" value="'. $subd->sid .'" />
										';
									}else{
										echo '
                                		<input id="search_str" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here" />
										';
									}
									?>
                              	</td>
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
											for($i=0;$i<3;$i++){
												echo '<option value="'.$year.'">'.$year.'</option>';
												$year--;
											}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="3"><button type="button" style="width:120px; margin-left:0px; float:right;" onclick="list_search(this);">Search</button><div id="search_msg"></div></td>
                                <td></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Information</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
                    <style>
                    button[disabled=disabled]{
                        box-shadow:none;
                        background:rgba(152,38,26,1);
                        cursor:default;
                    }
                    </style>
                    <div class="content-spl" id="data_print">        
                           
                        
                        <div class="list-scroll stylist-scroll" id="data_list" style="font-family:'Courier New', Courier, monospace;">
                        
                        	
                        
                        
                        </div>
                        
                    </div>
                </div>
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>