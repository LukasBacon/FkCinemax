<?php  
function printPhotosOfAlbumWithId($id){
	$db = napoj_db();
	$sql =<<<EOF
	SELECT * FROM Fotky WHERE id_album = "$id";
EOF;
	$ret = $db->query($sql);
	$pole = array();
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
		$pole[] = $row;
	}
	$db->close();
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


?>

