<?php
include('../db.php');

$id = $_POST['id'];
$novyNazov = $_POST['novyNazov'];

$db = napoj_db();

$sql =<<<EOF
UPDATE albumy SET nazov="$novyNazov" WHERE id = "$id";
EOF;
$ret = $db->query($sql);
?>