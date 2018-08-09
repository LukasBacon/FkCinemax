<?php
include('../db.php');

$pocetPoloziekNaStranu = $_POST['pocetPoloziekNaStranu'];

$db = napoj_db();
$sql =<<<EOF
SELECT count() as count FROM Aktuality;
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();	

$pocetAktualit = $row["count"];
$pocetStran = ceil($pocetAktualit / $pocetPoloziekNaStranu);
echo $pocetStran;
?>