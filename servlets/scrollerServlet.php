<?php
  include('../db.php');
  include('../printers/zapasyPrinter.php');
  $rok = $_POST['rok'];
  $skupina = $_POST['skupina'];
  $kolo = vratPosledneKolo($skupina, $rok);
  if($kolo === FALSE)
  	echo 0;
  else{
  	echo $kolo['kolo'];
  } 
?>