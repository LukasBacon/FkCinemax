<?php
  include('../db.php');
  include('../printers/tabulkyPrinter.php');
  $rok = $_POST['rok'];
  $skupina = $_POST['skupina'];
  echo $rok . " " . $skupina;
  $nazov = vratKonkretnyRokNazovLigy($skupina, $rok);
  echo $nazov;
?>