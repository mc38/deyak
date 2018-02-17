<?php
require_once("../../../../db/command.php");
if(
	isset($_GET['s']) && isset($_GET['d']) && isset($_GET['c'])
	&&
	$_GET['s']!="" && $_GET['d']!="" && $_GET['c']!=""
 )
{
	$q = mysql_query("select * from in_data_queue where subdivision_id='". $_GET['s'] ."' and dtr_no='". $_GET['d'] ."' and consumer_no='". $_GET['c'] ."' and status='0'");
	if(mysql_num_rows($q)>0){
			$d = mysql_fetch_object($q);
	
			echo '
			
							
                            <input type="hidden" id="i" value="'. $d->id .'" />
							
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>3 -> Old Consumer No</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_oldcon" type="text" value="'. $d->old_consumer_no .'" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>5 -> Consumer Name</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_conname" type="text" value="'. $d->consumer_name .'" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>6 -> Consumer Address</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<textarea id="en_conaddress" type="text" value="" autocomplete="off" spellcheck="false" placeholder="Type here" style="position:relative;">'. $d->	consumer_address .'</textarea>
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>7 -> Meter no</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_meterno" type="text" value="'. $d->meter_no .'" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>8 -> Connected Load (KW)</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_connload" type="text" value="'. $d->connected_load .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>9 -> Multiplying Factor</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <input id="en_mf" type="text" value="'. $d->multiplying_factor .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>
                        	<!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>10 -> Category</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<select id="en_category">
                                    	<option value="">Select Category</option>
                 ';
										$cc_q = mysql_query("select id,name from settings_consumer_cate");
										while($cc_d = mysql_fetch_object($cc_q)){
											$se = '';
											if($cc_d->id == $d->consumer_category_code){
												$se = 'selected="selected"';
											}
											
											echo '<option value="'. $cc_d->id .'" '. $se .'>'. $cc_d->name .'</option>';
										}
				echo '
                                    </select>
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>11 -> Meter Type</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <select id="en_metertype">
                                        <option value="">Select Meter Type</option>
                ';
                                        
                                        $mt_q = mysql_query("select id,name from settings_meter_cate");
                                        while($mt_d = mysql_fetch_object($mt_q)){
                                            $se = '';
                                            if($mt_d->id == $d->meter_type){
                                                $se = 'selected="selected"';
                                            }
                                            echo '<option value="'. $mt_d->id .'" '. $se .'>'. $mt_d->name .'</option>';
                                        }
                                        
                echo '                                      
                                    </select>
                                </td>
                            </tr>
                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>12 -> Previous Reading</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_prevreading" type="text" value="'. $d->previous_reading .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="number_only(this.value,this.id);" onkeypress="number_only(this.value,this.id);" onkeyup="number_only(this.value,this.id);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>13 -> Previous Bill Date</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_prevbilldate" type="date" value="'. $d->previous_bill_date .'" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>15 -> Principal Arrear</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_parrear" type="text" value="'. $d->principle_arrear .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>16 -> Arrear Surcharge</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_arrsurchrg" type="text" value="'. $d->arrear_surcharge .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>18 -> Adjustment</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_adjust" type="text" value="'. $d->adjustment .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>19 -> Average Unit</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <input id="en_avgunit" type="text" value="'. $d->avg_unit .'" autocomplete="off" spellcheck="false" placeholder="Type here" onkeydown="check_amount(this,this.value);" onkeypress="check_amount(this,this.value);" onkeyup="check_amount(this,this.value);" />
                                </td>
                            </tr>
                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>20 -> Due Date</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <input id="en_duedate" type="date" value="'. date("Y-m-d",strtotime($d->due_date)) .'" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->
                            
                            <!-- ------------------------------------------------------------ -->
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><div id="create_system_msg"></div></td>
                                <td class="med"></td>
                                <td align="right"><button type="button" style="width:200px; margin-left:0px; margin-right:0px;" onclick="update_cate(this);">Update</button></td>
                            </tr>
                            
				';
	}
	else{
		echo '<tr><td>No data exists for editing</td></tr>';
	}
}
?>