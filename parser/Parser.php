<?php
include('simple_html_dom.php');
include('Zapas.php');
include('RiadokTabulky.php');
include('TextUtils.php');

class Parser
{
	private $body = "";
	private $aktualneKolo;

	public $nazovLigy;
	public $rocnik;
	public $zapasy = array();				
	public $tabulka = array();					

 	//main
 	//---------------------------------------------------------------------------------------------------
	public function parsuj($url)
 	{
    if ($url == '' | $url == null){
      return false;
    }
    @$html = file_get_html($url);
    if ($html == false) {
       //echo "<script>console.log('error');</script>";
       return false;
    }
    $this->body = $html->find('body', 0);
    $this->nacitajNazovLigyARocnik();
    $this->nacitajZapasy();
    $this->nacitajTabulku();
    return True;
  }

  private function nacitajNazovLigyARocnik(){
    $divTeam = $this->body->find('header',0)->find('.team',0);
    $ligaArocnik = $divTeam->find('strong',0);

    $indexPomlcky = strpos($ligaArocnik, " -");
    $nazovLigy = substr($ligaArocnik, 0, $indexPomlcky);
    $rocnik = substr($ligaArocnik, $indexPomlcky + 3);

    $this->nazovLigy = $nazovLigy;
    $this->rocnik = $rocnik;
  }

  private function nacitajZapasy(){
    $tableZapasy = $this->body->find('.competition-summary', 0);
    if($tableZapasy == null){
      return false;
    }
    $allTrs = $tableZapasy->find('tr');
    for ($trOrder = 0; $trOrder < sizeof($allTrs); $trOrder++) { 
      $this->spracujTr($allTrs, $trOrder);
    }
  }

  private function spracujTr($allTrs, $trOrder){
    $tr = $allTrs[$trOrder];
    if ($tr->class == "page-breaker"){
      $this->nacitajAkrualneKolo($tr);
      return;
    }
    if ($tr->class == "zapasdatumfull"){
      return;
    }
    $this->nacitajZapas($tr);
  }

  private function nacitajAkrualneKolo($tr){
    $th = $tr->plaintext;
    $index = TextUtils::preskocSlovo($th, 0);
    $this->aktualneKolo = (int)TextUtils::nacitajSlovoPoZnak($th, ".");   
  }

  private function nacitajZapas($tr){
      $zapas = new Zapas;
      $zapas->kolo = $this->aktualneKolo;

      $datumCas = $tr->find('.zapasdatum', 0)->plaintext;
      $zapas->nacitajDatumACas($datumCas);

      $domaci = $tr->find('.domaci', 0)->plaintext;
      $zapas->domaci = $domaci;

      $hostia = $tr->find('.hostia', 0)->plaintext;
      $zapas->hostia = $hostia;

      $tdScore = $tr->find('.score', 0);
      $scoreArray = $tdScore->find(".score");
      $skoreDomaci = $scoreArray[0]->plaintext;
      $skoreHostia = $scoreArray[1]->plaintext;
      $zapas->skoreDomaci = $this->returnValueIfNumericOrNull($skoreDomaci);
      $zapas->skoreHostia = $this->returnValueIfNumericOrNull($skoreHostia);
       
      $this->zapasy[] = $zapas;   //append
  }

  private function nacitajTabulku(){
    $tabulka = $this->body->find(".table-container", 0)->find("table", 0);
    $allTrs = $tabulka->find("tr");
    foreach ($allTrs as $tr) {
      $this->spracujRiadokTabulky($tr);
    }
  }

  private function spracujRiadokTabulky($tr){
    $header = $tr->find("th",0);
    if ($header != null){
      return;
    }  
    $riadokTabulky = new RiadokTabulky;
    $riadokTabulky->poradie = (int)$tr->find("td", 0)->plaintext;
    $riadokTabulky->klub = $tr->find("td", 2)->plaintext;
    $riadokTabulky->z = (int)$tr->find("td", 3)->plaintext;
    $riadokTabulky->v = (int)$tr->find("td", 4)->plaintext;
    $riadokTabulky->r = (int)$tr->find("td", 5)->plaintext;
    $riadokTabulky->p = (int)$tr->find("td", 6)->plaintext;
    $riadokTabulky->skore = $tr->find("td", 7)->plaintext;
    $riadokTabulky->b = (int)$tr->find("td", 8)->plaintext;
    $riadokTabulky->fp = (float)$tr->find("td", 11)->plaintext;

    $this->tabulka[] = $riadokTabulky;    
  } 

  private function returnValueIfNumericOrNull($value){
    $value = trim($value);
    if (!is_numeric($value)){
      return null;
    }
    return (int)$value;
  }
}

?>