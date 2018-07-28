<?php
/*
* Trieda TABULKA
*/
class TABULKA{
  public $ID;
  public $SKUPINA;
  public $ROK;
  public $ID_KLUBU;
  public $P_ZAPASOV;
  public $P_VYHIER;
  public $P_REMIZ;
  public $P_PREHIER;
  public $SKORE;
  public $BODY;

  public function nacitaj($ID,$SKUPINA,$ROK,$ID_KLUBU,$P_ZAPASOV,$P_VYHIER,$P_REMIZ,$P_PREHIER,$SKORE,$BODY){
    $this->ID = $ID;
    $this->SKUPINA = $SKUPINA;
    $this->ROK = $ROK;
    $this->ID_KLUBU = $ID_KLUBU;
    $this->P_ZAPASOV = $P_ZAPASOV;
    $this->P_VYHIER = $P_VYHIER;
    $this->P_REMIZ = $P_REMIZ;
    $this->P_PREHIER = $P_PREHIER;
    $this->SKORE = $SKORE;
    $this->BODY = $BODY;
  }

   public function vytvor($skupina, $rok, $id_klubu, $p_zapasov, $p_vyhier, $p_remiz, $p_prehier, $skore, $body){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Tabulka (skupina, rok, id_klubu, p_zapasov, p_vyhier, p_remiz, p_prehier, skore, body)
      VALUES ("$skupina", "$rok", "$id_klubu", "$p_zapasov", "$p_vyhier", "$p_remiz", "$p_prehier", 
      "$skore", "$body");
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Tabulka";
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
        SELECT * FROM Tabulka;
EOF;    
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    return $row;
  }
}

?>