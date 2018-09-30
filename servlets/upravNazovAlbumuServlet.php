<?php
include('../db.php');

$id = $_POST['id'];
$novyNazov = $_POST['novyNazov'];

$db = napoj_db();

$sql =<<<EOF
	SELECT exists (SELECT * FROM albumy WHERE nazov = "$novyNazov") as exist;
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC); 
if($row['exist'] == 1) {
	$db->close();	
	echo false;
}
else{
	$sql2 =<<<EOF
	UPDATE albumy SET nazov="$novyNazov" WHERE id = "$id";
EOF;
	$ret = $db->query($sql2);
	$db->close();
	echo true;
}

?>