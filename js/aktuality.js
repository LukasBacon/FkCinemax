var POCET_POLOZIEK_NA_STRANU = 3;
var aktualnaStrana = 1;
var pocetStran = 10000000;

$(document).ready(function(){
	updatePaginationNavigation();
	vypisNthStranu(1, false);
});

// pridanie akttuality ----------------------------------------------------- 
function pridajAktualitu(){
	var nadpis = $("#pridaj-nadpis");
	var text = $("#pridaj-text");
	var infoDiv = $("#info-div");
	var nadpisText = nadpis.val();
	var textText = text.val();
	if (nadpisText.length == 0 || textText.length == 0){
		infoDiv.html("Jedno z polí je prázdne.");
		return;
	}
	$.ajax({
		url:"servlets/pridajAktualituServlet.php",
		type:"post",
		data:{"nadpis":nadpisText, "text":textText},
		success: function(data){
			nadpis.val("");
			text.val("");
			infoDiv.html("Aktualita pridaná.");
		}
	});	
	window.location.reload();
}

// vymazanie aktuality ------------------------------------------------------
function vymazAktualitu(id){
	if (!confirm('Naozaj chcete vymazať aktualitu?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazAktualituServlet.php",
		type:"post",
		data:{"id":id},
		success: function(data){
			window.location.reload();
		}
	});	
}

// update aktuality --------------------------------------------------------
function upravAktualitu(id){
	var upravBtn = $("#upravBtn-" + id);
	var vymazBtn = $("#vymazBtn-" + id);
	var nadpis = $("#aktualita-nadpis-" + id);
	var text = $("#aktualita-text-" + id);
	var nadpisText = nadpis.text();
	var textText = text.text();

	nadpis.replaceWith('<input class="card-header" id="new-nadpis-'+id+'" type="text" value="'+nadpisText+'">');		
	text.replaceWith('<textarea id="new-text-'+id+'" rows="6" style="width:100%;">'+textText+'</textarea>');
	vymazBtn.prop('hidden',true);
	upravBtn.replaceWith('<a class="btn btn-admin" id="potvrdBtn-'+id+'" style="margin-right:10px;" href="javascript:potvrdAktualitu('+id+');">Potvrď</a>');
}


function potvrdAktualitu(id){
	var vymazBtn = $("#vymazBtn-" + id);
	var potvrdBtn = $("#potvrdBtn-" + id);
	var newNadpis = $("#new-nadpis-" + id);
	var newText = $("#new-text-" + id);
	var newNadpisText = newNadpis.val();
	var newTextText = newText.val();
	
	newNadpis.replaceWith('<h5 class="card-header" id="aktualita-nadpis-'+id+'">'+newNadpisText+'</h5>');		
	newText.replaceWith('<p class="card-text" id="aktualita-text-'+id+'">'+newTextText+'</p>');
	vymazBtn.prop('hidden',false);
	potvrdBtn.replaceWith('<a class="btn btn-admin" id="upravBtn-'+id+'" style="margin-right:10px;" href="javascript:upravAktualitu('+id+');">Uprav</a>')

	$.ajax({
		url:"servlets/upravAktualituServlet.php",
		type:"post",
		data:{"id":id,"nadpis":newNadpisText,"text":newTextText}
	});		
}

// paginaton ---------------------------------------------------------------
function updatePaginationNavigation(){
	var paginationNavigation = $(".pagination");
	var paginationNavigationText = "";

	$.ajax({
		url:"servlets/aktualityPocetStranServlet.php",
		type:"post",
		data:{"pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
		success: function(data){
			pocetStran = data;
			paginationNavigationText += '<li class="page-item pagination-prev"><a class="page-link" href="javascript:vypisPrevStranu();">&laquo;</a></li>';
			for (var strana = 1; strana <= pocetStran; strana++) {
				paginationNavigationText += '<li class="page-item" id="pagination-nav-page-' + strana + '">';
			    paginationNavigationText += '<a class="page-link" href="javascript:vypisNthStranu('+ strana +', true);">' + strana +'</a>';
			    paginationNavigationText += '</li>'; 
			}
			paginationNavigationText += '<li class="page-item pagination-next"><a class="page-link" href="javascript:vypisNextStranu();">&raquo;</a></li>';
			paginationNavigation.html(paginationNavigationText);
			highlightActualPage(aktualnaStrana);
			$(".pagination").rPage();
		}
	});	
}

function vypisNthStranu(cisloStrany, scroll){
	if (cisloStrany < 1 || cisloStrany > pocetStran){
		return;
	}
	$(".pagination").rPage();
	aktualnaStrana = cisloStrany;
	$.ajax({
		url:"servlets/aktualityGetNthStranuServlet.php",
		type:"post",
		data:{"cisloStrany":aktualnaStrana, "pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
		success: function(data){
			var strana = data;
			var aktuality = $(".aktualityPage");
			aktuality.html("");
			var aktualityText = '<div class="aktualityPage">';
			$.ajax({
				url:"servlets/getSessionServlet.php",
				type:"post",
				success: function(session){
					session = JSON.parse(session);
					if (session["admin"] == 1){
						aktualityText = vypisAktualityAdmin(strana);
					}
					else{
						aktualityText = vypisAktualityUser(strana);
					}
					aktualityText += '</div>'
					aktuality.html(aktualityText);
				}
			});
			updatePaginationNavigation();
		}
	});
	if (scroll === true){
		scrollToAktuality();
	}
}

function vypisPrevStranu(){
	vypisNthStranu(aktualnaStrana-1, true);
}

function vypisNextStranu(){
	vypisNthStranu(aktualnaStrana+1, true);
}

function highlightActualPage(aktualnaStrana){
	$(".page-item").removeClass("active");
	$("#pagination-nav-page-" + aktualnaStrana).addClass("active");
}

function vypisAktualityAdmin(data){
	var aktualityText = "";
	$.each(JSON.parse(data), function(index, aktualita){
		aktualityText +=  '<div class="card" id="aktualita-'+aktualita['id']+'">';
	    aktualityText +=  '<h5 class="card-header" id="aktualita-nadpis-'+aktualita['id']+'">'+aktualita['nadpis']+'</h5>';
	    aktualityText +=  '<div class="card-body">';
	    aktualityText +=  '<p class="card-text" id="aktualita-text-'+aktualita['id']+'">'+aktualita['text'].replace(/\n/g, "<br />")+'</p>';
	    aktualityText +=  '<input type="hidden" name="akt_id" value="'+aktualita['id']+'">';
	    aktualityText +=  '</div>';
	    aktualityText +=  '<div class="card-footer">';
	    aktualityText +=  '<a class="btn btn-admin" id="vymazBtn-'+aktualita['id']+'" style="margin-right:10px;" href="javascript:vymazAktualitu('+aktualita['id']+');">Vymaž</a>';
	    aktualityText +=  '<a class="btn btn-admin" id="upravBtn-'+aktualita['id']+'" style="margin-right:10px;" href="javascript:upravAktualitu('+aktualita['id']+');">Uprav</a>';
	    aktualityText +=  aktualita['datum'];
	    aktualityText +=  '</div>';
	    aktualityText +=  '</div>';
	});
	return aktualityText;
}

function vypisAktualityUser(data){
	var aktualityText = "";
	$.each(JSON.parse(data), function(index, aktualita){
		aktualityText +=  '<div class="card">';
	    aktualityText +=  '<h5 class="card-header">'+aktualita['nadpis']+'</h5>';
	    aktualityText +=  '<div class="card-body">';
	    aktualityText +=  '<p class="card-text">'+aktualita['text']+'</p>';
	    aktualityText +=  '<input type="hidden" name="akt_id" value="'+aktualita['id']+'">';
	    aktualityText +=  '</div>';
	    aktualityText +=  '<div class="card-footer">';
	    aktualityText +=  aktualita['datum'];
	    aktualityText +=  '</div>';
	    aktualityText +=  '</div>';
	});
	return aktualityText;
}

function scrollToAktuality(){
	$('html, body').animate({scrollTop: ($("#aktuality-section").offset().top) -= 110},500);
}



