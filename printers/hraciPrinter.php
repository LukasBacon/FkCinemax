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
			vypis_hraca($row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby']);
      $poc++;
    }
    //napravo
    else{
    	vypis_hraca($row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby']);
      echo '</div>';
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
      echo '<div class="row rowHraci">';
      vypis_hraca_admin($row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby']);
      $poc++;
    }
    //napravo
    else{
    	vypis_hraca_admin($row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby']);
      echo '</div>';
      $poc++;
    }
  }
  //posledny je nalavo
  if(($poc % 2) > 0){
    echo '<div class="col-md-6"></div></div>';
  }
  $db->close();
}

function vypis_hraca($url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby){
  echo '<div class="col-md-3">';
 	 echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$url.'">';
  echo '</div>';
  echo '<div class="col-md-3 border-bottom mb-3 pb-3">';
  	echo '<h3>'.$meno.' '.$priezvisko.'</h3>';
  	echo $typ_hraca.'<br>'.$rok_narodenia.'<br>'.$kluby;
  echo '</div>';
}


function vypis_hraca_admin($url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby){
  echo '<div class="col-md-3">';
  	echo '<div class="hracFoto">';
  		echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$url.'">';
  	echo '</div>';
  	echo '<form class="form-inline d-flex justify-content-center hraciForm" method="post">';
  		echo '<a class="pr-1" id="" href="javascript:zmenFotkuHraca();"><img width="40" class="buttonImg withHover" src="fotky/foto.png" alt="Zmeň fotku hráča"></a>';
  		echo '<a class="pr-1" id="" href="javascript:vymazHraca();"><img width="40" class="buttonImg withHover" src="fotky/remove.png" alt="Vymaž hráča"></a>';
  		echo '<a class="" id="" href="javascript:upravHraca();"><img width="40" class="buttonImg withHover" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
  	echo '</form>';
  echo '</div>';
  echo '<div class="col-md-3 border-bottom mb-3 pb-3" style="position:relative;">';
  	echo '<h3>'.$meno.' '.$priezvisko.'</h3>';
  	echo $typ_hraca.'<br>'.$rok_narodenia.'<br>'.$kluby.'<br>';
  	echo '<div style="position:absolute; bottom:10px;">';
  echo '</div></div>';
}

function vypis_pridaj_noveho($skupina){
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj nového hráča</h4>';
			echo '<form class="justify-content-center" action="/FkCinemax/servlets/addPlayesServlet.php" method="post" enctype="multipart/form-data">';
			echo '<label for="meno">Meno: </label><br>';
			echo '<input id="meno" name="meno" type="text"><br>';
			echo '<label for="priezvisko">Priezvisko: </label><br>';
			echo '<input id="priezvisko" name="priezvisko" type="text"><br>';
			echo '<label for="typ">Post: </label><br>';
			echo '<input id="typ" name="post" type="text"><br>';
			echo '<label for="rocnik">Ročník: </label><br>';
			echo '<input id="rocnik" name="rocnik" type="text"><br>';
			echo '<label for="timy">Tímy: </label><br>';
			echo '<textarea id="timy" name="timy"></textarea><br>';
			echo '<label for="foto">Fotka: </label><br>';
			echo '<input type="file" name="files[]" id="foto" accept=".jpg, .jpeg, .png"/> ';
			echo '<inpit type="hidden" name="skupina" value="'.$skupina.'"';
			echo '<br><br>';
			echo '<div align="center">';
			echo '<input type="submit" id="submitFoto withHover" name="submit" value="P" class="btn btn-success">';
			echo '</div>';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>