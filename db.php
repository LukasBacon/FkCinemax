<?php
class MyDB extends SQLite3{
  function __construct(){
    $this->open(__DIR__ . DIRECTORY_SEPARATOR .'fkcinemax.db');
  }
}  

function napoj_db(){
  $db = new MyDB();
  if(!$db){
    echo $db->lastErrorMsg();
    return false;
  } 
  else {
    return $db;
  }
}
?>