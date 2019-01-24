<?php
$heslo="Matus18"; //heslo admina
date_default_timezone_set('UTC');
include('db.php');
include('dbLoader.php');

function hlavicka(){
  $dbLoader = new dbLoader;
  $dbLoader->over();
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Oficiálna stránka FK CINEMAX Doľany | Aktuality | Zápasy | Tabuľky | Hráči | Fotogaléria | Fórum | Kontakt">
    <meta name="author" content="">

    <title>FK CINEMAX Doľany</title>

    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="css/modern-business.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
    <!-- Navigacia -->
    <div class="content">
    <nav class="navbar fixed-top navbar-expand-xl navbar-dark bg-dark fixed-top">
      <div class="container">
        <img src="fotky/logo.png" class="img-logo">
        <a class="navbar-brand" href="index.php"><h1 style="font-size: 1.25rem; font-weight: bold; margin-top: 0.5rem;">FK CINEMAX DOĽANY</h1></a>
        <!--<a class="navbar-brand" href="index.php">FK CINEMAX DOĽANY</a>-->
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
              <a class="nav-link" href="fotogaleria.php">Fotogaléria</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="forum.php">Fórum</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="partneri.php">Partneri</a>
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
  <footer class="bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">
       <a href="admin.php" id="footerA">Copyright &copy; Gabriela Slaninková & Lukáš Slaninka 2019</a>
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

function vytvorDiskusiu($meno, $nazov, $popis){
  $db = napoj_db();
  $sql =<<<EOF
    SELECT count() as count FROM Diskusie WHERE nazov="$nazov" AND autor="$meno";
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $pocet = $row["count"];
  if ($pocet != 0){
    $db->close();
    return;
  }

  $sql =<<<EOF
    INSERT INTO Diskusie (nazov, datum_disk, popis, autor) VALUES ("$nazov", date('now'), "$popis", "$meno");
EOF;
  $db->query($sql);
  $db->close();
}

function vytvorAktualitu($nadpis, $text){
  $datum = date('Y-m-d');
  $cas = date('H:i:s');
  $db = napoj_db();

  $sql =<<<EOF
    SELECT count() as count FROM Aktuality WHERE nadpis="$nadpis";
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $pocet = $row["count"];
  if ($pocet != 0){
    $db->close();
    return;
  }
  $sql =<<<EOF
  INSERT INTO Aktuality (nadpis, text, datum, cas) VALUES ("$nadpis", "$text", "$datum", "$cas");
EOF;
  $db->query($sql);
  $db->close();
}

function replaceSpecialChars($string){
  $map1 = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' ' => '_');
  $map2 = array('á'=>'a', 'ä'=>'a', 'č'=>'c', 'é'=>'e', 'í'=>'i','ľ'=>'l', 'ň'=>'n', 'ó'=>'o', 'ô'=>'o', 'ŕ'=>'r', 'š'=>'s', 'ś'=>'s', 'ť'=>'t', 'ú'=>'u', 'ý'=>'             y', 'ž'=>'z',
                'Á'=>'A', 'Ä'=>'A', 'Č'=>'C', 'É'=>'E', 'Í'=>'I','Ľ'=>'L', 'Ň'=>'N', 'Ó'=>'O', 'Ô'=>'O', 'Ŕ'=>'R', 'Š'=>'S', 'Ś'=>'S', 'Ť'=>'T', 'Ú'=>'U', 'Ý'=>'Y', 'Ž'=>'Z', '/'=>'_', '\\' => '_', ':' => '_', '*' => '_', '?' => '_', '<' => '_', '>' => '_', '"' => '_', '|' => '_');
  $map = $map1 + $map2;
  return strtr($string, $map);
}

function replaceApostrophes($string){
  $map = array('\''=> '\'\'', '"'=>'""');
  return strtr($string, $map);
}
?>
