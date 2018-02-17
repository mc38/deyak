
function reset_subdiv(doc){
	
	var ele = document.getElementById("subdiv_srch");
	ele.value = "";
	ele.removeAttribute("disabled");
	$("#subdiv_ch_but").css({"display":""});
	$("#subdiv_re_but").css({"display":"none"});
	show_msg(doc,"create_subdiv_msg","#98261A","");
	document.getElementById("subdiv_id").value = "";
	document.getElementById("subdiv_lvl").innerHTML = "ID";
	
	document.getElementById("search_month").value="";
	document.getElementById("search_year").value="";
	document.getElementById("book_no").value="";
	clear_field();
}

function check_subdiv(doc){
	
	$("#secondary_data").css({"display":"none"});
	
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
						
					}
				}
			},
			error:function(){
				show_msg(doc,"create_subdiv_msg","#98261A","Network connection problem");
			}
		});
		
	}
}
/////////////

function clear_field(){
	document.getElementById("conid").value ="";
	document.getElementById("tariff").value ="";
	document.getElementById("book_no").value ="";
}


function change_pic(id){
	var doc = document.getElementById("status_"+id);
	var d = doc.value;
	
	var msgid = "mtrpic_action_"+id;
	if( d !="" ){
		
		doc.setAttribute("disabled","disabled");
		$("#edit_but_"+id,"#mtrpic"+id).attr("disabled","disabled");
		var c = getcode(10);
		
		var data = new Array();
		data = [d];
		var dd = $.base64.encode(JSON.stringify(data));
		
		$(doc).attr("disabled","disabled");
		document.getElementById(msgid).innerHTML="Please Wait...";
		$.ajax({
			url:llink + "server/cpic.php",
			type:"POST",
			data:{c:c,d:dd},
			success:function(res){
				//alert(res);
				if(res == "0"){
					show_msg(doc,"create_subdiv_msg","#98261A","Data Problem");
				}
				else{
					var rd_j = $.base64.decode(res);
					var rd_arr = JSON.parse(rd_j);
					if(rd_arr[0] == c){
						var pic = rd_arr[1];
						$("#mtrpic_"+id).attr("src","data:image/jpeg;base64,"+pic);
						document.getElementById("mtrpicdata_"+id).value = pic;
						show_msg(doc,msgid,"#98261A","");
						$("#edit_but_"+id,"#mtrpic"+id).removeAttr("disabled");
					}
				}
			},
			error:function(){
				show_msg(doc,msgid,"#98261A","Network connection problem");
			}
		});
		
	}

}

function type_show(doc,id){
	var rdoc = document.getElementById("read_"+id);
	rdoc.removeAttribute("readonly");
	rdoc.value ="";
	if(doc.value>0){
		rdoc.setAttribute("readonly","readonly");
		rdoc.value = "-1";
	}
}
//////////////////Bill Made/////////////////////////
function bill_edit(doc){
	var id = $(doc).data("i");
	var s = doc.value;
	var sa = document.getElementById("status_"+id).value;
	var d = document.getElementById("read_"+id).value;
	var pd = document.getElementById("pread_"+id).value;
	var pic= document.getElementById("mtrpicdata_"+id).value;
	
	var msgid = "action_msg_"+id;
	if( d !="" && s !="" && pic !="" ){
		if(((parseInt(sa)<1) && parseInt(pd) < parseInt(d)) || (parseInt(sa)>0)){
			
			var conf = confirm("Do you really want to change this reading? Please ensure the further effect with your management first.");
			if(conf){
				doc.setAttribute("disabled","disabled");
				var c = getcode(10);
				
				var data = new Array();
				data = [s,sa,d,pic];
				var dd = $.base64.encode(JSON.stringify(data));
				
				$(doc).attr("disabled","disabled");
				document.getElementById(msgid).innerHTML="Please Wait...";
				$.ajax({
					url:llink + "server/mbill.php",
					type:"POST",
					data:{c:c,d:dd},
					success:function(res){
						//alert(res);
						if(res == c){
							list_search(doc);
						}
						else if(res == "0"){
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
		}
		else{
			show_msg(doc,msgid,"#98261A","Curr Read > Prev Read");
		}
	}

}
//////////////// List ////////////////////


function list_search(doc){
	var str = document.getElementById("subdiv_id").value;
	var smon = document.getElementById("search_month").value;
	var syar = document.getElementById("search_year").value;
	
	var cid = document.getElementById("conid").value;
	var tar = document.getElementById("tariff").value;
	var bkno = document.getElementById("book_no").value;
	var year = document.getElementById("fyear").value
	var cadd = document.getElementById("conadd").value;
	
	if(str !="" && smon !="" && syar !=""){
		var sdate = "01-"+ smon +"-"+ syar;
		
		var data = [str.toLowerCase(),sdate,tar,bkno,cid,year,cadd];
		var str_s = $.base64.encode(JSON.stringify(data));
		scrollpos_get();
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "list.php?s="+str_s,function(){scrollpos_set();});
	}
	else{
		show_msg(doc,"search_msg","#98261A","Fill up all fields");
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

////////////////////////////////////////


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