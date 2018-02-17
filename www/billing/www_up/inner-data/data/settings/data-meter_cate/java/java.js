



function create_cate(doc){
	var mtr_cate = document.getElementById("mtr_cate").value;
	
	var mtr_rent = document.getElementById("mtr_rent").value;
	
	var mtr_phase = document.getElementById("mtr_phase").value;
	var mtr_code  = document.getElementById("mtr_code").value;
	
	if(mtr_cate !="" && mtr_rent !="" && mtr_phase !="" && mtr_code !="" ){
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data = [mtr_cate,mtr_rent,mtr_phase,mtr_code];
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
						show_msg(doc,"create_system_msg","#98261A","Meter Category exists");
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
	document.getElementById("mtr_cate").value ="";
	document.getElementById("mtr_rent").value ="";
	document.getElementById("mtr_mfact").value ="";
	document.getElementById("mtr_phase").value ="";
	document.getElementById("mtr_code").value ="";
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
	$("#meter_list").html('<tr><td>Loading...</td></tr>');
	$("#meter_list").load(llink + "list.php");
	document.getElementById("search_str").value="";
}

function list_search(){
	var str = document.getElementById("search_str").value;
	var str_s = $.base64.encode(str);
	
	if(str !=""){
		$("#meter_list").html('<tr><td>Loading...</td></tr>');
		$("#meter_list").load(llink + "list.php?s="+str_s);
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