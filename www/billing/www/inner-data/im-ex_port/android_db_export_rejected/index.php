<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/im-ex_port/android_db_export_rejected/";
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
                	<div class="head">Download from here</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
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
                            	<td><label>Agent</label></td>
                                <td class="med"></td>
                                <td><label>Date</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	<?php
									if($subdiv >0){
										
										echo '
											<select id="agent_id">
                                    			<option value="">Select Agent</option>
										';
										
                                		$aq = mysql_query("select id,name from agent_info where subdiv='". $subdiv ."' and status='0'");
										if(mysql_num_rows($aq)>0){
											while($ad = mysql_fetch_object($aq)){
												echo '<option id="'. $ad->id .'">'. $ad->name .'</option>';
											}
										}
										
										echo '
                                			</select>
										';
										
									}else{
										
										echo '
											<select id="agent_id">
                                    			<option value="">Select Agent</option>
                                			</select>
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
                            	<td colspan="3">
                                	<form id="frm_andown" action="?t=0_1_2" target="_blank" method="post">
                                    	<input type="hidden" name="s" value="" id="frm_s" />
                                        <input type="hidden" name="a" value="" id="frm_a" />
                                        <input type="hidden" name="d" value="" id="frm_d" />
                                    </form>
                                	<button type="button" style="width:120px; margin-left:0px; float:right;" onclick="download_start(this);">Download</button>
                                    <div id="search_msg"></div>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                
                
		        </div>             
                
                
                
                
            </div>
        </div>
        
        
    </div>
    
    
    
</div></body>
</html>