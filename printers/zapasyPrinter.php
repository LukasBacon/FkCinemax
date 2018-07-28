<?php
/*funkcie v index.php*/
function vratnasledujuciZapas($skupina){
	$db = napoj_db();
  $sql =<<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, z.skupina, l.nazov 
    FROM Zapasy as z JOIN Ligy as l ON z.rok = l.rok AND z.skupina = l. skupina
		WHERE datetime(z.datum) > datetime('now') AND z.skupina = "$skupina" AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum asc
		LIMIT 1;
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $db->close();	
  return $row;	
}

function vratPoslednyZapas($skupina){
	$db = napoj_db();
  $sql =<<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, z.skupina, l.nazov 
    FROM Zapasy as z JOIN Ligy as l ON z.rok = l.rok AND z.skupina = l. skupina
		WHERE datetime(z.datum) < datetime('now') AND z.skupina = "$skupina" AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum desc
		LIMIT 1;
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $db->close();	
  return $row;	
}

function vypisNasledujuceZapasy(array $skupiny){
	foreach ($skupiny as $skupina) {
		$zapas = vratnasledujuciZapas($skupina);
		if($zapas === FALSE){
			return;
		}
		/*kvoli tomu ze 3 kola v pripravke sa hraju v jeden den - kolo 1,2,3 = kolo 1*/
		if($skupina === 'Pripravka'){
			$kolo = floor(($zapas['kolo'] -1) / 3) + 1;
		}
		else{
			$kolo = $zapas['kolo'];
		}
		echo '<li class="list-group-item">';
	  echo '<p class="card-text">'.$zapas["nazov"].' '.$zapas["rok"].'<br> Kolo '.$kolo.'<br>'.$zapas["datum"].'<br>'.$zapas["domaci"].':'.$zapas["hostia"].'</p>';
	  echo '</li>';
	}
}

function vypisPosledneZapasy(array $skupiny){
	foreach ($skupiny as $skupina) {
		$zapas = vratposlednyZapas($skupina);
		if($zapas === FALSE){
			return;
		}
		/*kvoli tomu ze 3 kola v pripravke sa hraju v jeden den - kolo 1,2,3 = kolo 1*/
		if($skupina === 'Pripravka'){
			$kolo = floor(($zapas['kolo'] -1) / 3) + 1;
		}
		else{
			$kolo = $zapas['kolo'];
		}
		echo '<li class="list-group-item">';
	  echo '<p class="card-text">'.$zapas["nazov"].' '.$zapas["rok"].'<br> Kolo '.$kolo.'<br>'.$zapas["datum"].'<br>'.$zapas["domaci"].' '.$zapas["skoreD"].':'.$zapas["skoreH"].' '.$zapas["hostia"].'</p>';
	  echo '</li>';
	}
}
/*funkcie v z_skupina.php*/
function vypisVsetkyZapasy($skupina){
	$rok = najdiNajvacsiRok($skupina);
	$kola = vratKolaSoSkupinouARokom($skupina, $rok);
  foreach ($kola as $kolo) {
  	vypisKolo($kolo, $skupina, $rok);
  }
 }

 function vratKolaSoSkupinouARokom($skupina,$rok){
 	$db = napoj_db();
  $sql =<<<EOF
    SELECT distinct(kolo) FROM Zapasy WHERE rok="$rok" AND skupina="$skupina";
EOF;
  $ret = $db->query($sql);
  $kola = array();
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  	$kola[] = $row['kolo'];
  }
  $db->close();	
  return $kola;
 }

function vypisKolo($kolo, $skupina, $rok){
  echo '<div class="row" id="k'.$kolo.'">';
  echo '<div class="col-sm-12">';
  echo '<h6>Kolo '.$kolo.'</h6>';
  echo '</div>';
  echo '</div>';
  $zapasy = vratZapasyKola($skupina, $rok, $kolo);
  foreach ($zapasy as $zapas) {
  	vypisZapas($zapas['domaci'], $zapas['hostia'], $zapas['skoreD'], $zapas['skoreH'], $zapas['datum']);
  }	
}

function vratZapasyKola($skupina, $rok, $kolo){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT * FROM Zapasy WHERE rok="$rok" AND skupina="$skupina" AND kolo="$kolo";
EOF;
  $ret = $db->query($sql);
  $Zapasy = array();
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  	$zapasy[] = $row;
  }
  $db->close();	
  return $zapasy;
}

function vypisZapas($domaci, $hostia, $skoreD, $skoreH, $datum){
  echo '<div class="row">';
  echo '<div class="col-sm-1"></div>';
  echo '<div class="col-sm-2 border-bottom font-weight-bold">'.$datum.'</div>';
  echo '<div class="col-sm-3 text-right border-bottom">'.$domaci.'</div>';
  echo '<div class="col-sm-2 text-center border-bottom">'.$skoreD.':'.$skoreH.'</div>';
  echo '<div class="col-sm-3 border-bottom">'.$hostia.'</div>';
  echo '<div class="col-sm-1"></div>';
  echo '</div>';
}

function vratPosledneKolo($skupina, $rok){
	$db = napoj_db();
  $sql =<<<EOF
    SELECT kolo FROM Zapasy WHERE datetime(datum) < datetime('now') AND skupina = "$skupina" AND rok = "$rok"
		ORDER BY datum desc LIMIT 1;
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $db->close();	
  return $row;	
}

function vratZapasyKonkretnehoRokuASkupiny($skupina, $rok){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT * FROM Zapasy WHERE skupina="$skupina" AND rok="$rok";
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    	$pole[] = $row;
    }
    $db->close();	
    return $pole;
}

?>