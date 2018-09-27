$(document).ready(function(){
	var skupina = $("#skupinaHidden").val();
	$.ajax({
		url:"servlets/getPlayersIdsServlet.php",
		type:"post",
		data:{"skupina":skupina},
		success: function(data){
			$.each(JSON.parse(data), function(index, row){
				var id = row['id'];
				$("#editFile-"+id).change(function() {
					console.log("edit"+id);
		    		$('#submitFile-'+id).click();
		    	});
			});
		}
	});	
});

function vymazHraca(id){
	if (!confirm('Naozaj chcete vymazať hráča?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazHracaServlet.php",
		type:"post",
		data:{"id":id},
		success: function(data){
			window.location.reload();
		}
	});	
}

function upravHraca(id){
	var zmenBtn = $("#zmenBtn-" + id);
	var upravBtn = $("#upravBtn-" + id);
	var vymazBtn = $("#vymazBtn-" + id);

	var celeMeno = $("#celeMeno-" + id);
	var post = $("#post-" + id);
	var rocnik = $("#rocnik-" + id);
	var kluby = $("#kluby-" + id);

	$.ajax({
		url:"servlets/getPlayerWithIdServlet.php",
		type:"post",
		data:{"id":id},
		datatype: 'json',
		success: function(data){
			data = JSON.parse(data);
			var oldCeleMeno = data['meno'] + " " + data["priezvisko"];
			var oldPost = data['typ_hraca'];
			var oldRocnik = data['rok_narodenia'];
			var oldKluby = data['kluby'];
			

			celeMeno.replaceWith('<input class="" id="newCeleMeno-'+id+'" type="text" value="'+oldCeleMeno+'">');		
			post.replaceWith('<input class="" id="newPost-'+id+'" type="text" value="'+oldPost+'">');	
			rocnik.replaceWith('<input class="" id="newRocnik-'+id+'" type="text" value="'+oldRocnik+'">');	
			kluby.replaceWith('<textarea rows="4" cols="100" class="form-control" id="newKluby-'+id+'" required data-validation-required-message="Zadaj text" maxlength="999" style="resize:none">'+oldKluby+'</textarea>');

			vymazBtn.prop('hidden',true);
			zmenBtn.prop('hidden',true);
			upravBtn.replaceWith(vypisPotvrdBtn(id));
		}
	});	
}

function potvrdHraca(id){
	var potvrdBtn = $("#potvrdBtn-" + id);
	var zmenBtn = $("#zmenBtn-" + id);
	var vymazBtn = $("#vymazBtn-" + id);

	var newCeleMeno = $("#newCeleMeno-" + id);
	var newPost = $("#newPost-" + id);
	var newRocnik = $("#newRocnik-" + id);
	var newKluby = $("#newKluby-" + id);

	var newCeleMenoText = newCeleMeno.val();
	var newPostText = newPost.val();
	var newRocnikText = newRocnik.val();
	var newKlubyText = newKluby.val();

	//celeMeno to meno priezvisko
	var menoPriezvisko = newCeleMenoText.split(" ");
	var newMeno = menoPriezvisko[0];
	var newPriezvisko = menoPriezvisko[1];
	
	newCeleMeno.replaceWith('<h3 id="celeMeno-'+id+'">'+newMeno+' '+newPriezvisko+'</h3>');	
	newPost.replaceWith('<div id="post-'+id+'">'+newPostText+'</div>');	
	newRocnik.replaceWith('<div id="rocnik-'+id+'">'+newRocnikText+'</div>');		
	newKluby.replaceWith('<div id="kluby-'+id+'">'+reformatTextToHtml(newKlubyText)+'</div>');

	vymazBtn.prop('hidden',false);
	zmenBtn.prop('hidden',false);
	potvrdBtn.replaceWith(vypisUpravBtn(id));

	$.ajax({
		url:"servlets/upravHracaServlet.php",
		type:"post",
		data:{"id":id,"meno":newMeno,"priezvisko":newPriezvisko,"post":newPostText,"rocnik":newRocnikText,"kluby":newKlubyText}
	});		
}

function vypisPotvrdBtn(id){
	return '<a class="" id="potvrdBtn-'+id+'" href="javascript:potvrdHraca('+id+');"><img width="40" class="buttonImg withHover" src="fotky/ok.png" alt="Potvrd info o hráčovi"></a>';
}

function vypisUpravBtn(id){
	return '<a class="" id="upravBtn-'+id+'" href="javascript:upravHraca('+id+');"><img width="40" class="buttonImg withHover" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
}

function reformatTextToHtml(text){
	return text.replace(/\n/g, "<br />");
}

