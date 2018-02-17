
function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	
	var ag_html='<option value="">Select Agent</option>';
	document.getElementById("agent_id").innerHTML = ag_html;
	document.getElementById("dtr").value="";
	
	$("#data_list").html('');
}

function check_subdiv(doc){
	
	$("#secondary_data").css({"display":"none"});
	
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
					show_msg(doc,"create_subdiv_msg","#98261A","Agent not exists");
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
						
						
						var agent_str = rd_arr[3];
						var ag_j = $.base64.decode(agent_str);
						var ag_arr = JSON.parse(ag_j);
						var i; var ag_html='<option value="">Select Agent</option>';
						for(i=0;i<ag_arr.length;i++){
							ag_html += '<option value="'+ ag_arr[i]['id'] +'">'+ ag_arr[i]['name'] +'</option>';
						}
						document.getElementById("agent_id").innerHTML = ag_html;
						
						list_load();
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}


/*-----------------------------------------------------*/



function update_para(doc){
	var adid = document.getElementById("adid").value;
	var subdiv = document.getElementById("subdiv_id").value;
	var agent = document.getElementById("agent_id").value;
	var dtr = document.getElementById("dtr").value;
	
	if(adid !="" && subdiv !="" && agent !="" && dtr !="" ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [adid,subdiv,agent,dtr];
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
						blank_detail();
						list_load();
					}
					else if(response == '0'){
						show_msg(doc,"create_system_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_system_msg","#98261A","DTR already assigned");
						dtr_search(dtr);
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

function edit_data(d){
	var data = JSON.parse($.base64.decode(d.value));
	document.getElementById("agent_id").setAttribute("disabled","disabled");
	document.getElementById("agent_id").value = data['aid'];
	document.getElementById("adid").value = data['id'];
	document.getElementById("dtr").value = data['dtr'];
}

function del_data(doc){
	var pid = doc.value;
	var msgid = "action_msg_"+pid;
	
	if(pid !="" ){
		
		show_msg(doc,msgid,"#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [pid,];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to proceed?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/del.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						show_msg(doc,msgid,"#135400","Successful");
						list_load();
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
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
	else{
		show_msg(doc,msgid,"#98261A","Fill up all the fields");
		
	}
}

function blank_detail(){
	document.getElementById("adid").value = 0;
	document.getElementById("agent_id").value ="";
	document.getElementById("agent_id").removeAttribute("disabled");
	document.getElementById("dtr").value ="";
	list_load();
}


//////////////////list code/////////////////////

function list_load(){
	var sid = document.getElementById("subdiv_id").value;
	if(sid !=""){
		var aid = document.getElementById("agent_id").value;
		var dtr = document.getElementById("dtr").value;
		var data = [sid,aid,dtr];
		var dstr = $.base64.encode(JSON.stringify(data));
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?d="+dstr);
	}
}

function dtr_search(d){
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "listdtr.php?d="+d);
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