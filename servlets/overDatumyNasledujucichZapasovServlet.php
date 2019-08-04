<?php
include('../db.php');
include('../dbLoader.php');

$dbLoader = new dbLoader;

echo json_encode($dbLoader->overDatumyNasledujucichNZapasov(10, "Seniori"));

?>