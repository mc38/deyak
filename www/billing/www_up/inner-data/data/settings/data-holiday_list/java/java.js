
function create_h(doc){
	var h_name = document.getElementById("h_name").value;
	
	var h_date = document.getElementById("h_date").value;
	
	if(h_name !="" && h_date !="" ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [h_name,h_date];
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
						list_load();
					}
					else if(response == '0'){
						show_msg(doc,"create_system_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_system_msg","#98261A","Date already exists");
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


function del_h(doc){
	var h = doc.value;
	
	if(h !="" ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [h];
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
						show_msg(doc,"create_system_msg","#135400","Successful");
						blank_systemdetail();
						list_load();
					}
					else if(response == '0'){
						show_msg(doc,"create_system_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_system_msg","#98261A","Date not found");
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
	document.getElementById("h_name").value ="";
	document.getElementById("h_date").value ="";
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

//////////////////list code/////////////////////
$(function(){
	list_load();
});

function list_load(){
	$("#h_list").html('<tr><td>Loading...</td></tr>');
	$("#h_list").load(llink + "list.php");
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