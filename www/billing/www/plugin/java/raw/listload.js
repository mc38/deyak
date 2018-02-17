//import jquery.mis.js at first

/*
put is code

<input id="listtotal" type="hidden" value="##total row number##" />

in page which is going to load
*/

var start;
var bar;
function listpredefine(){
	bar = document.getElementById("listload_progressbar");
	start = 0;
}

function listload(doc,url,endfunction,frequency){
	listpredefine();
	
	$(bar).css({"width":"0%"});
	$(doc).html('');
	loaddata(doc,url,frequency,endfunction);
}

function loaddata(doc,url,f,endfunc){
	var nurl = url +"&pos=" + start +"&freq="+ f;
	$.ajax({
		url: nurl,
		dataType: 'html',
		success: function(html){
			if(start >0){
				$(doc).append(html);
			}else{
				$(doc).html(html);
			}
			var total = document.getElementById("listtotal").value;
			start = Math.floor(start + f);
			var blen = (start / total) * 100;
			$(bar).css({"width": blen +"%"});
			
			if(start<total){
				var ndoc = document.getElementById("listload");
				loaddata(ndoc,url,f,endfunc);
			}else{
				endfunc();
			}
		},
		error:function(){
			setTimeout(function(){
				loaddata(doc,url,f,endfunc);
			},5000);
		}
	});
	
	/*
	$(doc).load(nurl,function(){
		var total = document.getElementById("listtotal").value;
		start = Math.floor((start +1) *f);
		if(start<total){
			var ndoc = document.getElementById("listload_"+start);
			alert($(ndoc).html());
			loaddata(ndoc,url,f,endfunc)
		}else{
			endfunc();
		}
	});
	*/
}