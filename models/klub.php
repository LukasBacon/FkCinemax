<?php
/*
* Trieda KLUB
*/
class KLUB{
  public $ID;
  public $NAZOV;

  public function nacitaj($ID,$NAZOV){
    $this->ID = $ID;
    $this->NAZOV = $NAZOV;
  }

   public function vytvor($nazov){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Kluby (nazov)
      VALUES ("$nazov");
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Kluby WHERE id = "$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function uprav($text){
    $db = napoj_db();
    $sql =<<<EOF
       UPDATE Kluby SET nazov = "$nazov" WHERE id="$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function vrat(){
    $db = napoj_db();
    $sel = <<<EOF
        SELECT * FROM Kluby WHERE id = "$this->ID";
EOF;    
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
  }
}

?>