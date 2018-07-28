<?php
/*
* Trieda ZAPAS
*/
class ZAPAS{
  public $ID;
  public $DATUM;
  public $ROK;
  public $ID_DOMACI;
  public $ID_HOSTIA;
  public $SKORE_D;
  public $SKORE_H;
  public $KOLO;
  public $POZNAMKA;
  public $SKUPINA;

  public function nacitaj($ID,$DATUM,$ROK,$ID_DOMACI,$ID_HOSTIA,$SKORE_D,$SKORE_H,$KOLO,$POZNAMKA,$SKUPINA){
    $this->ID = $ID;
    $this->DATUM = $DATUM;
    $this->ROK = $ROK;
    $this->ID_DOMACI = $ID_DOMACI;
    $this->ID_HOSTIA = $ID_HOSTIA;
    $this->SKORE_D = $SKORE_D;
    $this->SKORE_H = $SKORE_H;
    $this->KOLO = $KOLO;
    $this->POZNAMKA = $POZNAMKA;
    $this->SKUPINA = $SKUPINA;
  }

    public function vytvor($rok, $id_domaci, $id_hostia, $skore_d, $skore_h, $kolo, $skupina){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Zapasy (datum, rok, id_domaci, id_hostia, skore_d, skore_h, kolo, skupina)
      VALUES (date('now'), "$rok", "$id_domaci", "$id_hostia", "$skore_d", "$skore_h", "$kolo", "$skupina");
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Zapasy WHERE id = "$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function uprav($poznamka){
    $db = napoj_db();
    $sql =<<<EOF
       UPDATE Zapasy SET poznamka = "$poznamka", datum = date('now') WHERE id="$this->ID";
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
        SELECT * FROM Zapasy WHERE id = "$this->ID";
EOF;    
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    return $row;
  }
}

?>