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
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, z.skupina, l.nazov, z.poznamka 
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
		/*if($skupina === 'Pripravka'){
			$kolo = floor(($zapas['kolo'] -1) / 3) + 1;
		}
		else{
			$kolo = $zapas['kolo'];
		}*/
    $kolo = $zapas['kolo'];
		echo '<li class="list-group-item">';
	  echo '<p class="card-text"><strong>'.$zapas["nazov"].' '.$zapas["rok"].'</strong><br> Kolo '.$kolo.' - '.vypisDatumACas($zapas["datum"]).'<br>'.$zapas["domaci"].' : '.$zapas["hostia"].'</p>';
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
		/*if($skupina === 'Pripravka'){
			$kolo = floor(($zapas['kolo'] -1) / 3) + 1;
		}
		else{
			$kolo = $zapas['kolo'];
		}*/
    $kolo = $zapas['kolo'];
		echo '<li class="list-group-item">';
	  echo '<p class="card-text"><strong>'.$zapas["nazov"].' '.$zapas["rok"].'</strong><br> Kolo '.$kolo.' - '.vypisDatumACas($zapas["datum"]).'<br>'.$zapas["domaci"].' <strong>'.$zapas["skoreD"].':'.$zapas["skoreH"].' </strong>'.$zapas["hostia"].'<br><small>'.$zapas['poznamka'].'</small></p>';
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
  echo '<div class="row ml-1 mr-1" id="k'.$kolo.'">';
  echo '<div class="col-sm-12 bg-dark text-center text-white">';
  echo '<h6 class="mb-1 mt-1"><strong>Kolo '.$kolo.'</strong></h6>';
  echo '</div>';
  echo '</div>';
  $zapasy = vratZapasyKola($skupina, $rok, $kolo);
  foreach ($zapasy as $zapas) {
  	vypisZapas($zapas['domaci'], $zapas['hostia'], $zapas['skoreD'], $zapas['skoreH'], vypisDatumACas($zapas['datum']), $zapas['poznamka'], $zapas['id']);
  }	
  echo '<br>';
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

function vypisZapas($domaci, $hostia, $skoreD, $skoreH, $datum, $poznamka, $id){
  if(strpos($domaci, "FK CINEMAX Doľany") !== false || strpos($hostia, "FK CINEMAX Doľany") !== false){
  echo '<div class="row ml-1 mr-1 bg-warning-pale">';
  }
  else{
  echo '<div class="row border-bottom mr-1 ml-1">';
  }
  echo '<div class="col-sm-2 font-weight-bold text-center">'.$datum.'</div>';
  echo '<div class="col-sm-3 text-center">'.$domaci.'</div>';
  echo '<div class="col-sm-2 text-center">'.$skoreD.':'.$skoreH.'</div>';
  echo '<div class="col-sm-3 text-center">'.$hostia.'</div>';
  if(strpos($domaci, "FK CINEMAX Doľany") !== false || strpos($hostia, "FK CINEMAX Doľany") !== false){
    if($poznamka == null){
        echo '<div class="col-sm-2 text-center"><img class="m-2" src="fotky/i-not.png" width="20">';
    }
    else{
        echo '<div class="col-sm-2 text-center "><div class="d-inline myTooltip"><img class="m-2" src="fotky/i.png" width="20"><span class="myTooltipText">'.$poznamka.'</span></div>';
    }
    if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
      $param = '\''.$poznamka.'\','.$id;
      echo '<button class="btn btn-warning p-1" style="font-size:10px; vertical-align:middle;" onclick="infoBox('.$param.')">Pridaj/uprav <br>poznámku</button>';
    }
    echo '</div>';

  }
  else{
    echo '<div class="col-sm-2"></div>';
  }
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

function vratKolo($idZapasu){
    $db = napoj_db();
  $sql =<<<EOF
    SELECT kolo FROM Zapasy WHERE id="$idZapasu";
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $db->close(); 
  return $row;  
}

?>