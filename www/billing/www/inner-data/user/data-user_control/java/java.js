/////////////////////////////user//////////////////////////////////////

function get_userlist(doc){
	var aid = doc.value;
	if( aid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [aid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/ucheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"get_userlist_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"get_userlist_msg","#98261A","Invalid authority data");
				}
				else if(res == "2"){
					show_msg(doc,"get_userlist_msg","#98261A","User not exists");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"get_userlist_msg","#135400","Successful");
						
						var user_str = rd_arr[1];
						var us_j = $.base64.decode(user_str);
						var us_arr = JSON.parse(us_j);
						
						var i; var us_html='<option value="">Select User</option>';
						for(i=0;i<us_arr.length;i++){
							us_html += '<option value="'+ us_arr[i]['id'] +'">'+ us_arr[i]['fname'] +' '+ us_arr[i]['lname'] +'</option>';
						}
						document.getElementById("uid").innerHTML = us_html;
					}
				}
			},
			error:function(){
				show_msg(doc,"get_userlist_msg","#98261A","Network connection problem");
			}
		});
		
	}
	else{
		var us_html='<option value="">Select User</option>';
		document.getElementById("uid").innerHTML = us_html;
	}
}



/////////////////////////////////////////////////////////////////////////////
function create_system(doc){
	var u = document.getElementById("uid").value;
	var a = document.getElementById("aid").value;
	
	if(u !="" && a !="" ){
		$("#create_system_msg").css({"color":"#000099"});
		document.getElementById("create_system_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [u,a];
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
						show_msg(doc,"create_system_msg","#98261A","Invalid user data");
					}
					else if(response == '2'){
						show_msg(doc,"create_system_msg","#98261A","Invalid authority data");
					}
					else if(response == '3'){
						show_msg(doc,"create_system_msg","#98261A","Authority already added");
					}
					else if(response == '4'){
						show_msg(doc,"create_system_msg","#98261A","Your authority cannot be added");
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
function auth_del(doc){
	var a = doc.value;
	var u = $(doc).data("uid");
	
	if(u !="" && a !="" ){
		var msgid = "create_action_msg_"+a;
		
		$(msgid).css({"color":"#000099"});
		document.getElementById(msgid).innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [u,a];
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
	var str = document.getElementById("uid").value;
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