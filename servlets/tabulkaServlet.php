<?php
    include('../db.php');
    include('../printers/tabulkyPrinter.php');
    $rok = $_POST['rok'];
    $skupina = $_POST['skupina'];
    $pole = nacitajKonkretnyRokTabulku($skupina, $rok);	
    echo json_encode($pole);
?>