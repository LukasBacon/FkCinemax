<?php
include('../db.php');

$idDiskusie = $_POST['idDiskusie'];
$meno = $_POST['meno'];
$text = $_POST['text'];

$db = napoj_db();
$sql =<<<EOF
INSERT INTO Komentare (meno, text, datum, cas, id_diskusie) VALUES ("$meno", "$text", date('now'), time('now'), "$idDiskusie");
EOF;
$db->query($sql);
$db->close();	

echo true;
?>