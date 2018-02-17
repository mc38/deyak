function get_data(){
	var subdiv 			= document.getElementById("en_subdiv").value;
	var dtrno 			= document.getElementById("en_dtrno").value;
	var conno 			= document.getElementById("en_conno").value;
	
	if(subdiv !="" && dtrno !="" && conno !="" ){
		
		$("#edit_data").html('<tr><td>Loading...</td></tr>');
		$("#edit_data").load(llink + "edit.php?s="+subdiv+"&d="+dtrno+"&c="+conno,function(){scrollpos_set();});
	}
}



function update_cate(doc){
	var i 				= document.getElementById("i").value;
	var mi 				= document.getElementById("mi").value;
	var ri 				= document.getElementById("ri").value;

	var o_meterno 		= document.getElementById("en_o_meterno").value;
	var n_meterno 		= document.getElementById("en_n_meterno").value;
	var sm 		= document.getElementById("sm").value;
	
	if(i !="" && mi !="" && ri !="" && o_meterno !="" && n_meterno !="" && sm !="" ){
		
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data[0] = i;
		data[1] = mi;
		data[2] = ri;

		data[3] = o_meterno;
		data[4] = n_meterno;
		data[5] = sm;

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
					show_msg(doc,"create_system_msg","#98261A","");
					if(c == response){
						alert("Successful");
						$("#edit_data").html("");
					}
					else if(response == '0'){
						alert("Authentication problem");
					}
					else if(response == '1'){
						alert("Data mismatch. Maybe wrong old meterno");
					}
					else{
						alert("Data problem");
					}
				},
				error:function(){
					show_msg(doc,"create_system_msg","#98261A","");
					alert("Network connection problem");
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




//////////////////list code/////////////////////

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