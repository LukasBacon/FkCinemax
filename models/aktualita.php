<?php
/*
* Trieda AKTUALITA
*/
class AKTUALITA{
  public $ID;
  public $NADPIS;
  public $TEXT;
  public $DATUM;

  public function nacitaj($ID,$NADPIS,$TEXT,$DATUM){
    $this->ID = $ID;
    $this->NADPIS = $NADPIS;
    $this->TEXT = $TEXT;
    $this->DATUM = $DATUM;
  }

  public function vytvor(){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Aktuality (nadpis, text, datum)
      VALUES ("$this->NADPIS", "$this->TEXT", date('now'));
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Aktuality WHERE id = "$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function vrat(){
    $db = napoj_db();
    $sql =<<<EOF
        SELECT * FROM Aktuality WHERE id="$this->ID";
EOF;  
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
  }

  public function uprav(){
    $db = napoj_db();
    $sql =<<<EOF
       UPDATE Aktuality SET text = "$this->TEXT", datum = date('now') WHERE id="$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
   $db->close();
  }
}
?>

