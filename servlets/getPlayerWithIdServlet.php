<?php  
include('../db.php');
$id = $_POST['id'];

$db = napoj_db();
$sql =<<<EOF
SELECT * FROM Hraci WHERE id="$id";
EOF;
$ret = $db->query($sql);
$pole = array();
$row = $ret->fetchArray(SQLITE3_ASSOC);
$db->close();

echo json_encode($row);

?>