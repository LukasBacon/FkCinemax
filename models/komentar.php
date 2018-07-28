<?php
/*
* Trieda KOMENTAR
*/
class KOMENTAR{
  public $ID;
  public $MENO;
  public $TEXT;
  public $DATUM;
  public $ID_DISKUSIE;

  public function nacitaj($ID,$MENO,$TEXT,$DATUM,$ID_DISKUSIE){
    $this->ID = $ID;
    $this->MENO = $MENO;
    $this->TEXT = $TEXT;
    $this->DATUM = $DATUM;
    $this->ID_DISKUSIE = $ID_DISKUSIE;
  }

    public function vytvor($meno, $text, $id_diskusie){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Komentare (meno, text, datum, id_diskusie)
      VALUES ("$meno", "$text", date('now'), $id_diskusie);
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Komentare WHERE id = "$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function uprav($meno, $text){
    $db = napoj_db();
    $sql =<<<EOF
       UPDATE Komentare SET meno = "$meno", text = "$text", datum = date('now') WHERE id="$this->ID";
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
        SELECT * FROM Komentare WHERE id = "$this->ID";
EOF;    
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    return $row;
  }
}

?>