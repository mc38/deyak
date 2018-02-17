

function create_system(doc){
	var auth = document.getElementById("authid").value;
	var sid = document.getElementById("subdivid").value;
	
	var fname = document.getElementById("user_fname").value;
	var lname = document.getElementById("user_lname").value;
	
	var contact = document.getElementById("user_contact").value;
	var sex = document.getElementById("user_sex").value;
	
	var user = document.getElementById("user_user").value;
	
	if(auth !="" && fname !="" && lname !="" && contact !="" && sex !="" && user !=""){
		$("#create_system_msg").css({"color":"#000099"});
		document.getElementById("create_system_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [auth,fname,lname,contact,sex,user,sid];
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
						show_msg(doc,"create_system_msg","#98261A","Invalid authority");
					}
					else if(response == '2'){
						show_msg(doc,"create_system_msg","#98261A","Username already exists, try new one");
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
	document.getElementById("authid").value = "";
	document.getElementById("subdivid").value = "";
	
	document.getElementById("user_fname").value ="";
	document.getElementById("user_lname").value ="";
	
	document.getElementById("user_contact").value ="";
	document.getElementById("user_sex").value ="";
	
	document.getElementById("user_user").value ="";
}


////////////////////////////block and unblock//////////////////////////////////////////

function user_delete(doc){
	var aid = doc.value;
	
	if(aid !=""){
		var msgid = "create_action_msg_"+aid;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to delete this user?");
		
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
						list_search();
					}
					else if(response == '0'){
						show_msg(doc,msgid,"#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,msgid,"#98261A","User ID not exists");
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


function user_block(doc){
	var aid = doc.value;
	
	if(aid !=""){
		var msgid = "create_action_msg_"+aid;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to block this user?");
		
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
						show_msg(doc,msgid,"#98261A","User ID not exists");
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


function user_restore(doc){
	var aid = doc.value;
	
	if(aid !=""){
		var msgid = "create_action_msg_"+aid;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to restore this user?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/restore.php",
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
						show_msg(doc,msgid,"#98261A","User ID not exists");
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
	//list_load();
});

function list_load(){
	$("#agent_list").html('<tr><td>Loading...</td></tr>');
	$("#agent_list").load(llink + "list.php");
	document.getElementById("search_str").value="";
}

function list_search(){
	var str = document.getElementById("search_str").value;
	var str_s = $.base64.encode(str);
	
	if(str !=""){
		scrollpos_get();
		$("#agent_list").html('<tr><td>Loading...</td></tr>');
		$("#agent_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
	}
	else{
		list_load();
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