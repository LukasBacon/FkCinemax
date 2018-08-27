<?php

function vypisDatum($date){
  $rok = substr($date, 0, 4);
  $mesiac = substr($date, 5, 2);
  $den = substr($date, 8, 2);
  return $den.'.'.$mesiac.'.'.$rok;
}

include('../db.php');
$id = $_POST['id'];

$db = napoj_db();
$sql =<<<EOF
SELECT * FROM Komentare WHERE id_diskusie="$id" ORDER BY datum ASC, cas ASC;
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