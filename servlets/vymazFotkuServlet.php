<?php
include('../db.php');

$id = $_POST['id'];

$db = napoj_db();
$sql =<<<EOF
DELETE FROM Fotky WHERE id = "$id";
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	
?>