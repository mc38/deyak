function reset_subdiv(doc){
	$("#secondary_data").css({"display":"none"});
	//blank_consumerdetail();
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
}

function check_subdiv(doc){
	
	$("#secondary_data").css({"display":"none"});
	//blank_consumerdetail();
	
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_srch").removeAttribute("disabled");
	var sid = document.getElementById("subdiv_srch").value;
	if( sid !="" ){
		doc.setAttribute("disabled","disabled");
		
		var c = getcode(10);
		
		var data = new Array();
		data = [sid];
		var dd = $.base64.encode(JSON.stringify(data));
		
		
		$.ajax({
			url:llink + "server/ccheck.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID doesnot exists");
				}
				else{
					
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"create_subdiv_msg","#135400","Successful");
						document.getElementById("subdiv_id").value = rd_arr[1];
						$(doc).css({"display":"none"});
						$("#subdiv_re_but").css({"display":""});
						document.getElementById("subdiv_srch").setAttribute("disabled","disabed");
						document.getElementById("subdiv_srch").value = rd_arr[2];
						document.getElementById("subdiv_lvl").innerHTML = "Name";
						
						//list_load();
						$("#secondary_data").css({"display":""});
						
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}

/////////////////// Meter Check ///////////////////////////

function checkmeter(doc){
	document.getElementById("con_mtr").value = "";
	var mtr_srch = document.getElementById("con_mtr_srch").value;
	if(mtr_srch !=""){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [mtr_srch];
		var dd = $.base64.encode(JSON.stringify(data));
		
		$.ajax({
			type:"POST",
			url	:llink + "server/acheck.php",
			data:{c:c,d:dd},
			success:function(response){
				//alert(response);
				response = $.trim(response);
				if(response == '0'){
					show_msg(doc,"create_system_msg","#98261A","Authentication problem");
				}
				else if(response == '1'){
					show_msg(doc,"create_system_msg","#98261A","Meter no is not found");
				}
				else if(response == '2'){
					show_msg(doc,"create_system_msg","#98261A","Meter no is used by another consumer");
				}
				else{
					var res = JSON.parse($.base64.decode(response));
					if(c == res[0]){
						show_msg(doc,"create_system_msg","#135400","Meter no is found");
						var rdata = res[1];
						
						document.getElementById("con_mtr").value = rdata['id'];
					}
					else{
						show_msg(doc,"create_system_msg","#98261A","Data problem");
					}
				}
			},
			error:function(){
				show_msg(doc,"create_system_msg","#98261A","Network connection problem");
			}
		});
	}
	else{
		show_msg(doc,"create_system_msg","#98261A","Enter Meter no");
	}
}




/////////////////////CONSUMER///////////////////////////



function create_consumer(doc){
	var subdiv_id = document.getElementById("subdiv_id").value;
	
	var con_id = document.getElementById("con_id").value;
	
	var con_name = document.getElementById("con_name").value;
	var con_add = document.getElementById("con_add").value;
	var con_phone = document.getElementById("con_phone").value;
	var con_installno = document.getElementById("con_installno").value;
	var con_conload = document.getElementById("con_conload").value;
	var con_mru = document.getElementById("con_mru").value;
	var con_mfactor = document.getElementById("con_mfactor").value;
	var con_category = document.getElementById("con_category").value;
	var con_mtr = document.getElementById("con_mtr").value;
	
	var con_ext1 = document.getElementById("con_phase").value;
	var con_ext2 = document.getElementById("con_hp").value;
	var con_ext3 = document.getElementById("con_mowner").value;
	
	var con_ext_d  = new Array();
	con_ext_d = [con_ext1, con_ext2, con_ext3];
	var con_ext = $.base64.encode(JSON.stringify(con_ext_d));
	
	
	var data = new Array();
	data = [con_id, con_name, con_add, con_phone, con_installno, con_conload, con_mru, con_mfactor, con_ext, con_category, con_mtr, subdiv_id];
	
	if( data.indexOf("") <1 ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		
		var c = getcode(10);
		
		
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
						blank_consumerdetail();
					}
					else if(response == '0'){
						show_msg(doc,"create_system_msg","#98261A","Authentication problem");
					}
					else if(response == '1'){
						show_msg(doc,"create_system_msg","#98261A","Consumer ID exists");
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



function blank_consumerdetail(){
	document.getElementById("con_id").value = "";
	
	document.getElementById("con_name").value = "";
	document.getElementById("con_add").value = "";
	document.getElementById("con_phone").value = "";
	document.getElementById("con_installno").value = "";
	document.getElementById("con_conload").value = "";
	document.getElementById("con_mru").value = "";
	document.getElementById("con_mfactor").value = "";
	document.getElementById("con_category").value = "";
	document.getElementById("con_mtr").value = "";
	document.getElementById("con_mtr_srch").value = "";
	
	document.getElementById("con_phase").value ="";
	
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

//////////////// List ////////////////////


function list_load(){
	list_search();
	document.getElementById("search_str").value="";
}

function list_search(){
	var subdiv = document.getElementById("subdiv_id").value;
	var str = document.getElementById("search_str").value;
	var data = [subdiv,str.toLowerCase()];
	var str_s = $.base64.encode(JSON.stringify(data));
	
	if(subdiv !=""){
		$("#agent_list").html('<tr><td>Loading...</td></tr>');
		$("#agent_list").load(llink + "list.php?s="+str_s);
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