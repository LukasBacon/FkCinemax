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
if ($url != null && strpos($url,'face.png') === false) {
	# prehodenie fotky do zaloha priecinku
	/*$pathToPhoto = dirname(getcwd()) . "/".$url;
	unlink($pathToPhoto);*/
	$index = strrpos($url, '/');
	$new_url = 'fotky/zaloha'.substr($url, $index);
	rename(dirname(getcwd()) . "/". $url, dirname(getcwd()) . "/". $new_url);
}

$sql =<<<EOF
DELETE FROM Hraci WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	

echo true;
?>