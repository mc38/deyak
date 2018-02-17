var ufile_name = "";

$(document).ready(function(){
	$("#data_type").change(function(){
		reset_data();
		var d = document.getElementById("data_type").value;
		if(d !=""){
			$("#secondary_data").css({"display":""});
			document.getElementById("d").value = d;
		}
	});
	
	$("#file_download_but").click(function(){
		
		var subdivid = document.getElementById("subdiv").value;
		var smonth = document.getElementById("search_month").value;
		var syear = document.getElementById("search_year").value;
		var tariff = document.getElementById("tariff").value;
		var book = document.getElementById("book").value;
		var conid = document.getElementById("conid").value;
		if(subdivid !="" && smonth!="" && syear !="" ){
			document.getElementById("sd").value = subdivid;
			document.getElementById("sdd").value = "1-"+smonth+"-"+syear;
			document.getElementById("dt").value = tariff;
			document.getElementById("db").value = book;
			document.getElementById("dc").value = conid;
			$("#download_form").submit();
			
			document.getElementById("data_type").value="";
			reset_data();
		}
	});
	
});

function reset_data(){
	$("#secondary_data").css({"display":"none"});
	document.getElementById("subdiv").value ="";
	document.getElementById("search_month").value ="";
	document.getElementById("search_year").value ="";
	document.getElementById("tariff").value ="";
	document.getElementById("book").value ="";
	document.getElementById("conid").value ="";
	
	document.getElementById("d").value = "";
	document.getElementById("sd").value ="";
	document.getElementById("sdd").value ="";
	document.getElementById("dt").value ="";
	document.getElementById("db").value ="";
	document.getElementById("dc").value ="";
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