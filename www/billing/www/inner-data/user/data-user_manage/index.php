<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/user/data-user_manage/";
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
                	<div class="head">User Details</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<tr>
                            	<td><label>Authority</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="authid">
                                		<option value="">Select Authority</option>
                                        <?php
										$where = ""; $accshow = false; $auth_arr = array();
										if($u >0){
											$accq = mysql_query("select access from zzuserdata where id='". $u ."'");
											$accd = mysql_fetch_object($accq);
											if($accd->access !=""){
												$accesslist = json_decode(base64_decode($accd->access));
												if(sizeof($accesslist)>0){
													$where = "where id in (". implode(',',$accesslist) .")";
													$accshow = true;
												}
											}
										}else{
											$accshow = true;
										}
										
										if($accshow){
											$aq = mysql_query("select * from zzauth ". $where);
											if(mysql_num_rows($aq) >0){
												while($ad = mysql_fetch_object($aq)){
													$auth_opt = '<option value="'. $ad->id .'">'. $ad->name .'</option>';
													$auth_arr[] = $auth_opt;
													echo $auth_opt;
												}
											}
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
                            	<td><label>Sub Division Name</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="subdivid">
                                        <?php
										$where = "";
										if($subdiv >0){
											$where = "where id='". $subdiv ."'";
										}else{
											echo '<option value="">All Sub Division</option>';
										}
										
										$sbq = mysql_query("select id,sid,name from settings_subdiv_data ".$where);
										if(mysql_num_rows($sbq) >0){
											while($sbd = mysql_fetch_object($sbq)){
												echo '<option value="'. $sbd->id .'">'. $sbd->name .' ('. $sbd->sid .')</option>';
											}
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
                            	<td><label>First Name</label></td>
                                <td class="med"></td>
                                <td><label>Last Name</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	
                                	<input id="user_fname" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="First Name" />
                                </td>
                                <td class="med"></td>
                                <td>
                                	<input id="user_lname" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Last Name" />
                                </td>
                            </tr>
							<tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <tr>
                            	<td><label>User Contact No</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="user_contact" type="text" autocomplete="off" spellcheck="false" placeholder="Type contact no" value="" /></td>
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
                                	<select id="user_sex">
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
                            	<td><label>Username</label></td>
                                <td class="med"></td>
                                <td></td>
                            </tr>
                            <tr>
                            	<td colspan="3">
                                	<input id="user_user" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Username" style="text-transform:none;" />
                                </td>
                            </tr>
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="create_system(this);">Create User</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Search User</div>
                    <div class="content">
                    	<table border="0">
                            <tr>
                            	<td><label>Authority</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="search_str">
                                		<option value="">Select Authority</option>
                                        <?php
										if($accshow){
											for($i=0;$i<sizeof($auth_arr);$i++){
												echo $auth_arr[$i];
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="2"><button type="button" style="width:120px; margin-left:0px;" onclick="list_search();">Search</button></td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
                
				
                <div class="sub-form">
                	<div class="head">User List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="print_report();">Print</button>
                    
                    <div class="content-spl" id="data_print">        
                           
                        
                        
                    	
                    	<table border="0" class="content-list content-list-head">
                        	<tr>
                            	<th class="cus_sln">Sl No</th>
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