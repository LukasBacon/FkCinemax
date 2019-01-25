<?php

function vypisDatum($date){
  $rok = substr($date, 0, 4);
  $mesiac = substr($date, 5, 2);
  $den = substr($date, 8, 2);
  return $den.'.'.$mesiac.'.'.$rok;
}

include('../db.php');
$idDiskusie = $_POST['idDiskusie'];

$db = napoj_db();
$sql =<<<EOF
SELECT *, (SELECT count(*) FROM Komentare WHERE id_diskusie = "$idDiskusie") as pocet FROM Diskusie WHERE id="$idDiskusie"
EOF;
$pole = array();
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
$row['datum_disk'] = vypisDatum($row['datum_disk']);
$pole[] = $row;
$db->close();

echo json_encode($pole);

?>