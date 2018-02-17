
function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	
	document.getElementById("con_srch").value="";
}

function check_subdiv(doc){
	
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
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data problem");
				}
				else if(res == "1"){
					show_msg(doc,"create_subdiv_msg","#98261A","Subdiv ID doesnot exists");
				}
				else if(res == "2"){
					show_msg(doc,"create_subdiv_msg","#98261A","Agent not exists");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						show_msg(doc,"create_subdiv_msg","#135400","Successful");
						document.getElementById("subdiv_id").value = sid;
						$(doc).css({"display":"none"});
						$("#subdiv_re_but").css({"display":""});
						document.getElementById("subdiv_srch").setAttribute("disabled","disabed");
						document.getElementById("subdiv_srch").value = rd_arr[2];
						document.getElementById("subdiv_lvl").innerHTML = "Name";
						
						document.getElementById("con_srch").value="";
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}


///////////////////////////////////
function clear_field(){
	document.getElementById("conid").value ="";
	document.getElementById("tariff").value ="";
	document.getElementById("book_no").value ="";
}

//////////////search//////////////////////////////
function list_search(doc){
	var str = document.getElementById("subdiv_id").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	
	var cid = document.getElementById("conid").value;
	var tar = document.getElementById("tariff").value;
	var bkno = document.getElementById("book_no").value;
	
	if(str !="" && smon !="" && syar !=""){
		var sdate = "01-"+ smon +"-"+ syar;
		
		var data = [str.toLowerCase(),sdate,tar,bkno,cid];
		var str_s = $.base64.encode(JSON.stringify(data));
		scrollpos_get();
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up starred fields");
	}
}


////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
		var w = window.open();
		w.document.write(d);
		w.document.getElementsByTagName("table").item(0).style.fontSize="10px";
		w.print();
		w.close();
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


var scroll_pos =0;
function scrollpos_get(){
	scroll_pos = $("div.list-scroll").scrollTop();
}
function scrollpos_set(){
	$("div.list-scroll").scrollTop(scroll_pos);
}