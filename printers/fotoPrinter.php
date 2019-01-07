<?php  
function printPhotosOfAlbumWithId($id){
	$db = napoj_db();
	$sql =<<<EOF
	SELECT * FROM Fotky WHERE id_album = "$id" ORDER BY cas DESC, url ASC;
EOF;
	$ret = $db->query($sql);
	$pole = array();
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
		$pole[] = $row;
	}
	$db->close();
	if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
		printPhotosWithFormular($id, $pole);
	}
	else{
		printPhotos($pole);
	}
}

function printPhotos($fotky){
	$pocet = 0;
	foreach ($fotky as $fotka) {
		$url = $fotka['url'];
		if($pocet % 4 == 0){
			echo '<div class="row">';
		}
		echo '<div class="col-sm-3">';
			echo '<a class="fotoA" href="'.$url.'">';
				echo '<img id="fotoImg" src="'.$url.'" class="img-thumbnail"/>';
			echo '</a>';
    echo '</div>';
		if($pocet % 4 == 3){
			echo '</div>';
		}
		$pocet += 1;
	}
}

function printPhotosWithFormular($idAlbumu, $fotky){
	vypis_pridaj_novu($idAlbumu);

	$pocet = 0;
	foreach ($fotky as $fotka) {
		$url = $fotka['url'];
		if($pocet % 4 == 0){
			echo '<div class="row">';
		}
		echo '<div class="col-sm-3">';
			echo '<div class="card">';
				echo '<a href="javascript:vymazFotku('.$fotka['id'].');" style="z-index:1;">';
					echo '<img id="cancelImgPhoto" src="fotky/remove.png">';
				echo '</a>';
				echo '<a class="fotoA" href="'.$url.'">';
					echo '<img id="fotoImg" src="'.$url.'" class="img-thumbnail"/>';
				echo '</a>';
			echo '</div>';
    echo '</div>';
		if($pocet % 4 == 3){
			echo '</div>';
		}
		$pocet += 1;
	}
}

function vypis_pridaj_novu($idAlbumu){
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj novú fotku</h4>';
			echo 'Povolené formáty: png a jpg <br>';
			echo '<form class="form-inline d-flex justify-content-center" action="/FkCinemax/servlets/addPhotosServlet.php" method="post" enctype="multipart/form-data">';
				echo '<label class="btn-file withHover" style="width:60px;">';
					echo '<input type="file" class="custom-file-input" style="width:0%;" name="files[]" id="files" accept=".jpg, .jpeg, .png" multiple/> ';
					echo '<span class="custom-file-control"><img src="fotky/foto.png" id="fileLabel" class="buttonImg withHover" width="40"></span>';
				echo '</label>';
				echo '<input type="text" name="idAlbumu" value="'.$idAlbumu.'" hidden>';
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
				echo '<input type="submit" id="submitFoto" name="submit" value="Pridaj" class="btn btn-success withHover" hidden/>';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>

