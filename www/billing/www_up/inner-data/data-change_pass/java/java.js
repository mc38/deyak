

function change_pass(doc){
	var us = document.getElementById("username").value;
	var ops = document.getElementById("old_pass").value;
	var nps = document.getElementById("new_pass").value;
	var npsr = document.getElementById("new_passr").value;
	
	if(us !="" && ops !="" && nps !="" && npsr !="" ){
		$("#create_pass_msg").css({"color":"#000099"});
		document.getElementById("create_pass_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		if(nps == npsr){
			if(ops == nps){
				show_msg(doc,"create_pass_msg","#98261A","Can't use old password as a new password");
			}
			else{
				if(nps.length <8){
					show_msg(doc,"create_pass_msg","#98261A","Minimum password length is 8");
				}
				else{
					var data = new Array();
					data = [us,ops,nps];
					var dd = $.base64.encode(JSON.stringify(data));
					
					var conf = confirm("Please confirm the data you want to change?");
					
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
									show_msg(doc,"create_pass_msg","#135400","Successful");
									blank_passdetail();
								}
								else if(response == '0'){
									show_msg(doc,"create_pass_msg","#98261A","Authentication problem");
								}
								else if(response == '1'){
									show_msg(doc,"create_pass_msg","#98261A","Wrong Username and Old Password");
								}
								else{
									show_msg(doc,"create_pass_msg","#98261A","Data problem");
								}
							},
							error:function(){
								show_msg(doc,"create_pass_msg","#98261A","Network connection problem");
							}
						});
					}
					else{
						show_msg(doc,"create_pass_msg","#98261A","");
					}
				}
			}
		}
		else{
			show_msg(doc,"create_pass_msg","#98261A","Retype New Password Correctly");
		}
	}
	else{
		show_msg(doc,"create_pass_msg","#98261A","Fill up all the fields");
		
	}
}



function blank_passdetail(){
	document.getElementById("username").value ="";
	document.getElementById("old_pass").value ="";
	document.getElementById("new_pass").value ="";
	document.getElementById("new_passr").value ="";
	
	setTimeout(function(){
		window.location.href="";
	},10000);
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