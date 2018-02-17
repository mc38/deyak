

function reset_subdiv(doc){
	var ele = document.getElementById("subdiv_id");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id_check").value = "";
}

function check_subdiv(doc){
	document.getElementById("subdiv_id_check").value = "";
	document.getElementById("subdiv_id").removeAttribute("disabled");
	var sid = document.getElementById("subdiv_id").value;
	if( sid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [sid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/acheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID exists");
				}
				else{
					show_msg(doc,"create_subdiv_msg","#135400","Successful");
					document.getElementById("subdiv_id_check").value = "1";
					$(doc).css({"display":"none"});
					$("#subdiv_re_but").css({"display":""});
					document.getElementById("subdiv_id").setAttribute("disabled","disabed");
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}



function create_subdiv(doc){
	var sid  = document.getElementById("subdiv_id").value;
	var sid_ch = document.getElementById("subdiv_id_check").value
	
	var name = document.getElementById("subdiv_name").value;
	var detail = document.getElementById("subdiv_detail").value;
	
	if(sid !="" && sid_ch =="1" && name !="" && detail !="" ){
		$("#create_system_msg").css({"color":"#000099"});
		document.getElementById("create_system_msg").innerHTML = "Please Wait";
		var c = getcode(10);
		
		var data = new Array();
		data = [sid,name,detail];
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
						show_msg(doc,"create_system_msg","#98261A","Sub-Division ID exists, Enter New One");
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
	reset_subdiv(document.getElementById("subdiv_ch_but"));
	document.getElementById("subdiv_id_check").value = "";
	
	document.getElementById("subdiv_name").value ="";
	document.getElementById("subdiv_detail").value ="";
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

////////// List ////////////

$(function(){
	list_load();
});

function list_load(){
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "list.php");
	document.getElementById("search_str").value="";
}

function list_search(){
	var str = document.getElementById("search_str").value;
	var str_s = $.base64.encode(str);
	
	if(str !=""){
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s);
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