$(document).ready(function(){

	scrollujNaAktualneKolo();

	$("#selectRokZapasy").change(function() {
		var rok = $("#selectRokZapasy").val();
		var skupina = $("#nazovSkupiny").text();
		var divZapasy = $("#zapasy");
  	$.ajax({	
			url:"servlets/getSessionServlet.php",
			type:"post",
			success: function(session){
				session = JSON.parse(session);
				if (session["admin"] == 1){
					adminVypis(rok, skupina, divZapasy);
				}
				else{
					vypis(rok, skupina, divZapasy);
				} 
			}
  	});
  	$.ajax({
		url:"servlets/nazovLigyServlet.php",
		type:"post",
		data:{"rok":rok, "skupina":skupina},
		success: function(data){
			console.log(data);
			$("#nazovLigy").text(data);
		}
	});
  	scrollujNaAktualneKolo();
  });	

	function adminVypis(rok, skupina, divZapasy){
		$.ajax({
			url:"servlets/zapasyServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			success: function(data){
				divZapasy.empty();
				
				var zapasy = "";
				$.each(JSON.parse(data), function(kolo, zapasyKola){
					zapasy += '<div class="row ml-1 mr-1"  id="k' + kolo + '">';
					zapasy += '<div class="col-sm-12 bg-dark text-center text-white">';
					zapasy += '<h6 class="mb-1 mt-1"><strong>Kolo ' + kolo + '</strong></h6></div></div>';
					$.each(zapasyKola, function(kluc, zapas){
						if(zapas['skoreD'] === null){ skoreD = ""; }
						else{ skoreD = zapas['skoreD']; }
						if(zapas['skoreH'] === null){ skoreH = ""; }
						else{ skoreH = zapas['skoreH']; }
						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
							zapasy += '<div class="row border-bottom ml-1 mr-1 bg-warning-pale">';
						}
						else{
							zapasy += '<div class="row border-bottom mr-1 ml-1">';
						}
						zapasy += '<div class="col-sm-2 font-weight-bold text-center">' + vypisDatum(zapas['datum']) + '</div>';
						zapasy += '<div class="col-sm-3 text-center">' + zapas['domaci'] + '</div>';
						zapasy += '<div class="col-sm-2 text-center">'+skoreD+':'+skoreH+'</div>';
						zapasy += '<div class="col-sm-3 text-center">'+zapas['hostia']+'</div>';
						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
							if(zapas['poznamka'] == null){
								zapasy += '<div class="col-sm-2 text-center ">';
								zapasy += '<div class="d-inline myTooltip">';
								zapasy += '<img id="infoImg'+zapas['kolo']+'" class="m-2" src="fotky/i-not.png" width="20">';
								zapasy += '<span hidden id="infoText'+zapas['kolo']+'" class="myTooltipText"></span></div>';
							}
							else{
								zapasy += '<div class="col-sm-2 text-center ">';
								zapasy += '<div class="d-inline myTooltip">';
								zapasy += '<img id="infoImg'+zapas['kolo']+'" class="m-2" src="fotky/i.png" width="20">';
								zapasy += '<span id="infoText'+zapas['kolo']+'" class="myTooltipText">'+zapas['poznamka']+'</span></div>';
							}
							zapasy += '<a class="buttonImg" href="javascript:infoBox(\''+zapas['poznamka']+'\','+zapas['id']+')"><img class="withHover" src="fotky/edit.png" width="30"></a>'
							zapasy += '</div>';
						}
						else{
							zapasy += '<div class="col-sm-2"></div>';
						}
						zapasy += '</div>';
					});
					zapasy += '<br>';
				});
				divZapasy.html(zapasy);	
			}
		});
	}

	function vypis(rok,skupina, divZapasy){
		$.ajax({
			url:"servlets/zapasyServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			success: function(data){
				divZapasy.empty();
				var zapasy = "";
				$.each(JSON.parse(data), function(kolo, zapasyKola){
					zapasy += '<div class="row ml-1 mr-1"  id="k' + kolo + '">';
					zapasy += '<div class="col-sm-12 bg-dark text-center text-white">';
					zapasy += '<h6 class="mb-1 mt-1"><strong>Kolo ' + kolo + '</strong></h6></div></div>';
					$.each(zapasyKola, function(kluc, zapas){
						if(zapas['skoreD'] === null){ skoreD = ""; }
						else{ skoreD = zapas['skoreD']; }
						if(zapas['skoreH'] === null){ skoreH = ""; }
						else{ skoreH = zapas['skoreH']; }
						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
							zapasy += '<div class="row ml-1 mr-1 border-bottom bg-warning-pale">';
						}
						else{
							zapasy += '<div class="row border-bottom mr-1 ml-1">';
						}
						zapasy += '<div class="col-sm-2 font-weight-bold text-center">' + vypisDatum(zapas['datum']) + '</div>';
						zapasy += '<div class="col-sm-3 text-center">' + zapas['domaci'] + '</div>';
						zapasy += '<div class="col-sm-2 text-center">'+skoreD+':'+skoreH+'</div>';
						zapasy += '<div class="col-sm-3 text-center">'+zapas['hostia']+'</div>';
						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
							if(zapas['poznamka'] === null){
								zapasy += '<div class="col-sm-2 text-center ">';
								zapasy += '<div class="d-inline myTooltip">';
								zapasy += '<img id="infoImg'+zapas['kolo']+'" class="m-2" src="fotky/i-not.png" width="20">';
								zapasy += '<span hidden id="infoText'+zapas['kolo']+'" class="myTooltipText">'+zapas['poznamka']+'</span></div>';
							}
							else{
								zapasy += '<div class="col-sm-2 text-center ">';
								zapasy += '<div class="d-inline myTooltip">';
								zapasy += '<img id="infoImg'+zapas['kolo']+'" class="m-2" src="fotky/i.png" width="20">';
								zapasy += '<span id="infoText'+zapas['kolo']+'" class="myTooltipText">'+zapas['poznamka']+'</span></div>';
							}
							zapasy += '</div>';
						}
						else{
							zapasy += '<div class="col-sm-2"></div>';
						}
						zapasy += '</div>';
					});
					zapasy += '<br>';
				});
				divZapasy.html(zapasy);	
			}
		});
	}

	/*Podla skupiny, roku a dnesneho datumu ziskam zo servletu aktualne kolo. Odscrollujem  na toto kolo.*/
	function scrollujNaAktualneKolo(){
		var skupina = $("#nazovSkupiny").text();
		var rok = $("#selectRokZapasy").val();
		$.ajax({
			url:"servlets/scrollerServlet.php",
			type:"post",
			data:{"rok":rok, "skupina":skupina},
			success: function(data){
				if(data != 0){
					var id = '#k'+ data;
					console.log('scrollujem na aktualne kolo:' + id);
					$('html, body').animate({
        				scrollTop: ($(id).offset().top) -= 110},500);
				}
			}
		});
	}

	function vypisDatum(datetime){
		var rok = datetime.substring(0,4);
		var mesiac = datetime.substring(5,7) ;
		var den = datetime.substring(8,10);
		var cas = datetime.substring(11);
		return den + '.' + mesiac + '.' + rok + ' ' + cas;
	}

});
/*
zapas->odscrolluje na vrch stranky s animaciou
*/
function scrollUp(){
	$('html, body').animate({scrollTop : 0},800);
}

/*
zapasy -> admin -> info. Po kliknuti na pridaj poznamku sa spusti funcia. Zobrazi sa prompt s predvyplnenou poznamkou.
Cez servlet upravim poznamku v databaze. Ak je nova poznamka prazdna, i-cko zosedivie a nezobrazi text po nadideni.
Inak i-cko zmodrie a zobrazi sa novy text poznamky.
*/
function infoBox(poznamka, id){
	if(poznamka === 'null'){
		poznamka = "";
	}
	var txt = prompt("Zadajte text poznámky k zápasu (góly, karty, ...):", poznamka);
	if(txt != null){
	 	console.log("text je neprazdny:" + txt);
	 	$.ajax({
			url:"servlets/upravPoznamkuServlet.php",
			type:"post",
			data:{"id": id, "poznamka":txt},
			success: function(kolo){
				var img = "#infoImg" + kolo;
				var text = "#infoText" + kolo;
				if(txt == ""){
					$(text).attr('hidden', true);
					$(img).attr('src', "fotky/i-not.png");
				}
				else{
					$(img).attr('src', "fotky/i.png");
					$(text).text(txt);
					$(text).removeAttr('hidden');
				}
			}
		});
	}
}