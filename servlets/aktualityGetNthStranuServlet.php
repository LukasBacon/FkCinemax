<?php

function vypisDatum($date){
  $rok = substr($date, 0, 4);
  $mesiac = substr($date, 5, 2);
  $den = substr($date, 8, 2);
  return $den.'.'.$mesiac.'.'.$rok;
}

include('../db.php');
$cisloStrany = $_POST['cisloStrany'];
$pocetPoloziekNaStranu = $_POST['pocetPoloziekNaStranu'];

$offset = $pocetPoloziekNaStranu * ($cisloStrany - 1);
$db = napoj_db();
$sql =<<<EOF
SELECT * FROM Aktuality ORDER BY datum DESC, cas DESC LIMIT "$pocetPoloziekNaStranu" OFFSET "$offset";
EOF;
$ret = $db->query($sql);
$pole = array();
while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
	$row['datum'] = vypisDatum($row['datum']);
	$pole[] = $row;
}
$db->close();

echo json_encode($pole);

?>