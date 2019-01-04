<?php
include('../db.php');
$nadpis = $_POST['nadpis'];
$text = $_POST['text'];
$datum = date('Y-m-d');
$cas = date('H:i:s');

$db = napoj_db();
$sql =<<<EOF
INSERT INTO Aktuality (nadpis, text, datum, cas) VALUES ("$nadpis", "$text", "$datum", "$cas");
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();

echo true;
?>