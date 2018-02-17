<?php
require_once("../../../../db/command.php");
if(
	isset($_GET['s']) && isset($_GET['d']) && isset($_GET['c'])
	&&
	$_GET['s']!="" && $_GET['d']!="" && $_GET['c']!=""
 )
{
	$q = mysql_query("select * from consumer_details where subdiv_id='". $_GET['s'] ."' and dtrno='". $_GET['d'] ."' and oldcid='". $_GET['c'] ."'");
	if(mysql_num_rows($q)>0){
			$d = mysql_fetch_object($q);

            $current_mydate = strtotime(date('Y-m-01',$datetime));
            $next_mydate    = strtotime(date('Y-m-01',strtotime("+45day",$current_mydate)));

            $mquery = "select id from m_data where c_mydate='". $current_mydate ."' and c_subdiv_id='". $_GET['s'] ."' and out_dtrno='". $_GET['d'] ."' and out_oldcid='". $_GET['c'] ."'";
            $mq = mysql_query($mquery);
            $mid = 0;
            if(mysql_num_rows($mq) ==1){
                $md = mysql_fetch_object($mq);
                $mid = $md->id;
            }

            $rquery = "select id from bill_reading where mydate='". $current_mydate ."' and conid='". $d->id ."' and mdid='". $mid ."'";
            $rq = mysql_query($rquery);
            $rid = 0;
            if(mysql_num_rows($rq) ==1){
                $rd = mysql_fetch_object($rq);
                $rid = $rd->id;
            }

            //echo $mquery ."<br/>". $rquery;

			echo '
			
							
                            <input type="hidden" id="i" value="'. $d->id .'" />
                            <input type="hidden" id="mi" value="'. $mid .'" />
                            <input type="hidden" id="ri" value="'. $rid .'" />
							
                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>Old Meter no</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <input id="en_o_meterno" type="text" value="" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                                <td></td>
                                <td class="med"></td>
                            </tr>
                            <!-- ------------------------------------------------------------ -->

                            <!-- ------------------------------------------------------------ -->
                            <tr>
                            	<td><label>New Meter no</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                            	<td colspan="3">
                                	<input id="en_n_meterno" type="text" value="" autocomplete="off" spellcheck="false" placeholder="Type here" />
                                </td>
                            </tr>
                            <tr class="gap">
                            	<td></td>
                                <td class="med"></td>
                            </tr>

                            <!-- ------------------------------------------------------------ -->
                            <tr>
                                <td><label>Update Effect From</label></td>
                                <td class="med"></td>
                            </tr>
                            <tr class="gap">
                                <td colspan="3">
                                    <select id="sm">
                                        <option value="">Select One</option>
                                        <option value="0">This Month</option>
                                        <option value="1">Next Month</option>
                                    </select>
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