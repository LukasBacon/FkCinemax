$(document).ready(function(){
	$("#inputNewAlbum").on('keyup keypress', function(event){
		var keyCode = event.keyCode || event.which;
		if (event.keyCode === 13) {
			event.preventDefault();
			pridajAlbum();
			return false;
		}
	});
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
	albumInput.val(albumNazov.text());
	upravAlbumBtn.replaceWith('<a id="potvrdNazovBtn'+id+'" class="d-inline float-right" href="javascript:potvrdNazov('+id+');"><img class="buttonImg withHover" src="fotky/ok.png" width="40"></a>');		
}

function potvrdNazov(id){
	var albumInput =  $('#upravAlbumInput'+id);
	var albumNazov = $('#albumNazov'+id);
	var potvrdNazovBtn = $('#potvrdNazovBtn'+id);
	var nazov = albumInput.val();
	if(nazov !== ""){
		albumNazov.removeAttr('hidden');
		albumInput.prop('hidden',true);
		potvrdNazovBtn.replaceWith('<a id="upravAlbumBtn'+id+'" class="d-inline float-right" href="javascript:upravAlbum('+id+');"><img class="buttonImg withHover" src="fotky/edit.png" width="40"></a>');
		$.ajax({
			url:"servlets/upravNazovAlbumuServlet.php",
			type:"post",
			data:{"id":id, "novyNazov": nazov},
			success: function(data){
				if (data == false){
					alert("Album s týmto názvom už existuje.");
				}
				else{
					albumNazov.text(nazov);
				}
			}
		});	
	}
}

function pridajAlbum(){
	var nazov = $('#inputNewAlbum').val();
	if(nazov != ""){
		$.ajax({
			url:"servlets/pridajAlbumServlet.php",
			type:"post",
			data:{"nazov":nazov},
			success: function(data){
				if(data == false){
					alert("Album so zadaným názvom už existuje. Zmeňte ho.");
				}
				else{
					location.reload();
					$('#inputNewAlbum').val("");
				}
			},
			error: function(data){
				console.log("error:" + data);
			}
		});		
	}
}


