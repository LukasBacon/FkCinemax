<?php
include('../db.php');

function runServlet()
{
	$id = $_POST['id'];
	$db = napoj_db();

	#ziskanie cesty k fotke
	$sql =<<<EOF
	SELECT url FROM Fotky WHERE id = "$id";
EOF;
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);
	$url = $row['url'];
	if ($url == null){
		return;
	}

	# vymazanie fotky z db
	$sql =<<<EOF
	DELETE FROM Fotky WHERE id = "$id";
EOF;
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);

	# vymazanie fotkt z priecinka
	$pathToPhoto = dirname(getcwd()) . "/".$url;
	unlink($pathToPhoto);

runServlet();

?>