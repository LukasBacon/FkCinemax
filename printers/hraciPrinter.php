<?php 

function vypis_hracov($skupina){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT h.*, s.nazov as skupina FROM Hraci as h 
    JOIN Skupiny as s ON h.id_skupiny=s.id
    WHERE s.kod = "$skupina" ORDER BY h.priezvisko;
EOF;
  $ret = $db->query($sql);
  $poc = 0;
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    //nalavo
    if($poc % 2 == 0){
      echo '<div class="row">';
			vypis_hraca($row['id'], $row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby'], $row['skupina']);
      $poc++;
    }
    //napravo
    else{
    	vypis_hraca($row['id'], $row['url'], $row['meno'], $row['priezvisko'], $row['typ_hraca'], $row['rok_narodenia'], $row['kluby'], $row['skupina']);
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
    SELECT h.*, s.nazov as skupina FROM Hraci as h 
    JOIN Skupiny as s ON h.id_skupiny=s.id
    WHERE s.kod = "$skupina" ORDER BY h.priezvisko;
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

function vypis_hraca($id, $url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby, $skupina){
  echo '<div class="col-md-3">';
 	  echo '<div class="hracFotoPanel" style="margin-bottom:1rem;">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0 img-thumbnail hracFoto" src="'.$url.'">';
    echo '</div>';
  echo '</div>';
  echo '<div  class="col-md-3 pb-3" style="position:relative; border-bottom: solid 8px #e9ecef;">';
  	echo '<h3>'.$meno.' '.$priezvisko.'</h3>';
  	echo $typ_hraca.'<br>'.$rok_narodenia.'<br>'.nl2br($kluby);
  echo '</div>';
}

function vypis_hraca_admin($id, $url, $meno, $priezvisko, $typ_hraca, $rok_narodenia, $kluby, $skupina){
  echo '<div class="col-md-3">';
  	echo '<div class="hracFotoPanel">';
  		echo '<img class="img-fluid rounded mb-3 mb-md-0 img-thumbnail hracFoto" src="'.$url.'">';
  	echo '</div>';
  	echo '<div class="form-inline d-flex justify-content-center hraciForm">';
  		echo '<a class="pr-1" id="vymazBtn-'.$id.'" href="javascript:vymazHraca('.$id.');"><img width="40" class="buttonImg withHover" src="fotky/remove.png" alt="Vymaž hráča"></a>';
  	  echo '<a class="pr-1" id="upravBtn-'.$id.'" href="javascript:upravHraca('.$id.');"><img width="40" class="buttonImg withHover" src="fotky/edit.png" alt="Uprav info o hráčovi"></a>';
      echo '<div class="pr-1" id="zmenBtn-'.$id.'">';
        echo '<form action="servlets/editPhotoServlet.php" method="post" enctype="multipart/form-data"  style="margin:0px; padding:0px;">';
          $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          echo '<input type="hidden" name="skupina" value="'.$skupina.'">';
          echo '<input type="hidden" name="meno" value="'.$meno.'">';
          echo '<input type="hidden" name="priezvisko" value="'.$priezvisko.'">';
          echo '<input type="submit" name="submit" id="submitFile-'.$id.'" value="Edit" hidden>';
          echo '<label class="btn-file" style="width:40px; margin:0px; display: flex !important;">';
            echo '<input type="file" class="custom-file-input" style="width:0%;" name="file" id="editFile-'.$id.'" accept=".jpg, .jpeg, .png"/> ';
            echo '<span class="custom-file-control"><img src="fotky/foto.png" alt="Uprav fotku hráča" id="fileLabel" class="buttonImg withHover" width="40"></span>';
          echo '</label>';
        echo '</form>';
      echo '</div>';
  	echo '</div>';
  echo '</div>';
  echo '<div class="col-md-3 pb-3" style="position:relative; border-bottom: solid 8px #e9ecef;">';
  	echo '<h3 id="celeMeno-'.$id.'">'.$meno.' '.$priezvisko.'</h3>';
  	echo '<div id="post-'.$id.'">'.$typ_hraca.'</div>';
    echo '<div id="rocnik-'.$id.'">'.$rok_narodenia.'</div>';
    echo '<div id="kluby-'.$id.'">'.nl2br($kluby).'</div>';
  	echo '<div style="position:absolute; bottom:10px;">';
  echo '</div></div>';
}

function vypis_pridaj_noveho($skupina){
  echo '<input type="hidden" name="skupina" id="skupinaHidden" value="'.$skupina["kod"].'">';
	echo '<div class="row m-0">';
    echo '<div class="col-sm-4"></div>';
		echo '<div class="col-sm-4" id="novaFotkaPanel">';
			echo '<h4>Pridaj nového hráča</h4>';
			echo '<form class="justify-content-center" action="servlets/addPlayerServlet.php" method="post" enctype="multipart/form-data">';
			echo '<label for="meno">Meno: </label><br>';
			echo '<input id="meno" name="meno" type="text" class="form-control" required>';
			echo '<label for="priezvisko">Priezvisko: </label><br>';
			echo '<input id="priezvisko" name="priezvisko" type="text" class="form-control" required>';
			echo '<label for="typ">Post: </label><br>';
			echo '<input id="typ" name="post" type="text" class="form-control">';
			echo '<label for="rok">Ročník: </label><br>';
			echo '<input id="rok" name="rocnik" type="text" class="form-control">';
			echo '<label for="timy">Tímy: </label><br>';
			echo '<textarea id="timy" name="timy" class="form-control"></textarea>';
			echo '<label for="foto">Fotka: </label><br>';
			echo '<input type="file" name="file" id="foto" accept=".jpg, .jpeg, .png, image/jpeg, image/png, image/pjpeg"/>';
			echo '<input type="hidden" name="skupinaId" value="'.$skupina["id"].'">';
			echo '<input type="hidden" name="skupinaKod" value="'.$skupina["kod"].'">';
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      echo '<input type="text" name="url" value="'.$actual_link.'" hidden>';
			echo '<br><br>';
			echo '<div align="center">';
			echo '<input type="submit" name="submit" id="submitFoto" value="+" class="btn btn-success withHover">';
			echo '</div>';
			echo '</form>';
		echo '</div>';
    echo '<div class="col-sm-4"></div>';
  echo '</div><br>';
  echo '<div class="row m-0">';
  echo '<div class="breadcrumb" id="panel"></div>';
  echo '</div>';
}
?>