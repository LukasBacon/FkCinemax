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
		printAddPhotosFormular($nazovPriecinku, $id);
	}
	printPhotos($pole);
}

function printPhotos($fotky){
	$pocet = 0;
	foreach ($fotky as $fotka) {
		$url = $fotka['url'];
		if($pocet % 4 == 0){
			echo '<div class="row">';
		}
		echo '<div class="col-sm-3">';
			echo '<a href="'.$url.'">';
			echo '<img id="fotoImg" src="'.$url.'" class="img-thumbnail"/>';
			echo '</a>';
    echo '</div>';
		if($pocet % 4 == 3){
			echo '</div>';
		}
		$pocet += 1;
	}
}

function printAddPhotosFormular($nazovPriecinku, $idAlbumu){
	?>
	<form action="/FkCinemax/servlets/addPhotosServlet.php" method="post" enctype="multipart/form-data">
	<label for="file">Vyber fotky:</label>
	<input type="file" name="files[]" id="file" multiple/> 
	<?php
	echo '<input type="text" name="idAlbumu" value="'.$idAlbumu.'" hidden>';
	echo '<input type="text" name="albumName" value="'.$nazovPriecinku.'" hidden>';
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
	?>
	<br />
	<input type="submit" name="submit" value="Submit" />
	</form>
	<?php
}
?>

