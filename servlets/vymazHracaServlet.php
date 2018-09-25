<?php
include('../db.php');

$id = $_POST['id'];

$db = napoj_db();

#ziskanie cesty k fotke
$sql =<<<EOF
SELECT url FROM Hraci WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$url = $row['url'];
if ($url != null){
	# vymazanie fotky z priecinka
	$pathToPhoto = dirname(getcwd()) . "/".$url;
	unlink($pathToPhoto);
}

$sql =<<<EOF
DELETE FROM Hraci WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	

echo true;
?>