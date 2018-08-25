$(document).ready(function(){

	$("#selectRokZapasy").change(function() {
		var rok = $("#selectRokZapasy").val();
		var skupina = $("#nazovSkupiny").text();
		var divZapasy = $("#zapasy");
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
  							zapasy += '<div class="row  ml-1 mr-1 bg-warning-pale">';
						}
						else{
							zapasy += '<div class="row  ml-1 mr-1">';
						}
  						zapasy += '<div class="col-sm-2 border-bottom font-weight-bold text-center">' + zapas['datum'] + '</div>';
  						zapasy += '<div class="col-sm-3 border-bottom text-center">' + zapas['domaci'] + '</div>';
  						zapasy += '<div class="col-sm-2 text-center border-bottom">'+skoreD+':'+skoreH+'</div>';
  						zapasy += '<div class="col-sm-3 border-bottom text-center">'+zapas['hostia']+'</div>';
  						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
  							if(zapas['poznamka'] == null){
  								zapasy += '<div class="col-sm-2 text-center"><img class="m-2" src="fotky/i-not.png" width="20">';
  							}
  							else{
  								zapasy += '<div class="col-sm-2 text-center ">';
  								zapasy += '<div class="d-inline myTooltip">';
  								zapasy += '<img class="m-2" src="fotky/i.png" width="20">';
  								zapasy += '<span class="myTooltipText">'+zapas['poznamka']+'</span></div>';
  							}
  							$.ajax({
								url:"servlets/getSessionServlet.php",
								type:"post",
								success: function(session){
									session = JSON.parse(session);
									console.log(session['admin']);
									if (session["admin"] == 1){
										console.log('som admin');
										//var fun = infoBox('+zapas['poznamka']+','+zapas['id']')';
										zapasy += '<button class="btn btn-warning p-1" style="font-size:10px; vertical-align:middle;" onclick="infoBox('
										+zapas['poznamka']+','+zapas['id']+')">Pridaj/uprav <br>poznámku</button>';
									}
								} 
  							});
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
		scrollujNaAktualneKolo();
	});

	scrollujNaAktualneKolo();

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
});


function scrollUp(){
	$('html, body').animate({scrollTop : 0},800);
}

function infoBox(poznamka, id){
   	var txt = prompt("Zadajte text poznámky k zápasu (góly, karty, ...):", poznamka);
   	$.ajax({
		url:"servlets/upravPoznamkuServlet.php",
		type:"post",
		data:{"id": id, "poznamka":txt},
		success: function(kolo){
			location.reload();
			console.log('obnovujem stranku');
			alert("Poznámka pridaná.");
			var ids = '#k'+ kolo;
			console.log('scolujem na zmennu poznamku kolo ' + ids)
			$('html, body').animate({
       			scrollTop: ($(ids).offset().top) -= 110},500);
			}
	});
}