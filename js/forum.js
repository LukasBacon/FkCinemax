var POCET_POLOZIEK_NA_STRANU = 3;
var aktualnaStrana = 1;
var pocetStran = 10000000;

$(document).ready(function(){
	updatePaginationNavigation();
	vypisNthStranu(1);
});

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
						diskusieText = vypisDiskusie(strana, true);
					}
					else{
						diskusieText = vypisDiskusie(strana, false);
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


// vypisanie vsetkych diskusii z pola diskusii "data"
function vypisDiskusie(data, admin){
	var diskusieText = "";
	$.each(JSON.parse(data), function(index, diskusia){
		if (admin){
			diskusieText += vypisDiskusiu(diskusia, true);
		} 
		else{
			diskusieText += vypisDiskusiu(diskusia, false);
		}
	});
	return diskusieText;
}

// vypisanie jednej diskusie
function vypisDiskusiu(diskusia, admin){
	var margin_top = "";
	if (admin){
		margin_top = " mt-2";
	}
	var diskusiaText = "";
	diskusiaText += '<div class="card my-4" id="diskusia-'+diskusia["id"]+'">';
    diskusiaText += 	vypisShowHeader(diskusia["id"], diskusia["nazov"]);
    diskusiaText += 	'<div class="card-body">';
    diskusiaText += 		'<h5 class="mt-0">'+diskusia["autor"]+'</h5>';
    diskusiaText += 		reformatTextToHtml(diskusia["popis"]);
    diskusiaText += 		'<div id="komentare-'+diskusia['id']+'"></div>';
    diskusiaText += 	'</div>';
    diskusiaText +=     '<div class="card-footer text-left pt-1 pb-1">';
    diskusiaText +=     	vypisVymazDiskusiuBtn(diskusia['id'], admin);
    diskusiaText +=     	'<div class="float-right'+ margin_top + '">' + diskusia['datum_disk'] + '</div>';
    diskusiaText +=     '</div>';
    diskusiaText += '</div>';
    return diskusiaText;
}

// ak je admin true, vypise button na vymazanie diskusie s id "idDiskusie"
// ak je admin false, vrati prazdny string
function vypisVymazDiskusiuBtn(idDiskusie, admin){
	var vymazDiskusiuBtn = '';
	if (admin){
		vymazDiskusiuBtn = 	'<a class="mx-1" style="margin-left: 10px;" href="javascript:vymazDiskusiu('+idDiskusie+')">';
		vymazDiskusiuBtn += 	'<img src="fotky/remove.png" width="40">';
		vymazDiskusiuBtn += '</a>';
	}
	return vymazDiskusiuBtn;
}

// vymaze diskusiu s id "idDiskusie" z databazy a aj jej komentare
function vymazDiskusiu(idDiskusie){
	if (!confirm('Naozaj chcete vymazať diskusiu?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazDiskusiuServlet.php",
		type:"post",
		data:{"id":idDiskusie},
		success: function(data){
			vypisNthStranu(aktualnaStrana);
		}
	});
}

// vylistovanie komentarov pod diskusiu
function ukazKomentare(id, nazov){
	var admin = false;
	$.ajax({
		url:"servlets/getSessionServlet.php",
		type:"post",
		success: function(session){
			session = JSON.parse(session);
			if (session["admin"] == 1){
				admin = true;
			}
			var komentare = $("#komentare-"+id);
			$.ajax({
				url:"servlets/dajKomentareDiskusieServlet.php",
				type:"post",
				data:{"id":id},
				success: function(data){
					var komentareHtml = '';
					komentareHtml += vypisKomentare(data, admin);
					komentareHtml += vypisPridajKomentarForm(id);
					komentare.html(komentareHtml);
					var header = $("#header-"+id);
					header.replaceWith(vypisHideHeader(id, nazov));
				}
			});
		}
	});
}

// skytie komentarov
function skryKomentare(id, nazov){
	var komentare = $("#komentare-"+id);
	komentare.html("");
	var header = $("#header-"+id);
	header.replaceWith(vypisShowHeader(id, nazov));
}

// vypise header diskusie, ktorym sa da vylistovat komentare
function vypisShowHeader(id, nazov){
	var result = "";
	result += 	'<a class="card-header diskusie-header" id="header-'+id+'" href="javascript:ukazKomentare('+id+',\''+nazov+'\');">';
    result += 		'<strong style="font-size: 1.25rem; color:black;">'+nazov+'</strong>';
    result += 		'<strong style="float:right;">&#9660;</strong>';
    result += 	'</a>';
    return result;
}

// vypise header diskusie, ktorym sa daju skyt komentare
function vypisHideHeader(id, nazov){
	var result = "";
	result += 	'<a class="card-header diskusie-header" id="header-'+id+'" href="javascript:skryKomentare('+id+',\''+nazov+'\');">';
    result += 		'<strong style="font-size: 1.25rem;">'+nazov+'</strong>';
    result += 		'<strong style="float:right;">&#9650;</strong>';
    result += 	'</a>';
    return result;
}

// vypise vsetky komentare z pola komentarov "data"
function vypisKomentare(data, admin){
	var margin_top = "";
	if (admin){
		margin_top = " mt-2";
	}
	var komentareText = "";
	$.each(JSON.parse(data), function(index, komentar){
		komentareText += '<div class="card mx-2 my-2">';
        komentareText += 	'<div class="card-body">';
        komentareText += 		'<h5 class="mt-0">'+komentar["meno"]+'</h5>';
        komentareText += 		reformatTextToHtml(komentar["text"]);
        komentareText += 	'</div>';
        komentareText +=	'<div class="card-footer text-left pt-1 pb-1">';
        komentareText +=		vypisVymazKomentarBtn(komentar["id"], admin);
        komentareText += 		'<div class="float-right'+ margin_top +'">';
        komentareText += 			komentar["datum"] + " ";
        komentareText += 			komentar["cas"];
        komentareText += 		'</div>'
        komentareText +=	'</div>';
        komentareText += '</div>';
	});
	return komentareText;
}

// ak je admin true, vypise button na vymazanie komentaru s id "idKomentaru"
// ak je admin false, vrati prazdny string
function vypisVymazKomentarBtn(idKomentaru, admin){
	var vymazKomentarBtn = '';
	if (admin){
		vymazKomentarBtn = 	'<a class="mx-1" style="margin-left: 10px;" href="javascript:vymazKomentar('+idKomentaru+')">';	
		vymazKomentarBtn += 	'<img src="fotky/remove.png" width="40">';
		vymazKomentarBtn += '</a>';
	}
	return vymazKomentarBtn;
}

// vymaze komentar s id "idKomentaru" z databazy a refreshne diskusiu (nech to jako pekne vypada)
function vymazKomentar(idKomentaru){
	if (!confirm('Naozaj chcete vymazať komentár?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazKomentarServlet.php",
		type:"post",
		data:{"id":idKomentaru},
		success: function(data){
			refreshniDiskusiuSId(data);
		}
	});
}

// vypise formular na pridavanie komentarov
function vypisPridajKomentarForm(idDiskusie){
	var result = '';
	result += '<div class="card my-3 mx-2">';
    result += 	'<div class="card-body">';
    result +=		'<label>Meno:</label>';
    result += 		'<input type="text" style="width:100%;" id="pridaj-meno-'+idDiskusie+'">';
    result +=		'<label>Komentár:</label>';
    result += 		'<textarea rows="5" style="width:100%;" id="pridaj-komentar-'+idDiskusie+'"></textarea>';
    result += 	'</div>';
    result +=	'<div class="card-footer text-left">';
    result +=		'<a class="mx-2" href="javascript:pridajKomentar('+idDiskusie+')"><img src="fotky/add.png" width="40"></a>';
    result +=   '</div>';
    result += '</div>';
    return result;
}

// prida komentar do databazy
function pridajKomentar(idDiskusie){
	var menoInput = $("#pridaj-meno-"+idDiskusie);
	var komentarInput = $("#pridaj-komentar-"+idDiskusie);
	var meno = menoInput.val();
	var komentar = komentarInput.val();
	if (meno == "" || komentar == ""){
		return;
	}
	$.ajax({
		url:"servlets/pridajKomentarDiskusiiServlet.php",
		type:"post",
		data:{"idDiskusie":idDiskusie, "meno":meno, "text":komentar},
		success: function(data){
			menoInput.val("");
			komentarInput.val("");
			refreshniDiskusiuSId(idDiskusie);
		}
	});
}

// prekresli diskusiu s id "idDiskusie" a vyroluje jej komentare
function refreshniDiskusiuSId(idDiskusie){
	var diskusiaDiv = $("#diskusia-"+idDiskusie);
	$.ajax({
		url:"servlets/getDiskusiuSIdServlet.php",
		type:"post",
		data:{"idDiskusie":idDiskusie},
		success: function(data){
			var newDiskusiaDiv = "";
			var menoDiskusie = "";
			$.each(JSON.parse(data), function(index, diskusia){		// bude iba 1, ale nejako mi to nefugovalo, ked tam nebol foreach
				$.ajax({
					url:"servlets/getSessionServlet.php",
					type:"post",
					success: function(session){
						session = JSON.parse(session);
						if (session["admin"] == 1){
							newDiskusiaDiv += vypisDiskusiu(diskusia, true);
						}
						else{
							newDiskusiaDiv += vypisDiskusiu(diskusia, false);
						}
						menoDiskusie = diskusia["nazov"];
						diskusiaDiv.replaceWith(newDiskusiaDiv);
						ukazKomentare(idDiskusie, menoDiskusie);
					}
				});
			});
		}
	});
}

function reformatTextToHtml(text){
	return text.replace(/\n/g, "<br />");
}