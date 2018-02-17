

function create_tag(doc){
	var tag = document.getElementById("tag_name").value;
	
	if(tag !="" ){
		$("#create_tag_msg").css({"color":"#000099"});
		document.getElementById("create_tag_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [tag];
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
						show_msg(doc,"create_tag_msg","#135400","Successful");
						blank_tagdetail();
						list_load()
					}
					else if(response == '0'){
						show_msg(doc,"create_tag_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_tag_msg","#98261A","Tag exists");
					}
					else{
						show_msg(doc,"create_tag_msg","#98261A","Data problem");
					}
				},
				error:function(){
					show_msg(doc,"create_tag_msg","#98261A","Network connection problem");
				}
			});
		}
		else{
			show_msg(doc,"create_tag_msg","#98261A","");
		}
	}
	else{
		show_msg(doc,"create_tag_msg","#98261A","Fill up all the fields");
		
	}
}



function blank_tagdetail(){
	document.getElementById("tag_name").value = "";
}


////////////////////////////block and unblock//////////////////////////////////////////

function tag_sh(doc){
	var id = doc.value;
	
	if(id !=""){
		var msgid = "create_action_msg_"+id;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [id];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to do this?");
		
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
}

/////////////////up down//////////////////////

function tag_up(doc){
	var srl = doc.value;
	
	if(srl !=""){
		var msgid = "create_action_msg_"+srl;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [srl];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to do this?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/up.php",
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
}

function tag_down(doc){
	var srl = doc.value;
	
	if(srl !=""){
		var msgid = "create_action_msg_"+srl;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [srl];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to do this?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/down.php",
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
}


////////////List///////////

$(function(){
	list_load();
});

function list_load(){
	scrollpos_get();
	$("#tag_list").html('<tr><td>Loading...</td></tr>');
	$("#tag_list").load(llink + "list.php",function(){scrollpos_set();});
}


// supporting functions
function show_msg(doc,id,color,msg){
	$("#"+id).css({"color":color});
	document.getElementById(id).innerHTML = msg;
	doc.removeAttribute("disabled");
	setTimeout(function(){
		document.getElementById(id).innerHTML = "";
	},10000);
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