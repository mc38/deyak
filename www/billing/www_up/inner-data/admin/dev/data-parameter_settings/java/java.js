
function update_para(doc){
	var pid = document.getElementById("pid").value;
	var parameter = document.getElementById("parameter").value;
	var pvalue = document.getElementById("pvalue").value;
	
	if(pid !="" && parameter !="" && pvalue !="" ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [pid,parameter,pvalue];
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
	document.getElementById("parameter").setAttribute("disabled","disabled");
	document.getElementById("parameter").value = data['parameter'];
	document.getElementById("pid").value = data['id'];
	document.getElementById("pvalue").value = data['value'];
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
	document.getElementById("pid").value = 0;
	document.getElementById("parameter").value ="";
	document.getElementById("parameter").removeAttribute("disabled");
	document.getElementById("pvalue").value ="";
}


//////////////////list code/////////////////////
$(function(){
	list_load();
});

function list_load(){
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "list.php");
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