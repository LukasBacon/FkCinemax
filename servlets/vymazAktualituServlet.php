<?php
include('../db.php');

$id = $_POST['id'];

$db = napoj_db();
$sql =<<<EOF
DELETE FROM Aktuality WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	

echo true;
?>