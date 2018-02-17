<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/settings/data-meter_cate/";
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
                	<div class="head">Meter Category</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<tr>
                            	<td><label>Category Name</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="mtr_cate" type="text" autocomplete="off" spellcheck="false" placeholder="Type Meter Category" value="" style="text-transform:uppercase;" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                        	
                            <tr>
                            	<td colspan="3"><label>Meter Rent</label></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><input id="mtr_rent" type="text" autocomplete="off" spellcheck="false" placeholder="Type Meter Rent" value="" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>Phase</label></td>
                                <td class="med"></td>
                                <td><label>Meter Category Type</label></td>
                            </tr>
                            <tr class="gap">
                            	<td>
                                	<select id="mtr_phase">
                                    	<option value="">Select Phase</option>
                                        <option value="1">1 Phase</option>
                                        <option value="3">3 Phase</option>
                                    </select>
                               	</td>
                                <td class="med"></td>
                                <td><input id="mtr_code" type="text" autocomplete="off" spellcheck="false" placeholder="Type Meter type code" value="" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" /></td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="create_cate(this);">Create Category</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
				
                <div class="sub-form">
                	<div class="head">Search Category</div>
                    <div class="content">
                    	<table border="0">
                        	<tr>
                            	<td><div class="errormsg" id="errormsg" style="display:none;"></div></td>
                            </tr>
                        	<tr>
                            	<td><label>Meter Category</label></td>
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
                	<div class="head">Meter Category List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
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
                        <table border="0" class="content-list" id="meter_list"> 
                        	
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
    
    
    
    <div class="cover">
    	<div class="cover-main">
        
        </div>
    </div>
</div></body>
</html>