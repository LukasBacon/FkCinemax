var POCET_POLOZIEK_NA_STRANU = 3;

$(document).ready(function(){
	updatePaginationNavigation();
	vypisNthStranu(1);

});

function updatePaginationNavigation(){
	var paginationNavigation = $(".pagination");
	var paginationNavigationText = "";

	$.ajax({
		url:"servlets/aktualityPocetStranServlet.php",
		type:"post",
		data:{"pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
		success: function(data){
			var pocetStran = data;
			paginationNavigationText += '<li class="page-item">';	
      		paginationNavigationText += '<a class="page-link" href="#" aria-label="Previous">'
        	paginationNavigationText += '<span aria-hidden="true">&laquo;</span>'
        	paginationNavigationText += '<span class="sr-only">Previous</span></a></li>'
     			
			for (var strana = 1; strana <= pocetStran; strana++) {
				paginationNavigationText += '<li class="page-item">';
			    paginationNavigationText += '<a class="page-link" href="javascript:vypisNthStranu('+ strana +'); scrollToAktuality();">' + strana +'</a>';
			    paginationNavigationText += '</li>'; 
			}
			paginationNavigationText += '<li class="page-item">';	
      		paginationNavigationText += '<a class="page-link" href="#" aria-label="Next">'
        	paginationNavigationText += '<span aria-hidden="true">&raquo;</span>'
        	paginationNavigationText += '<span class="sr-only">Next</span></a></li>'
			paginationNavigation.html(paginationNavigationText);
		}
	});		
}

function vypisNthStranu(cisloStrany){
	$.ajax({
		url:"servlets/aktualityGetNthStranuServlet.php",
		type:"post",
		data:{"cisloStrany":cisloStrany, "pocetPoloziekNaStranu":POCET_POLOZIEK_NA_STRANU},
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
		}
	});
}

function vypisAktualityAdmin(data){
	var aktualityText = "";
	$.each(JSON.parse(data), function(index, aktualita){
		aktualityText +=  '<div class="card">';
	    aktualityText +=  '<h5 class="card-header">'+aktualita['nadpis']+'</h5>';
	    aktualityText +=  '<div class="card-body">';
	    aktualityText +=  '<p class="card-text">'+aktualita['text']+'</p>';
	    aktualityText +=  '<input type="hidden" name="akt_id" value="'+aktualita['id']+'">';
	    aktualityText +=  '</div>';
	    aktualityText +=  '<div class="card-footer">';
	    aktualityText +=  '<form novalidate method="post">';
	    aktualityText +=  '<input type="submit" name="vymaz_akt" class="btn btn-admin" value="Vymaž" style="margin-right:10px;">';
	    aktualityText +=  '<input type="submit" name="uprav_akt" class="btn btn-admin" value="Uprav" style="margin-right:10px;">';
	    aktualityText +=  aktualita['datum'];
	    aktualityText +=  '</form>';
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
	    aktualityText +=  '<form novalidate method="post">';
	    aktualityText +=  aktualita['datum'];
	    aktualityText +=  '</form>';
	    aktualityText +=  '</div>';
	    aktualityText +=  '</div>';
	});
	return aktualityText;
}

function scrollToAktuality(){
	$('html, body').animate({scrollTop: ($("#aktuality-section").offset().top) -= 110},500);
}



