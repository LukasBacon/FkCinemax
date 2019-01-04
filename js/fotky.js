$(document).ready(function(){
    $('.popupgallery').magnificPopup({ 
        type: 'image', 
        delegate: '.fotoA', 
        closeOnContentClick: false, 
        closeOnBgClick: true,
        image: { 
            verticalFit: true
        }, 
       removalDelay: 300,
       gallery: {
            enabled: true,
            preload: [0,2],
            navigateByImgClick: true,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            tPrev: 'Previous (Left arrow key)',
            tNext: 'Next (Right arrow key)', 
            tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
        }
    }); 

    $('#files').change(function() {
        console.log('klikla som');
        $('#submitFoto').click();
    });

});


// vymazanie fotky ------------------------------------------------------
function vymazFotku(id){
	if (!confirm('Naozaj chcete vymazať fotku?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazFotkuServlet.php",
		type:"post",
		data:{"id":id},
		success: function(data){
			window.location.reload();
		}
	});	
}

