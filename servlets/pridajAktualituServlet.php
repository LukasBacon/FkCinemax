<?php
include('../db.php');

$nadpis = $_POST['nadpis'];
$text = $_POST['text'];
$datum = date('Y-m-d');

$db = napoj_db();
$sql =<<<EOF
INSERT INTO Aktuality (nadpis, text, datum) VALUES ("$nadpis", "$text", "$datum");
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	

echo true;
?>