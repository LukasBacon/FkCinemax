<?php
    include('../db.php');
    include('../printers/zapasyPrinter.php');
    $rok = $_POST['rok'];
    $skupina = $_POST['skupina'];
    $kola = vratKolaSoSkupinouARokom($skupina, $rok);

    $jsonZapasy = array();
    foreach ($kola as $kolo) {
    	$jsonZapasy[$kolo] = vratZapasyKola($skupina, $rok, $kolo);
    }

    echo json_encode($jsonZapasy);
?>