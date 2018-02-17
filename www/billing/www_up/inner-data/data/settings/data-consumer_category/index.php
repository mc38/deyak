<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<?php
$link="inner-data/data/settings/data-consumer_category/";
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
                	<div class="head">Category Details</div>
                    <div class="content">
                    	
                    	<table border="0">
                        	<tr>
                            	<td><label>Category Name</label></td>
                                <td class="med"></td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="cceta_name" type="text" autocomplete="off" spellcheck="false" placeholder="Type Category Name" value="" />
                                </td>
                            </tr>
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                        	<tr>
                                <td colspan="3"><label>Code</label></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                	<input id="cceta_mtriff" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Category Code" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" maxlength="5" style="width:561px;" />
                                    
                                    <button type="button" id="tariff_ch_but" style="width:120px; margin-left:7px; margin-right:0px;" onclick="check_tariff(this);">Check</button>
                                    <button type="button" id="tariff_re_but" style="display:none; width:120px; margin-left:7px; margin-right:0px;" onclick="reset_tariff(this);">Reset</button>
                                    <div id="create_tariff_msg"></div>
                                </td>
                            </tr>
                            <input type="hidden" id="mtariff" value="" />
                            <input type="hidden" id="mtariff_d" value="" />
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><label>Electricity Duty Rupees per unit</label></td>
                                <td class="med"></td>
                                <td><label>Surcharge Percentage</label></td>
                            </tr>
                            <tr class="gap">
                            	<td>
                                	<input id="cceta_eduty" type="text" autocomplete="off" spellcheck="false" placeholder="Type Amount in Rupees" value="" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                                <td class="med"></td>
                                <td>
                                	<input id="cceta_schrg" type="text" autocomplete="off" spellcheck="false" placeholder="Type percentage (accepted upto 2 decimal)" value="" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr>
                            	<td><label>FPPPA Charge Rate per unit</label></td>
                                <td class="med"></td>
                                <td></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="cceta_fppa" type="text" autocomplete="off" spellcheck="false" placeholder="Type Amount in Rupees" value="" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            
                            <tr class="gap">
                            	<td colspan="3"><hr /></td>
                            </tr>
                            
                            <tr>
                            	<td><input type="radio" name="sd" value="0" checked="checked" onclick="slab_type(this);" /><label>Simple Slab</label></td>
                                <td class="med"></td>
                                <td><input type="radio" name="sd" value="1" onclick="slab_type(this);" /><label>Complex Slab</label></td>
                            </tr>
                            
                            <!-- --------------------------------- -->
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><hr /></td>
                            </tr>
                            
                        	<tr>
                            	<td colspan="3">
                                    <table border="0" id="slab_spl" style="display:none;">
                                        <tr>
                                            <td><label>Meter Slab Reading From</label></td>
                                            <td class="med"></td>
                                            <td><label>Meter Slab Reading To</label></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input id="cceta_spl_mslabf" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type unit from" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                            </td>
                                            <td class="med"></td>
                                            <td>
                                                <input id="cceta_spl_mslabt" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type unit to" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label>Meter Slab Amount in rupees</label></td>
                                            <td class="med"></td>
                                            <td><label>Govt Subsidy</label></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input id="cceta_spl_mamnt" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Meter slab Amount" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                            </td>
                                            <td class="med"></td>
                                            <td>
                                                <input id="cceta_spl_msubsidy" type="text"  autocomplete="off" spellcheck="false" value="0" placeholder="Type Subsidy" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label>Fixed Charge</label></td>
                                            <td class="med"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>
                                            	<input id="cceta_spl_fchrg" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Fixed Charge" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                            </td>
                                            <td class="med"></td>
                                            <td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="right"><button type="button" style="width:80px; margin-left:0px; margin-right:0px;" onclick="add_slab_spl_data();">Add</button></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="content-spl">
                                                    <style>
                                                    .cus_mamnt_spl{
                                                        width:20% !important;
                                                    }
                                                    </style>
                                                    <table border="0" class="content-list content-list-head">
                                                        <tr>
                                                            <th class="cus_sln">Sl No</th>
                                                            <th class="cus_rslab">Meter Slab Reading</th>
                                                            <th class="cus_mamnt_spl" align="center">Meter Slab Amount</th>
                                                            <th class="cus_mamnt_spl" align="center">Fixed Charge</th>
                                                            <th class="cus_mamnt_spl" align="center">Subsidy</th>
                                                            <th class="cus_act">Action</th>
                                                        </tr>
                                                    </table>
                                                    <div class="list-scroll stylist-scroll" style="height:70px;">
                                                        <table border="0" class="content-list" id="slab_spl_data"> 
                                                            
                                                            <!--
                                                            <tr>
                                                                <th class="cus_sln"><span>1</span></th>
                                                                <td class="cus_rslab" valign="top">75</td>
                                                                <td class="cus_mamnt_spl" valign="top">3.50</td>
                                                                <td class="cus_mamnt_spl" valign="top">15.00</td>
                                                                <td class="cus_act" valign="top"><button type="button">Del</button></td>
                                                            </tr>
                                                            -->
                                                            
                                                        </table>
                                                    </div>
                                                
                                                </div>
                                            </td>
                                            
                                        </tr>
                                    </table>
                            	</td>
                            </tr>
                            <!-- --------------------------------- -->
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><hr /></td>
                            </tr>
                            
                        	<tr>
                            	<td><label>Meter Slab Reading From</label></td>
                                <td class="med"></td>
                                <td><label>Meter Slab Reading To</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	<input id="cceta_mslabf" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Unit From" onkeyup="number_only(this.value,this.id);" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" />
                                </td>
                                <td class="med"></td>
                                <td>
                                	<input id="cceta_mslabt" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Unit To (For balance unit leave blank)" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr>
                            	<td><label>Meter Slab Amount in rupees</label></td>
                                <td class="med"></td>
                            	<td><label>Govt Subsidy</label></td>
                            </tr>
                            <tr>
                            	<td>
                                	<input id="cceta_mamnt" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type slab amount" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            	<td class="med"></td>
                            	<td>
                                	<input id="cceta_msubsidy" type="text"  autocomplete="off" spellcheck="false" value="0" placeholder="Type subsidy amount" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr>
                            	<td><label>Fixed Charge</label></td>
                                <td class="med"></td>
                            	<td></td>
                            </tr>
                            <tr>
                            	<td>
                                	<input id="cceta_fchrg" type="text"  autocomplete="off" spellcheck="false" value="" placeholder="Type Fixed Charge" onkeyup="check_amount(this,this.value);" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" />
                                </td>
                            	<td class="med"></td>
                            	<td>
                                </td>
                            </tr>
                            <tr>
                            	<td colspan="3" align="right"><button type="button" style="width:80px; margin-left:0px; margin-right:0px;" onclick="add_slab_data();">Add</button></td>
                            </tr>
                            <tr>
                           		<td colspan="3">
                                 	<div class="content-spl">
                                    	<style>
										.cus_mamnt_spl{
											width:20% !important;
										}
										</style>
                                        <table border="0" class="content-list content-list-head">
                                            <tr>
                                                <th class="cus_sln">Sl No</th>
                                                <th class="cus_rslab">Meter Slab Reading</th>
                                                <th class="cus_mamnt_spl" align="center">Meter Slab Amount</th>
                                                <th class="cus_mamnt_spl" align="center">Fixed Charge</th>
                                                <th class="cus_mamnt_spl" align="center">Subsidy</th>
                                                <th class="cus_act">Action</th>
                                            </tr>
                                        </table>
                                        <div class="list-scroll stylist-scroll" style="height:200px;">
                                            <table border="0" class="content-list" id="slab_data"> 
                                                
                                                <!--
                                                <tr>
                                                    <th class="cus_sln"><span>1</span></th>
                                                    <td class="cus_rslab" valign="top">75</td>
                                                    <td class="cus_mamnt_spl" valign="top">3.50</td>
                                                    <td class="cus_mamnt_spl" valign="top">15.00</td>
                                                    <td class="cus_act" valign="top"><button type="button">Del</button></td>
                                                </tr>
                                                -->
                                                
                                            </table>
                                        </div>
                                    
                                    </div>
                            	</td>
                                
                            </tr>
                            <tr class="gap">
                            	<td colspan="3"><hr /></td>
                            </tr>
                            
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="create_category(this);">Create Category</button></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="sub-form">
                	<div class="head">Category List</div>
                    
                    <button type="button" style="width:120px; margin-left:0px;" onclick="list_load();">Refresh</button>
                    
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