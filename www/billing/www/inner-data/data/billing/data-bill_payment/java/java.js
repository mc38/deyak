
function search_consumer(doc){
	
	var cid = document.getElementById("cid").value;
	
	if(cid !="" ){
		var str_s = $.base64.encode(JSON.stringify([cid]));
		list_search(str_s);
	}
	else{
		show_msg(doc,"create_check_msg","#98261A","Fill up all fields");
		
	}
}

function calculate_amount(){
	var d = document.getElementById("pay_amount_calc").value;

	var n_pa = 0;
	var n_as = 0;
	var n_cr = Math.abs(parseFloat(cr) + parseFloat(d)).toFixed(2);
	var n_ad = 0;

	if(cp == 0){
		var payment_arr = [as,pa];
		var i =0;
		while(i<payment_arr.length){
			if(payment_arr[i] > n_cr){
				payment_arr[i] = Math.abs(payment_arr[i] - n_cr).toFixed(2);
				n_cr = 0;
				break;
			}else{
				n_cr = Math.abs(n_cr - payment_arr[i]).toFixed(2);
				payment_arr[i] = 0;
			}
			i++;
		}
		n_as = payment_arr[0];
		n_pa = payment_arr[1];
		if(n_cr <10){
			n_ad = n_cr;
			n_cr = 0;
		}
	}else{
		n_pa = pa;
		n_as = as;
		n_ad = ad;
	}

	document.getElementById("pa_show").innerHTML = n_pa;
	document.getElementById("as_show").innerHTML = n_as;
	document.getElementById("cr_show").innerHTML = n_cr;
	document.getElementById("ad_show").innerHTML = n_ad;

	pay_amount_confirm = d;
}






function pay(doc){
	var p = document.getElementById("pay_amount").value;
	var r = document.getElementById("pay_ref").value;
	
	var msgid = "pay_msg";
	
	if(r !="" && p !="" && conid !=""){
		if(pay_amount_confirm !=""){
			if(pay_amount_confirm == p){

				$("#"+ msgid).css({"color":"#000099"});
				document.getElementById(msgid).innerHTML = "Please wait...";
				var c = getcode(10);
				
				var data = new Array();
				data = [conid,p,r];
				var dd = $.base64.encode(JSON.stringify(data));
				
				var conf = confirm("Do you really want to procced?");
				
				if(conf){
					doc.setAttribute("disabled","disabled");
					
					$.ajax({
						url	:llink + "server/pay.php",
						type:"POST",
						data:{c:c,d:dd},
						success:function(response){
							alert(response);
							response = $.trim(response);
							if(c == response){
								show_msg(doc,msgid,"#135400","Successful");
								new_list();
							}else if(response == '0'){
								show_msg(doc,msgid,"#98261A","Authentication error");
							}else if(response == '1'){
								show_msg(doc,msgid,"#98261A","Invalid consumer");
							}else if(response == '2'){
								show_msg(doc,msgid,"#98261A","Amount must be greater than zero");
							}else if(response == '3'){
								show_msg(doc,msgid,"#98261A","Billing data is not found");
							}else{
								show_msg(doc,msgid,"#98261A","Data error");
							}
						},
						error:function(){
							show_msg(doc,msgid,"#98261A","Internet Connection Problem");
						}
					});
				}
				else{
					show_msg(doc,msgid,"#98261A","");
				}
			}else{
				show_msg(doc,msgid,"#98261A","Re-enter payment amount correctly");
			}
		}
		else{
			show_msg(doc,msgid,"#98261A","Calculate payment amount first");
		}
	}
	else{
		show_msg(doc,msgid,"#98261A","Fill up all fields");
	}
}


////////////print///////////

function print_report(){
	var d = $("#data_print").html();
	if(d !=""){
		var w = window.open();
		w.document.write(d);
		w.print();
		w.close();
	}
}

////////////List///////////

function list_search(str){
	$("#print_but").css({"display":"none"});
	
	scrollpos_get();
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "list.php?s="+str,function(){scrollpos_set();});
}

function new_list(){
	$("#print_but").css({"display":""});
	
	scrollpos_get();
	$("#data_list").html('<tr><td>Loading...</td></tr>');
	$("#data_list").load(llink + "new.php",function(){scrollpos_set();});
}

function print_receipt(str){
	$("#print_but").css({"display":""});
	
	if(str !=""){
		scrollpos_get();
		$("#data_list").html('<tr><td>Loading...</td></tr>');
		$("#data_list").load(llink + "new.php?p="+str,function(){scrollpos_set();});
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