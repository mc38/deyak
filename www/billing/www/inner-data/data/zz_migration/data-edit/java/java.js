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
	var oldcon 			= document.getElementById("en_oldcon").value;			//03
	var conname 		= document.getElementById("en_conname").value;			//05
	var conaddress 		= document.getElementById("en_conaddress").value;		//06
	var meterno 		= document.getElementById("en_meterno").value;			//07
	var connload 		= document.getElementById("en_connload").value;			//08
	var mf 				= document.getElementById("en_mf").value;				//09
	var category 		= document.getElementById("en_category").value;			//10
	var metertype 		= document.getElementById("en_metertype").value;		//11

	var prevreading 	= document.getElementById("en_prevreading").value;		//12
	var prevbilldate 	= document.getElementById("en_prevbilldate").value;		//13

	var parrear 		= document.getElementById("en_parrear").value;			//15
	var arrsurchrg 		= document.getElementById("en_arrsurchrg").value;		//16
	var adjust 			= document.getElementById("en_adjust").value;			//18

	var avgunit 		= document.getElementById("en_avgunit").value;			//19

	var duedate 		= document.getElementById("en_duedate").value;			//20
	
	if(i !="" && oldcon !="" && conname !="" && conaddress !="" && meterno !="" && connload !="" && mf !="" && category !="" && metertype !="" && prevreading !="" && prevbilldate !="" && parrear !="" && arrsurchrg !="" && adjust !="" && avgunit !="" && duedate !="" ){
		
		show_msg(doc,"create_system_msg","#000099","Please Wait...");
		var c = getcode(10);
		
		var data = new Array();
		data[00] = i;
		data[01] = oldcon; 			//03
		data[02] = conname;			//05
		data[03] = conaddress;		//06
		data[04] = meterno;			//07
		data[05] = connload;		//08
		data[06] = mf;				//09
		data[07] = category;		//10
		data[08] = metertype;		//11

		data[09] = prevreading;		//12
		data[10] = prevbilldate;	//13

		data[11] = parrear;			//15
		data[12] = arrsurchrg;		//16
		data[13] = adjust;			//18
		
		data[14] = avgunit;			//19
		data[15] = duedate;			//20

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
						alert("Consumer not Exists");
					}
					else{
						alert("Data problem");
					}
				},
				error:function(){
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