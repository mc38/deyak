function reset_subdiv(doc){
	$("#secondary_data").css({"display":"none"});
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	list_search();
}

function check_subdiv(doc){
	
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_srch").removeAttribute("disabled");
	var sid = document.getElementById("subdiv_srch").value;
	if( sid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [sid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/ccheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID doesnot exists");
				}
				else if(res == "2"){
					show_msg(doc,"create_subdiv_msg","#98261A","Un-authenticated Subdiv ID");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"create_subdiv_msg","#135400","Successful");
						document.getElementById("subdiv_id").value = rd_arr[1];
						$(doc).css({"display":"none"});
						$("#subdiv_re_but").css({"display":""});
						document.getElementById("subdiv_srch").setAttribute("disabled","disabed");
						document.getElementById("subdiv_srch").value = rd_arr[2];
						document.getElementById("subdiv_lvl").innerHTML = "Name";
						
						list_search();
						$("#secondary_data").css({"display":""});
						
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}

///////////////////agent/////////////////////////////////
function putagentid(){
	document.getElementById("agentpin").value = generate_code(4);
}


function create_system(doc){
	var subdiv_id = document.getElementById("subdiv_id").value;
	var systemc = document.getElementById("agentpin").value;
	var imei = document.getElementById("agent_imei").value;
	
	var fname = document.getElementById("agent_fname").value;
	var lname = document.getElementById("agent_lname").value;
	
	var contact = document.getElementById("agent_contact").value;
	var sex = document.getElementById("agent_sex").value;
	
	if(subdiv_id !="" && systemc !="" && fname !="" && lname !="" && contact !="" && sex !=""){
		$("#create_system_msg").css({"color":"#000099"});
		document.getElementById("create_system_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [subdiv_id,systemc,fname,lname,contact,sex,imei];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to create?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/add_new.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						show_msg(doc,"create_system_msg","#135400","Successful");
						blank_systemdetail();
					}
					else if(response == '0'){
						show_msg(doc,"create_system_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_system_msg","#98261A","Problem with sub-division data");
					}
					else if(response == '2'){
						show_msg(doc,"create_system_msg","#98261A","IMEI no already registered");
					}
					else{
						show_msg(doc,"create_system_msg","#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,"create_system_msg","#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,"create_system_msg","#98261A","");
		}
	}
	else{
		show_msg(doc,"create_system_msg","#98261A","Fill up all the fields");
		
	}
}



function blank_systemdetail(){
	document.getElementById("agentpin").value = "";
	document.getElementById("agent_imei").value = "";
	
	document.getElementById("agent_fname").value ="";
	document.getElementById("agent_lname").value ="";
	
	document.getElementById("agent_contact").value ="";
	document.getElementById("agent_sex").value ="";
}


////////////////////////////block and unblock//////////////////////////////////////////

function agnt_block(doc){
	var aid = doc.value;
	
	if(aid !=""){
		var msgid = "create_action_msg_"+aid;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to block this agent?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/block.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						show_msg(doc,msgid,"#135400","Successful");
						list_search();
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,msgid,"#98261A","Agent ID not exists");
					}
					else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,msgid,"#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,msgid,"#98261A","");
		}
	}
}

////////////////////////////backdoor//////////////////////////////////////////

function agnt_backdoor(doc){
	var aid = doc.value;
	
	if(aid !=""){
		var msgid = "create_action_msg_"+aid;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to proceed?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/backdoor.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						show_msg(doc,msgid,"#135400","Successful");
						list_search();
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,msgid,"#98261A","Agent ID not exists");
					}
					else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,msgid,"#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,msgid,"#98261A","");
		}
	}
}




////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
		var w = window.open();
		w.document.write(d);
		w.document.getElementsByTagName("table").item(0).style.display="none";
		w.document.getElementsByTagName("table").item(1).border="1";
		w.document.getElementsByTagName("table").item(1).width="100%";
		w.print();
		w.close();
	}
}

////////////List///////////

$(function(){
	list_search();
})


function list_search(){
	var str = document.getElementById("search_str").value;
	var sd = document.getElementById("subdiv_id").value;
	
	if(sd !=""){
		var sdata = [sd,str];
		var str_s = $.base64.encode(JSON.stringify(sdata));
		
		scrollpos_get();
		$("#agent_list").html('<tr><td>Loading...</td></tr>');
		$("#agent_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
	}else{
		$("#agent_list").html('');
	}
}


// supporting functions
function show_msg(doc,id,color,msg){
	$("#"+id).css({"color":color});
	document.getElementById(id).innerHTML = msg;
	doc.removeAttribute("disabled");
}

function generate_code(len){
	var data="123456789";
	var i;
	var out="";
	for(i=0;i<len;i++){
		out += data.charAt(Math.floor(Math.random() * data.length));
	}
	return out;
}

var scroll_pos =0;
function scrollpos_get(){
	scroll_pos = $("div.list-scroll").scrollTop();
}
function scrollpos_set(){
	$("div.list-scroll").scrollTop(scroll_pos);
}