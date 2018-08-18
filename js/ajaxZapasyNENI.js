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
  						zapasy += '<div class="col-sm-2"></div>';
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
				console.log("data= "+ data);
				if(data != 0){
					var id = '#k'+ data;
					console.log(id);
					$('html, body').animate({
        				scrollTop: ($(id).offset().top) -= 110},500);
				}
			}
		});
	}


});