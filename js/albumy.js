$(document).ready(function(){

});


// vymazanie albumu ------------------------------------------------------
function vymazAlbum(id){
	if (!confirm('Naozaj chcete vymazať album?')){
		return;
	}
	$.ajax({
		url:"servlets/vymazAlbumServlet.php",
		type:"post",
		data:{"id":id},
		success: function(data){
			window.location.reload();
		}
	});	
}

// update albumu --------------------------------------------------------
function upravAlbum(id){
	var albumInput =  $('#upravAlbumInput'+id);
	var albumNazov = $('#albumNazov'+id);
	var upravAlbumBtn = $('#upravAlbumBtn'+id);
	albumNazov.prop('hidden',true);
	albumInput.removeAttr('hidden'); 
	//albumInput.prop('placeholder','staryNazov');
	upravAlbumBtn.replaceWith('<a id="potvrdNazovBtn'+id+'" class="d-inline float-right btn btn-admin" href="javascript:potvrdNazov('+id+');">Potvrď</a>');
}

function potvrdNazov(id){
	var albumInput =  $('#upravAlbumInput'+id);
	var albumNazov = $('#albumNazov'+id);
	var potvrdNazovBtn = $('#potvrdNazovBtn'+id);
	var nazov = albumInput.val();
	if(nazov !== ""){
		albumNazov.text(nazov);
		albumNazov.removeAttr('hidden');
		albumInput.prop('hidden',true);
		potvrdNazovBtn.replaceWith('<a id="upravAlbumBtn'+id+'" class="d-inline float-right btn btn-admin" href="javascript:upravAlbum('+id+');">Uprav</a>');
		$.ajax({
			url:"servlets/upravNazovAlbumuServlet.php",
			type:"post",
			data:{"id":id, "novyNazov": nazov}
		});	
	}
}