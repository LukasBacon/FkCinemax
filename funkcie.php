<?php
$heslo="zelenajetrava"; //heslo admina
date_default_timezone_set('UTC');
include('db.php');

function hlavicka(){  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FK CINEMAX</title>

    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
  </head>

  <body>
    <!-- Navigacia -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <img src="fotky/logo.png" class="img-logo">
        <a class="navbar-brand" href="index.php">FK CINEMAX DOĽANY </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="onas.php">O nás</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Zápasy </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                <a class="dropdown-item" href="z_seniori.php">Seniori</a>
                <a class="dropdown-item" href="z_pripravka.php">Prípravka</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tabuľky</a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                <a class="dropdown-item" href="t_seniori.php">Seniori</a>
                <a class="dropdown-item" href="t_pripravka.php">Prípravka</a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hráči</a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownPortfolio">
                <a class="dropdown-item" href="seniori.php">Seniori</a>
                <a class="dropdown-item" href="pripravka.php">Prípravka</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="foto.php">Fotogaléria</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="forum.php">Fórum</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="kontakt.php">Kontakt</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<?php
}

function paticka(){ ?>
  <p style="margin-bottom: 100px;"></p>
  <footer class="py-3 bg-dark" style="z-index: 1">
    <div class="container">
      <p class="m-0 text-center text-white">
       <a href="admin.php">Copyright &copy; Gabriela Slaninková 2018</a>
      </p>
    </div>
  </footer> 
<?php
}

function vypisDatum($date){
  $rok = substr($date, 0, 4);
  $mesiac = substr($date, 5, 2);
  $den = substr($date, 8, 2);
  return $den.'.'.$mesiac.'.'.$rok;
}

function vypisDatumACas($datetime){
  $datum = vypisDatum($datetime);
  $cas = substr($datetime, 11);
  return $datum . ' ' . $cas;
}

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
      echo '<div class="col-md-3">';
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
      echo '<div class="col-md-3">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'];
      echo '</div></div><hr>';
      $poc++;
    }
  }
  //ak je posledny nalavo
  if(($poc % 2) > 0){
    echo '<div class="col-md-6"></div></div><hr>';
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
      echo '</div>';
      echo '<div class="col-md-3">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'].'<br>';
      echo '<form method="post">';
      echo '<input type="submit" value="Vymaž hráča" name="vymaz'.$row['id'].'" class="btn btn-admin"><br>';
      echo '<input type="submit" value="Uprav hráča" name="uprav'.$row['id'].'" class="btn btn-admin"><br>';
      echo '</form>';
      echo '<input type="submit" value="Nahraj fotku" name="foto'.$row['id'].'" class="btn btn-admin"><br>';
      echo '</div>';
      $poc++;
    }
    //napravo
    else{
      echo '<div class="col-md-3">';
      echo '<img class="img-fluid rounded mb-3 mb-md-0" src="'.$row['url'].'">';
      echo '</div>';
      echo '<div class="col-md-3">';
      echo '<h3>'.$row['meno'].' '.$row['priezvisko'].'</h3>';
      echo $row['typ_hraca'].'<br>'.$row['rok_narodenia'].'<br>'.$row['kluby'].'<br>';
      echo '<form method="post">';
      echo '<input type="submit" value="Vymaž hráča" name="vymaz'.$row['id'].'" class="btn btn-admin"><br>';
      echo '<input type="submit" value="Uprav hráča" name="uprav'.$row['id'].'" class="btn btn-admin"><br>';
      echo '</form>';
      echo '<input type="submit" value="Nahraj fotku" name="foto'.$row['id'].'" class="btn btn-admin"><br>';
      echo '</div></div><hr>';
      $poc++;
    }
  }
  //posledny je nalavo
  if(($poc % 2) > 0){
    echo '<div class="col-md-6"></div></div><hr>';
  }
  $db->close();
}

function vypis_diskusie(){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT * FROM Diskusie ORDER BY datum_disk DESC;
EOF;
  $ret = $db->query($sql);
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){?>
    <div class="card my-4">
      <div class="card-header">
        <strong style="font-size: 1.25rem;"><?php echo $row['nazov']; ?></strong>
        <strong style="float:right;">&#9660;</strong> <!-- opacna je 9650-->
      </div>
        <div class="card-body">
          <h5 class="mt-0"><?php echo $row['autor']; ?></h5>
                <?php echo $row['popis']; ?>
                <!--
                  KOMENTARE
                <div class="card my-4">
                  <div class="card-body">
                    <h5 class="mt-0">Anonym</h5>
                      Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                  </div>
                  <div class="card-footer">27.02.2018</div>
                </div>

                <div class="card my-4">
                  <div class="card-body">
                    <h5 class="mt-0">Stefan</h5>
                    Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                  </div>
                  <div class="card-footer">27.02.2018</div>
                </div>
  
                <div class="card my-4">
                  <h5 class="card-header-match">Pridaj komentár</h5>
                  <div class="card-body">
                    <form novalidate>
                      <div class="control-group form-group">
                        <div class="controls">
                          <label>Meno:</label>
                          <input type="text" class="form-control" id="name" required data-validation-required-message="Please enter your name.">
                          <p class="help-block"></p>
                        </div>
                      </div>
                      <div class="control-group form-group">
                        <div class="controls">
                          <label>Komentár:</label>
                          <textarea rows="4" cols="100" class="form-control" id="message" required data-validation-required-message="Please enter your message" maxlength="999" style="resize:none"></textarea>
                        </div>
                      </div>
                      <div id="success"></div>
                      <button type="submit" class="btn btn-primary" id="sendMessageButton">Pridaj</button>
                    </form>
                  </div>
                </div>
                --> 


              </div>
              <div class="card-footer"><?php echo vypisDatum($row['datum_disk']); ?></div>
            </div>
      <?php
  }

  $db->close();  
}
?>