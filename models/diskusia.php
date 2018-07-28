<?php
/*
* Trieda DISKUSIA
*/
class DISKUSIA{
  public $ID;
  public $NAZOV;
  public $DATUM_DISK;
  public $POPIS;
  public $AUTOR;

  public function nacitaj($ID,$NAZOV,$DATUM_DISK,$POPIS,$AUTOR){
    $this->ID = $ID;
    $this->NAZOV = $NAZOV;
    $this->$DATUM_DISK = $DATUM_DISK;
    $this->$POPIS = $POPIS;
    $this->$AUTOR = $AUTOR;
  }

    public function vytvor($nazov, $popis, $autor){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Diskusie (nazov, datum_disk, popis, autor)
      VALUES ("$nazov", date('now'), $popis, $autor);
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  /*treba overit ci sa spravi kaskadovo*/
  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Diskusie WHERE id = "$this->ID";
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
        SELECT * FROM Diskusie WHERE id = "$this->ID";
EOF;    
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    return $row;
  }
}

?>