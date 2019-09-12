<?php
/*
* Trieda HRAC
*/
class HRAC{
  public $ID;
  public $MENO;
  public $PRIEZVISKO;
  public $ROK_NARODENIA;
  public $SKUPINA;
  public $TYP_HRACA;
  public $URL;
  public $KLUBY;


  public function nacitaj($ID,$MENO,$PRIEZVISKO,$ROK_NARODENIA,$SKUPINA,$TYP_HRACA,$URL,$KLUBY){
    $this->ID = $ID;
    $this->MENO = $MENO;
    $this->PRIEZVISKO = $PRIEZVISKO;
    $this->ROK_NARODENIA = $ROK_NARODENIA;
    $this->SKUPINA = $SKUPINA;
    $this->TYP_HRACA = $TYP_HRACA;
    $this->URL = $URL;
    $this->KLUBY = $KLUBY;
  }

    public function vytvor($meno, $priezvisko, $rok_narodenia, $skupina, $typ_hraca, $url, $kluby){
    $db = napoj_db();
    $sql =<<<EOF
      INSERT INTO Hraci (meno, priezvisko, rok_narodenia, id_skupiny, typ_hraca, url, kluby)
      VALUES ("$meno", "$priezvisko", "$rok_narodenia", "$skupina", "$typ_hraca", "$url", "$kluby");
EOF;
    $ret = $db->exec($sql);
    $db->close();
  }

  public function vymaz(){
    $db = napoj_db();
    $sql =<<<EOF
      DELETE FROM Hraci WHERE id = "$this->ID";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
  }

  public function uprav($meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby){
    $db = napoj_db();
    $sql =<<<EOF
       UPDATE Hraci SET meno = "$meno", priezvisko = "$priezvisko", typ_hraca = "$typ_hraca",  
       rok_narodenia = "$rok_narodenia", kluby = "$kluby" WHERE id="$this->ID";
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
      SELECT * FROM Hraci WHERE id = "$this->ID";
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
  }

  public function nahraj_fotku(){
    if(($_FILES['obrazok']['type'] != 'image/png') && ($_FILES['obrazok']['type'] != 'image/jpg') && ($_FILES['obrazok']['type'] != 'image/jpeg')   && ($_FILES['obrazok']['type'] != 'image/gif') && ($_FILES['obrazok']['type'] != 'image/bmp')) { }
    else{
      $type = $_FILES['obrazok']['type'];
      $pripona = explode("/", $type);
      if(file_exists('fotky/'.$id.'.png')){
      $subor = 'fotky/' . $id .'.png';
      unlink($subor);
      pridaj_obrazok($id);
    }
    else if(file_exists('fotky/'.$id.'.jpg')){
      $subor = 'fotky/' . $id .'.jpg';
      unlink($subor);
      pridaj_obrazok($id);
    }
    else if(file_exists('fotky/'.$id.'.jpeg')){
      $subor = 'fotky/' . $id .'.jpeg';
      unlink($subor);
      pridaj_obrazok($id);
    }
    else if(file_exists('fotky/'.$id.'.gif')){
      $subor = 'fotky/' . $id .'.gif';
      unlink($subor);
      pridaj_obrazok($id);
    }
    else{
      if (isset($_FILES['obrazok'])) {
        $novy_nazov = '';
        if ($_FILES['obrazok']['error'] == UPLOAD_ERR_OK) {
          if (is_uploaded_file($_FILES['obrazok']['tmp_name'])) {
            $novy_nazov = 'fotky/' . $id .'.'.$pripona[1].'';
            $podarilosa = move_uploaded_file($_FILES['obrazok']['tmp_name'], $novy_nazov);
            if ($podarilosa) { }
            $novy_nazov = '';
          }
        }
      }
    }
  }
}

?>