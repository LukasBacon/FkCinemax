<?php
include('../db.php');

$id = $_POST['id'];
$meno = $_POST['meno'];
$priezvisko = $_POST['priezvisko'];
$post = $_POST['post'];
$rocnik = $_POST['rocnik'];
$kluby = $_POST['kluby'];

$db = napoj_db();
$sql =<<<EOF
UPDATE Hraci SET meno="$meno", priezvisko="$priezvisko", typ_hraca="$post", rok_narodenia="$rocnik", kluby="$kluby"  WHERE id = "$id";
EOF;
$ret = $db->query($sql);
?>