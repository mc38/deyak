
function update_consumption(doc){
	var cate	= document.getElementById("cate").value;
	var consump = document.getElementById("consump").value;
	
	var msgid = "create_system_msg";
	
	if(cate !="" && consump !="" ){
		show_msg(doc, msgid,"#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [cate,consump];
		var dd = $.base64.encode(JSON.stringify(data));
		
		var conf = confirm("Please confirm the data you want to update?");
		
		if(conf){
			doc.setAttribute("disabled","disabled");
			
			$.ajax({
				url	:llink + "server/update.php",
				type:"POST",
				data:{c:c,d:dd},
				success:function(response){
					//alert(response);
					response = $.trim(response);
					if(c == response){
						show_msg(doc,msgid,"#135400","Successful");
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


function get_data(doc){
	var cate	= doc.value;
	
	var msgid = "create_system_msg";
	
	if(cate !="" ){
		show_msg(doc, msgid,"#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [cate];
		var dd = $.base64.encode(JSON.stringify(data));
		
		doc.setAttribute("disabled","disabled");
		
		$.ajax({
			url	:llink + "server/check.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(response){
				//alert(response);
				response = $.trim(response);
				if(response == '0'){
					show_msg(doc,msgid,"#98261A","Authentication problem");
				}
				else if(response == '1'){
					show_msg(doc,msgid,"#98261A","Data problem");
				}
				else{
					var r = JSON.parse($.base64.decode(response));
					if(r[0] == c){
						document.getElementById("consump").value = r[1];
						show_msg(doc,msgid,"#135400","Successful");
					}else{
						show_msg(doc,msgid,"#98261A","Data problem");
					}
				}
			},
			error:function(){
				show_msg(doc,msgid,"#98261A","Network connection problem");
			}
		});
	}else{
		document.getElementById("consump").value = "";
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