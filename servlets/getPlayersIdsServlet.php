<?php
include('../db.php');

$skupina = $_POST['skupina'];

$db = napoj_db();
$sql =<<<EOF
SELECT h.id FROM Hraci as h JOIN Skupiny as s ON h.id_skupiny = s.id WHERE s.kod="$skupina";
EOF;
$ret = $db->query($sql);	
$pole = array();
while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
	$pole[] = $row;
}
$db->close();

echo json_encode($pole);
?>