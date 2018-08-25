<?php
include('../db.php');

$id = $_POST['id'];

$db = napoj_db();

$sql =<<<EOF
DELETE FROM Komentare WHERE id_diskusie="$id";
EOF;
$ret = $db->query($sql);
$ret->fetchArray(SQLITE3_ASSOC);

$sql =<<<EOF
DELETE FROM Diskusie WHERE id="$id";
EOF;
$ret = $db->query($sql);
$ret->fetchArray(SQLITE3_ASSOC);

$db->close();	

echo true;
?>