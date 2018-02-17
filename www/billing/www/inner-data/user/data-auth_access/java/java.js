

////////////////////////////////////page/////////////////////////////////////////////

function get_pagelist(doc){
	var tid = doc.value;
	if( tid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [tid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/pcheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"get_pagelist_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"get_pagelist_msg","#98261A","Invalid tag data");
				}
				else if(res == "2"){
					show_msg(doc,"get_pagelist_msg","#98261A","Page not exists");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"get_pagelist_msg","#135400","Successful");
						
						var page_str = rd_arr[1];
						var pg_j = $.base64.decode(page_str);
						var pg_arr = JSON.parse(pg_j);
						
						var i; var pg_html='<option value="">Select Page</option>';
						for(i=0;i<pg_arr.length;i++){
							pg_html += '<option value="'+ pg_arr[i]['id'] +'">'+ pg_arr[i]['name'] +'</option>';
						}
						document.getElementById("pid").innerHTML = pg_html;
					}
				}
			},
			error:function(){
				show_msg(doc,"get_pagelist_msg","#98261A","Network connection problem");
			}
		});
		
	}
	else{
		var pg_html='<option value="">Select Page</option>';
		document.getElementById("pid").innerHTML = pg_html;
	}
}
/////////////////////////////////////////////////////////////////////////////
function create_system(doc){
	var a = document.getElementById("authid").value;
	var p = document.getElementById("pid").value;
	
	if(a !="" && p !="" ){
		$("#create_system_msg").css({"color":"#000099"});
		document.getElementById("create_system_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [a,p];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to add?");
		
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
						show_msg(doc,"create_system_msg","#98261A","Invalid authority data");
					}
					else if(response == '2'){
						show_msg(doc,"create_system_msg","#98261A","Invalid page data");
					}
					else if(response == '3'){
						show_msg(doc,"create_system_msg","#98261A","Page already added");
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
	document.getElementById("ptid").value ="";
	var pg_html='<option value="">Select Page</option>';
	document.getElementById("pid").innerHTML = pg_html;
}
/////////////////////////////////////////////////////////
function page_del(doc){
	var p = doc.value;
	var a = $(doc).data("aid");
	
	if(a !="" && p !="" ){
		var msgid = "create_action_msg_"+p;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [a,p];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Do you really want to do this?");
		
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

function list_search(){
	var str = document.getElementById("authid").value;
	var str_s = $.base64.encode(str);
	
	if(str !=""){
		$("#page_list").html('<tr><td>Loading...</td></tr>');
		$("#page_list").load(llink + "list.php?s="+str_s);
	}
	else{
		$("#page_list").html('');
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