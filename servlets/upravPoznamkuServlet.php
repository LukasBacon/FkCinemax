<?php
include('../db.php');
include('../printers/zapasyPrinter.php');
$id = $_POST['id'];
$poznamka = $_POST['poznamka'];

$db = napoj_db();

$sql =<<<EOF
UPDATE Zapasy SET poznamka="$poznamka" WHERE id="$id";
EOF;
$ret = $db->query($sql);
$kolo = vratKolo($id);
echo $kolo['kolo'];
$db->close();
?>