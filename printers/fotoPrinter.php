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
	printRow($pole);
}

function printRow($fotky){
	foreach ($fotky as $fotka) {
		$url = $fotka['url'];
		echo '<a href="'.$url.'" class="img-thumbnail"><img src="'.$url.'" /></a>';
	}
}


?>

