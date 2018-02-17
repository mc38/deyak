<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/agent/data-agent_registration/";
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
                	<div class="head">Agent Details</div>
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
                            	<td><label>Agent PIN</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="agentpin" type="text" autocomplete="off" spellcheck="false" placeholder="Generate 4 digit Agent PIN clicking the Generate Button" value="" readonly="readonly" style="width:560px;" />
                                    <button type="button" style="width:120px; margin-left:7px; margin-right:0px;" onclick="putagentid();">Generate</button>
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>Agent&acute;s Mobile IMEI no of first SIM slot</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="agent_imei" type="text" autocomplete="off" spellcheck="false" placeholder="Type IMEI No (Dial *#06# to get IMEI no)" value="" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            
                        	<tr>
                            	<td><label>First Name</label></td>
                                <td class="med"></td>
                                <td><label>Last Name</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	
                                	<input id="agent_fname" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="First Name" />
                                </td>
                                <td class="med"></td>
                                <td>
                                	<input id="agent_lname" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Last Name" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <tr>
                            	<td><label>Agent Contact No</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="agent_contact" type="text" autocomplete="off" spellcheck="false" placeholder="Type contact no" value="" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>Gender</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="agent_sex">
                                    	<option value="">Select Gender</option>
                                        <option value="0">Male</option>
                                        <option value="1">Female</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="create_system(this);">Create Agent</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
				
                <div class="sub-form">
                	<div class="head">Search Agent</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
                        	<tr>
                            	<td><label>Agent name</label></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><input id="search_str" type="text" autocomplete="off" spellcheck="false" placeholder="Type Here" /></td>
                            </tr>
                            <tr>
                            	<td colspan="2"><button type="button" style="width:120px; margin-left:0px;" onclick="list_search();">Search</button></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Agent List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_search();">Refresh</button>
                    <button type="button" style="width:120px; margin-left:0px;" onclick="print_report();">Print</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        
                        
                    	
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
                            	<th class="cus_date">Date</th>
                                <th class="cus_det">Detail</th>
                                <th class="cus_act">Action</th>
                            </tr>
                        </table>
                        <div class="list-scroll stylist-scroll">
                        <table border="0" class="content-list" id="agent_list"> 
                        	
                            <!--
                        	<tr>
                            	<th class="cus_sln"><span>1</span></th>
                               	<td class="cus_date" valign="top">07-08-2014<br />08:10 PM</td>
                                <td class="cus_det" valign="top">
                                	<b>Chief Complain : </b>fever<br />
                                    <b>Disease : </b>ABCD<br />
                                </td>
                                <td class="cus_act" valign="top"><button type="button">View</button></td>
                         	</tr>
                            -->
                            
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