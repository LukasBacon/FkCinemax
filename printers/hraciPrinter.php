<?php 

function vypis_hracov($skupina){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT * FROM Hraci WHERE skupina = "$skupina" ORDER BY priezvisko;
EOF;
  $ret = $db->query($sql);
  $poc = 0;
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    //nalavo
    if($poc % 2 == 0){
      echo '<div class="row">';
      echo '<div class="col-md-3">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$row['url'].'">';
      echo '</div>';
      echo '<div class="col-md-3 border-bottom mb-3 pb-3">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'];
      echo '</div>';
      $poc++;
    }
    //napravo
    else{
      echo '<div class="col-md-3">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$row['url'].'">';
      echo '</div>';
      echo '<div class="col-md-3 border-bottom mb-3 pb-3">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'];
      echo '</div></div>';
      $poc++;
    }
  }
  //ak je posledny nalavo
  if(($poc % 2) > 0){
    echo '<div class="col-md-6"></div></div>';
  }
  $db->close();
}

function vypis_hracov_admin($skupina){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT * FROM Hraci WHERE skupina = "$skupina" ORDER BY priezvisko;
EOF;
  $ret = $db->query($sql);
  $poc = 0;
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    //nalavo
    if($poc % 2 == 0){
      echo '<div class="row">';
      echo '<div class="col-md-3">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$row['url'].'">';
      echo '<form class="form-inline d-flex justify-content-center" method="post">';
      echo '<a class="pr-1" id="" href="javascript:zmenFotkuHraca();"><img width="40" class="buttonImg" src="fotky/foto.png" alt="Zmeň fotku hráča"></a>';
      echo '<a class="pr-1" id="" href="javascript:vymazHraca();"><img width="40" class="buttonImg" src="fotky/remove.png" alt="Vymaž hráča"></a>';
      echo '<a class="" id="" href="javascript:upravHraca();"><img width="40" class="buttonImg" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
      echo '</form>';
      echo '</div>';
      echo '<div class="col-md-3 border-bottom mb-3 pb-3" style="position:relative;">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'].'<br>';
      echo '<div style="position:absolute; bottom:10px;">';

      echo '</div>';
      echo '</div>';

      $poc++;
    }
    //napravo
    else{
      echo '<div class="col-md-3">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$row['url'].'">';
      echo '<form class="form-inline d-flex justify-content-center" method="post">';
      echo '<a class="pr-1" id="" href="javascript:zmenFotkuHraca();"><img width="40" class="buttonImg" src="fotky/foto.png" alt="Zmeň fotku hráča"></a>'; 
      echo '<a class="pr-1" id="" href="javascript:vymazHraca();"><img width="40" class="buttonImg" src="fotky/remove.png" alt="Vymaž hráča"></a>';
      echo '<a class="" id="" href="javascript:upravHraca();"><img width="40" class="buttonImg" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
      echo '</form>';
      echo '</div>';
      echo '<div class="col-md-3 border-bottom mb-3 pb-3" style="position:relative;">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'].'<br>';
      echo '<div style="position:absolute; bottom:10px;">';
      echo '</div>';     
      echo '</div></div>';
      $poc++;
    }
  }
  //posledny je nalavo
  if(($poc % 2) > 0){
    echo '<div class="col-md-6"></div></div>';
  }
  $db->close();
}

function vypis_pridaj_noveho($skupina){
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj nového hráča</h4>';
			echo '<form class="justify-content-center" action="/FkCinemax/servlets/addPhotosServlet.php" method="post" enctype="multipart/form-data">';
			echo '<label for="meno">Meno a priezvisko: </label><br>';
			echo '<input id="meno" type="text"><br>';
			echo '<label for="typ">Typ: </label><br>';
			echo '<input id="typ" type="text"><br>';
			echo '<label for="rok">Ročník: </label><br>';
			echo '<input id="rok" type="text"><br>';
			echo '<label for="timy">Tímy: </label><br>';
			echo '<textarea id="timy"></textarea><br>';
			echo '<label for="foro">Fotka: </label><br>';
			echo '<input type="submit" id="submitFoto" value="P" class="btn btn-success">';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>