<?php
include('../db.php');

$id = $_POST['id'];
$nadpis = $_POST['nadpis'];
$text = $_POST['text'];

$db = napoj_db();
$sql =<<<EOF
UPDATE Aktuality SET nadpis="$nadpis", text="$text", datum=date('now') WHERE id = "$id";
EOF;
$ret = $db->query($sql);
?>