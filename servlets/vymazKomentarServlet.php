<?php
include('../db.php');

$id = $_POST['id'];

$db = napoj_db();

$sql =<<<EOF
SELECT id_diskusie FROM Komentare WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$idDiskusie = $row["id_diskusie"];

$sql =<<<EOF
DELETE FROM Komentare WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);

$db->close();	

echo $idDiskusie;
?>