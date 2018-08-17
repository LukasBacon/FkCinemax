<?php  
function printPhotosOfAlbumWithId($id, $nazovPriecinku){
	$db = napoj_db();
	$sql =<<<EOF
	SELECT * FROM Fotky WHERE id_album = "$id" ORDER BY datum DESC;
EOF;
	$ret = $db->query($sql);
	$pole = array();
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
		$pole[] = $row;
	}
	$db->close();
	if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1){
		printPhotosWithFormular($nazovPriecinku, $id, $pole);
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

function printPhotosWithFormular($nazovPriecinku, $idAlbumu, $fotky){
	vypis_pridaj_novu($nazovPriecinku, $idAlbumu);

	$pocet = 0;
	foreach ($fotky as $fotka) {
		$url = $fotka['url'];
		if($pocet % 4 == 0){
			echo '<div class="row">';
		}
		echo '<div class="col-sm-3">';
			echo '<div class="card">';
				echo '<a href="javascript:vymazFotku('.$fotka['id'].');">';
					echo '<img id="cancelImgPhoto" src="fotky/cancel.png">';
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

function vypis_pridaj_novu($nazovPriecinku, $idAlbumu){
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj novú fotku</h4>';
			echo '<form class="form-inline d-flex justify-content-center" action="/FkCinemax/servlets/addPhotosServlet.php" method="post" enctype="multipart/form-data">';
				echo '<input type="file" name="files[]" id="file" multiple/> ';
				echo '<input type="text" name="idAlbumu" value="'.$idAlbumu.'" hidden>';
				echo '<input type="text" name="albumName" value="'.$nazovPriecinku.'" hidden>';
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
				echo '<br />';
				echo '<input type="submit" name="submit" value="Pridaj" class="btn btn-success"/>';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>
