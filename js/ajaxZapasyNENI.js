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
					zapasy += '<div class="row" id="k' + kolo + '">';
  					zapasy += '<div class="col-sm-12">';
  					zapasy += '<h6>Kolo ' + kolo + '</h6></div></div>';
  					$.each(zapasyKola, function(kluc, zapas){
  						if(zapas['skoreD'] === null){ skoreD = ""; }
  						else{ skoreD = zapas['skoreD']; }
  						if(zapas['skoreH'] === null){ skoreH = ""; }
  						else{ skoreH = zapas['skoreH']; }
  						if(zapas['domaci'].includes("FK CINEMAX Doľany") || zapas['hostia'].includes("FK CINEMAX Doľany")){
  							zapasy += '<div class="row bg-warning-pale">';
						}
						else{
							zapasy += '<div class="row">';
						}
  						zapasy += '<div class="col-sm-1"></div>';
  						zapasy += '<div class="col-sm-2 border-bottom font-weight-bold">' + zapas['datum'] + '</div>';
  						zapasy += '<div class="col-sm-3 text-right border-bottom">' + zapas['domaci'] + '</div>';
  						zapasy += '<div class="col-sm-2 text-center border-bottom">'+skoreD+':'+skoreH+'</div>';
  						zapasy += '<div class="col-sm-3 border-bottom">'+zapas['hostia']+'</div>';
  						zapasy += '<div class="col-sm-1"></div>';
  						zapasy += '</div>';
  					});
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