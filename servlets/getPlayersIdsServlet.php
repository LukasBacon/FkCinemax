<?php
include('../db.php');

$skupina = $_POST['skupina'];

$db = napoj_db();
$sql =<<<EOF
SELECT id FROM Hraci as h JOIN Skupiny as S ON h.id_skupiny=s.id WHERE s.kod="$skupina";
EOF;
$ret = $db->query($sql);	
$pole = array();
while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
	$pole[] = $row;
}
$db->close();

echo json_encode($pole);
?>