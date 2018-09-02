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

function vypis_pridaj_novu($nazovPriecinku, $idAlbumu){
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj novú fotku</h4>';
			echo '<form class="form-inline d-flex justify-content-center" action="/FkCinemax/servlets/addPhotosServlet.php" method="post" enctype="multipart/form-data">';
			echo 'Vyber fotku/y';
				echo '<label class="btn-file" style="width:60px;">';
					echo '<input type="file" class="custom-file-input" style="width:0%;" name="files[]" id="files" accept=".jpg, .jpeg, .png" multiple/> ';
					echo '<span class="custom-file-control"><img src="fotky/foto.png" id="fileLabel" class="buttonImg" width="40"></span>';
				echo '</label>';
				echo '<input type="text" name="idAlbumu" value="'.$idAlbumu.'" hidden>';
				echo '<input type="text" name="albumName" value="'.$nazovPriecinku.'" hidden>';
				$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
				echo '<br>Povolené formáty: png a jpg. Pridaj výber:&nbsp;';
				echo '<input type="submit" id="submitFoto"name="submit" value="Pridaj" class="btn btn-success"/>';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>

