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
      vypis_hraca_admin($row['id'], $row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby'], $skupina);
      $poc++;
    }
    //napravo
    else{
    	vypis_hraca_admin($row['id'], $row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby'], $skupina);
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

function vypis_hraca($id, $url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby){
  echo '<div class="col-md-3">';
 	 echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$url.'">';
  echo '</div>';
  echo '<div class="col-md-3 border-bottom mb-3 pb-3">';
  	echo '<h3>'.$meno.' '.$priezvisko.'</h3>';
  	echo $typ_hraca.'<br>'.$rok_narodenia.'<br>'.nl2br($kluby);
  echo '</div>';
}

function vypis_hraca_admin($id, $url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby, $skupina){
  echo '<div class="col-md-3">';
  	echo '<div class="hracFoto">';
  		echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$url.'">';
  	echo '</div>';
  	echo '<div class="form-inline d-flex justify-content-center hraciForm">';
  		echo '<a class="pr-1" id="vymazBtn-'.$id.'" href="javascript:vymazHraca('.$id.');"><img width="40" class="buttonImg" src="fotky/remove.png" alt="Vymaž hráča"></a>';
  	  echo '<a class="pr-1" id="upravBtn-'.$id.'" href="javascript:upravHraca('.$id.');"><img width="40" class="buttonImg" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
      echo '<div class="pr-1" id="zmenBtn-'.$id.'">';
        echo '<form action="/FkCinemax/servlets/editPhotoServlet.php" method="post" enctype="multipart/form-data"  style="margin:0px; padding:0px;">';
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          echo '<input type="hidden" name="skupina" value="'.$skupina.'">';
          echo '<input type="hidden" name="meno" value="'.$meno.'">';
          echo '<input type="hidden" name="priezvisko" value="'.$priezvisko.'">';
          echo '<input type="submit" name="submit" id="submitFile-'.$id.'" value="Edit" hidden>';
          echo '<label class="btn-file" style="width:40px; margin:0px;">';
            echo '<input type="file" class="custom-file-input" style="width:0%;" name="file" id="editFile-'.$id.'" accept=".jpg, .jpeg, .png"/> ';
            echo '<span class="custom-file-control"><img src="fotky/foto.png" id="fileLabel" class="buttonImg" width="40"></span>';
          echo '</label>';
        echo '</form>';
      echo '</div>';
  	echo '</div>';
  echo '</div>';
  echo '<div class="col-md-3 border-bottom mb-3 pb-3" style="position:relative;">';
  	echo '<h3 id="celeMeno-'.$id.'">'.$meno.' '.$priezvisko.'</h3>';
  	echo '<div id="post-'.$id.'">'.$typ_hraca.'</div>';
    echo '<div id="rocnik-'.$id.'">'.$rok_narodenia.'</div>';
    echo '<div id="kluby-'.$id.'">'.nl2br($kluby).'</div>';
  	echo '<div style="position:absolute; bottom:10px;">';
  echo '</div></div>';
}

function vypis_pridaj_noveho($skupina){
  echo '<input type="hidden" name="skupina" id="skupinaHidden" value="'.$skupina.'">';
	echo '<div class="row m-0">';
		echo '<div class="col-sm-12" id="novaFotkaPanel">';
			echo '<h4>Pridaj nového hráča</h4>';
			echo '<form class="justify-content-center" action="/FkCinemax/servlets/addPlayerServlet.php" method="post" enctype="multipart/form-data">';
			echo '<label for="meno">Meno: </label><br>';
			echo '<input id="meno" name="meno" type="text"><br>';
			echo '<label for="priezvisko">Priezvisko: </label><br>';
			echo '<input id="priezvisko" name="priezvisko" type="text"><br>';
			echo '<label for="typ">Post: </label><br>';
			echo '<input id="typ" name="post" type="text"><br>';
			echo '<label for="rok">Ročník: </label><br>';
			echo '<input id="rok" name="rocnik" type="text"><br>';
			echo '<label for="timy">Tímy: </label><br>';
			echo '<textarea id="timy" name="timy"></textarea><br>';
			echo '<label for="foto">Fotka: </label><br>';
			echo '<input type="file" name="file" id="foto" accept=".jpg, .jpeg, .png"/> ';
			echo '<input type="hidden" name="skupina" value="'.$skupina.'">';
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
			echo '<br><br>';
			echo '<div align="center">';
			echo '<input type="submit" name="submit" id="submitFoto" value="P" class="btn btn-success">';
			echo '</div>';
			echo '</form>';
		echo '</div>';
	echo '</div><br>';
}
?>