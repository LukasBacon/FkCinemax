var POCET_POLOZIEK_NA_STRANU = 3;
var aktualnaStrana = 1;
var pocetStran = 10000000;

$(document).ready(function(){
	updatePaginationNavigation();
	vypisNthStranu(1);
});


// vymazanie diskusie ------------------------------------------------------


// paginaton ---------------------------------------------------------------
function updatePaginationNavigation(){
	var paginationNavigation = $(".pagination");
	var paginationNavigationText = "";

	$.ajax({
		url:"servlets/forumPocetStranServlet.php",		// treba ho spravit
		type:"post",
		data:{"pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
		success: function(data){
			pocetStran = data;
			paginationNavigationText += '<li class="page-item pagination-prev"><a class="page-link" href="javascript:vypisPrevStranu();">&laquo;</a></li>';
			for (var strana = 1; strana <= pocetStran; strana++) {
				paginationNavigationText += '<li class="page-item" id="pagination-nav-page-' + strana + '">';
			    paginationNavigationText += '<a class="page-link" href="javascript:vypisNthStranu('+ strana +');">' + strana +'</a>';
			    paginationNavigationText += '</li>'; 
			}
			paginationNavigationText += '<li class="page-item pagination-next"><a class="page-link" href="javascript:vypisNextStranu();">&raquo;</a></li>';
			paginationNavigation.html(paginationNavigationText);
			highlightActualPage(aktualnaStrana);
			$(".pagination").rPage();
		}
	});	
}

function vypisNthStranu(cisloStrany){
	if (cisloStrany < 1 || cisloStrany > pocetStran){
		return;
	}
	$(".pagination").rPage();
	aktualnaStrana = cisloStrany;
	$.ajax({
		url:"servlets/diskusieGetNthStranuServlet.php",
		type:"post",
		data:{"cisloStrany":aktualnaStrana, "pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
		success: function(data){
			var strana = data;
			var diskusie = $(".diskusiePage");
			diskusie.html("");
			var diskusieText = '<div class="diskusiePage">';
			$.ajax({
				url:"servlets/getSessionServlet.php",
				type:"post",
				success: function(session){
					session = JSON.parse(session);
					if (session["admin"] == 1){
						diskusieText = vypisDiskusieAdmin(strana);
					}
					else{
						diskusieText = vypisDiskusieUser(strana);
					}
					diskusieText += '</div>'
					diskusie.html(diskusieText);
				}
			});
			updatePaginationNavigation();
		}
	});
}

function vypisPrevStranu(){
	vypisNthStranu(aktualnaStrana-1);
}

function vypisNextStranu(){
	vypisNthStranu(aktualnaStrana+1);
}

function highlightActualPage(aktualnaStrana){
	$(".page-item").removeClass("active");
	$("#pagination-nav-page-" + aktualnaStrana).addClass("active");
}

function vypisDiskusieAdmin(data){

}

function vypisDiskusieUser(data){
	var diskusieText = "";
	$.each(JSON.parse(data), function(index, diskusia){
		diskusieText += '<div class="card my-4">';
      	diskusieText += 	vypisShowHeader(diskusia["id"], diskusia["nazov"]);
        diskusieText += 	'<div class="card-body">';
        diskusieText += 		'<h5 class="mt-0">'+diskusia["autor"]+'</h5>';
        diskusieText += 		diskusia["popis"];
        diskusieText += 		'<div id="komentare-'+diskusia['id']+'"></div>';
        diskusieText += 	'</div>';
        diskusieText +=     '<div class="card-footer">'+diskusia['datum_disk']+'</div>';
        diskusieText += '</div>';
	});
	return diskusieText;
}

function ukazKomentare(id, nazov){
	var komentare = $("#komentare-"+id);
	$.ajax({
		url:"servlets/dajKomentareDiskusieServlet.php",
		type:"post",
		data:{"id":id},
		success: function(data){
			var komentareHtml = '<div id="komentare-'+id+'">';
			komentareHtml += vypisKomentare(data);
			komentareHtml += vypisPridajKomentarForm(id);
			komentareHtml += '</div>';
			komentare.html(komentareHtml);
			var header = $("#header-"+id);
			header.replaceWith(vypisHideHeader(id, nazov));
		}
	});
}

function skryKomentare(id, nazov){
	var komentare = $("#komentare-"+id);
	komentare.html("");
	var header = $("#header-"+id);
	header.replaceWith(vypisShowHeader(id, nazov));
}

function vypisShowHeader(id, nazov){
	var result = "";
	result += 	'<a class="card-header" id="header-'+id+'" href="javascript:ukazKomentare('+id+',\''+nazov+'\');">';
    result += 		'<strong style="font-size: 1.25rem;">'+nazov+'</strong>';
    result += 		'<strong style="float:right;">&#9660;</strong>';
    result += 	'</a>';
    return result;
}

function vypisHideHeader(id, nazov){
	var result = "";
	result += 	'<a class="card-header" id="header-'+id+'" href="javascript:skryKomentare('+id+',\''+nazov+'\');">';
    result += 		'<strong style="font-size: 1.25rem;">'+nazov+'</strong>';
    result += 		'<strong style="float:right;">&#9650;</strong>';
    result += 	'</a>';
    return result;
}

function vypisKomentare(data){
	var komentareText = "";
	$.each(JSON.parse(data), function(index, komentar){
		komentareText += '<div class="card my-4">';
        komentareText += 	'<div class="card-body">';
        komentareText += 		'<h5 class="mt-0">'+komentar["meno"]+'</h5>';
        komentareText += 		komentar["text"];
        komentareText += 	'</div>';
        komentareText +=	'<div class="card-footer">'+komentar["datum"]+'</div>'
        komentareText += '</div>';
	});
	return komentareText;
}

function vypisPridajKomentarForm(idDiskusie){
	var result = '';
	result += '<div class="card my-4">';
    result += 	'<div class="card-body">';
    result +=		'<label>Meno:</label>';
    result += 		'<input type="text" style="width:100%;" id="pridaj-meno-'+idDiskusie+'">';
    result +=		'<label>Komentár:</label>';
    result += 		'<textarea rows="5" style="width:100%;" id="pridaj-komentar-'+idDiskusie+'"></textarea>';
    result += 	'</div>';
    result +=	'<div class="card-footer">';
    result +=	'<a class="btn btn-primary" href="javascript:pridajKomentar('+idDiskusie+')">Pridaj</a>';
    result +=   '</div>';
    result += '</div>';
    return result;
}

function pridajKomentar(idDiskusie){
	var menoInput = $("#pridaj-meno-"+idDiskusie);
	var komentarInput = $("#pridaj-komentar-"+idDiskusie);
	var meno = menoInput.val();
	var komentar = komentarInput.val();
	// check ci je dobre vyplnene
	$.ajax({
		url:"servlets/pridajKomentarDiskusiiServlet.php",
		type:"post",
		data:{"idDiskusie":idDiskusie, "meno":meno, "text":komentar},
		success: function(data){
			menoInput.val("");
			komentarInput.val("");
			// refreshni kometare diskuies id idDiskusie
			// skontrolovat funkcnost
		}
	});
}

function reformatTextToHtml(text){
	return text.replace(/\n/g, "<br />");
}