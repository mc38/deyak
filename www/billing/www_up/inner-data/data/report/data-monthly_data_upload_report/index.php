<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/report/data-monthly_data_upload_report/";
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
                	<div class="head">Consumer Data</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<?php
							if($subdiv >0){
								echo '<input id="subdiv_id" type="hidden" value="'. $subdiv .'" />';
							}else{
								echo '
								<tr>
									<td><label>Sub-Division <span id="subdiv_lvl" style="color:#000;">ID</span></label></td>
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
								';
							}
                        	?>
                        
                        	
                            
                        	<tr>
                                <td><label>Bill Period <span>*</span></label></td>
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
											for($i=0;$i<3;$i++){
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
                            	<td><label>Category</label></td>
                                <td class="med"></td>
                                <td><label>DTR no</label></td>
                            </tr>
                            <tr>
                                <td>
                                	<select id="category">
                                    	<option value="">Select Category</option>
                                        <?php
										$cc_q = mysql_query("select id,name from settings_consumer_cate");
										while($cc_d = mysql_fetch_object($cc_q)){
											echo '<option value="'. $cc_d->name .'">'. $cc_d->name .'</option>';
										}
										?>
                                    </select>
                                </td>
                                <td class="med"></td>
                            	<td>
                                	<input id="dtr_no" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Here" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>DEYAK ID</label></td>
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
                                	<button type="button" style="width:120px; margin-left:0px; float:right;" onclick="list_search(this);">Export Excel</button>
                                    <button type="button" style="width:120px; margin-right:10px; float:right;" onclick="clear_field(this);">Clear</button>
                                    <div id="search_msg"></div>
                                </td>
                            </tr>
                            
                            
                        </table> 
                        
                    </div>
                </div>
				
                <form id="excel_download" action="download.php?t=1_3_1" method="post" target="_blank">
                	<input type="hidden" id="s" name="s" value="" />
                    <input type="hidden" id="d" name="d" value="" />
                    <input type="hidden" id="c" name="c" value="" />
                    <input type="hidden" id="b" name="b" value="" />
                    <input type="hidden" id="i" name="i" value="" />
                </form>
                
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    <script>
	function show_image_big(s){
		$("#big_i").attr("src",s);
		$("#big_image").css({"display":""});
	}
	function close_image(){
		$("#big_image").css({"display":"none"});
	}
	</script>
    <!------------------- Temporary ---------------------------->
    <div id="big_image" style="display:none; width:100%; height:100%; position:fixed; top:0px; left:0px; text-align:center; vertical-align:middle; background:rgba(0,0,0,0.5);">
    	<div style="margin-top:30px;">
    		<img id="big_i" src="" />
        </div>
        <div>
        	<button type="button" onclick="close_image();">Close</button>
        </div>
    </div>
    
    
</div></body>
</html>