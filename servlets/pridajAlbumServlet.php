<?php
include('../funkcie.php');

$nazov = $_POST['nazov'];
echo "som tu";
$nazovPriecinku = replaceSpecialChars($nazov);
$db = napoj_db();
$sql =<<<EOF
	SELECT exists (SELECT * FROM albumy WHERE nazov = "$nazov" OR nazov_priecinku = "$nazovPriecinku") as exist;
EOF;
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC); 
if($row['exist'] == 1) {
	$db->close();	
	echo false;
}
else{
	$sql =<<<EOF
		INSERT INTO Albumy (nazov, nazov_priecinku, datum) VALUES ("$nazov", "$nazovPriecinku", date('now'));
EOF;
	$ret = $db->query($sql);
	$db->close();	

	$cestaKPrieckinku = dirname(getcwd()) . "/fotky/".$nazovPriecinku;
	print_r(array($cestaKPrieckinku));
	if (!file_exists($cestaKPrieckinku)) {
	 	mkdir($cestaKPrieckinku);
	}
	echo true;
}
?>