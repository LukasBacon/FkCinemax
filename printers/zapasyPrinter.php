<?php
/*funkcie v index.php*/
function vratnasledujuciZapas($skupina)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, s.kod, l.nazov, z.poznamka 
    FROM Zapasy as z 
    JOIN Skupiny as s ON s.id = z.id_skupiny
    JOIN Ligy as l ON z.rok = l.rok AND s.id = l.id_skupiny
		WHERE datetime(z.datum) > datetime('now') AND s.kod = "$skupina" AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum asc LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
}

function vratPoslednyZapas($skupina)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, s.kod, l.nazov, z.poznamka 
    FROM Zapasy as z 
    JOIN Skupiny as s ON s.id = z.id_skupiny
    JOIN Ligy as l ON z.rok = l.rok AND s.id = l.id_skupiny
		WHERE datetime(z.datum) < datetime('now') AND s.kod = "$skupina" AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum desc LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
}

function vypisNasledujuceZapasy($skupina)
{
    if ($skupina["zobrazenie_nasl_a_predch_zapasov"]) {
        $zapas = vratnasledujuciZapas($skupina['kod']);
        if ($zapas === FALSE) {
            return;
        }
        $kolo = $zapas['kolo'];
        echo '<li class="list-group-item">';
        echo '<p class="card-text"><strong>' . $skupina["nazov"] . '</strong><br> Kolo ' . $kolo . ' - ' . vypisDatumACas($zapas["datum"]) . '<br>' . $zapas["domaci"] . ' : ' . $zapas["hostia"] . '<br><small>' . $zapas['poznamka'] . '</small></p>';
        echo '</li>';
    } else {
        echo '<li class="list-group-item">';
        echo '<a class="btn btn-primary" href="zapasy.php?skupina=' . $skupina["kod"] . '">Zápasy '. $skupina["nazov_genitiv"] . '</a>';
        echo '</li>';
    }
}

function vypisPosledneZapasy($skupina)
{
    if ($skupina["zobrazenie_nasl_a_predch_zapasov"]) {
        $zapas = vratposlednyZapas($skupina['kod']);
        if ($zapas === FALSE) {
            return;
        }
        $kolo = $zapas['kolo'];
        echo '<li class="list-group-item">';
        echo '<p class="card-text"><strong>' . $skupina["nazov"] . '</strong><br> Kolo ' . $kolo . ' - ' . vypisDatumACas($zapas["datum"]) . '<br>' . $zapas["domaci"] . ' <strong>' . $zapas["skoreD"] . ':' . $zapas["skoreH"] . ' </strong>' . $zapas["hostia"] . '<br><small>' . $zapas['poznamka'] . '</small></p>';
        echo '</li>';
    } else {
        echo '<li class="list-group-item">';
        echo '<a class="btn btn-primary" href="zapasy.php?skupina=' . $skupina["kod"] . '">Zápasy ' . $skupina["nazov_genitiv"] . '</a>';
        echo '</li>';
    }
}

function vypisVsetkyZapasy($idSkupiny)
{
    $rok = najdiNajvacsiRok($idSkupiny);
    $kola = vratKolaSoSkupinouARokom($idSkupiny, $rok);
    foreach ($kola as $kolo) {
        vypisKolo($kolo, $idSkupiny, $rok);
    }
}

function vratKolaSoSkupinouARokom($skupina, $rok)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT distinct(z.kolo) FROM Zapasy as z JOIN Skupiny as s ON z.id_skupiny = s.id
     WHERE z.rok="$rok" AND s.kod="$skupina";
EOF;
    $ret = $db->query($sql);
    $kola = array();
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $kola[] = $row['kolo'];
    }
    $db->close();
    return $kola;
}

function vypisKolo($kolo, $skupina, $rok)
{
    echo '<div class="row ml-1 mr-1" id="k' . $kolo . '">';
    echo '<div class="col-sm-12 bg-dark text-center text-white">';
    echo '<h6 class="mb-1 mt-1"><strong>Kolo ' . $kolo . '</strong></h6>';
    echo '</div>';
    echo '</div>';
    $zapasy = vratZapasyKola($skupina, $rok, $kolo);
    foreach ($zapasy as $zapas) {
        vypisZapas($zapas['domaci'], $zapas['hostia'], $zapas['skoreD'], $zapas['skoreH'], vypisDatumACas($zapas['datum']), $zapas['poznamka'], $zapas['id'], $zapas['kolo']);
    }
    echo '<br>';
}

function vratZapasyKola($skupina, $rok, $kolo)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT * FROM Zapasy as z JOIN Skupiny as s ON z.id_skupiny=s.id
    WHERE rok="$rok" AND (id_skupiny="$skupina" OR s.kod="$skupina")
    AND kolo="$kolo" order by domaci='FK CINEMAX Doľany' or hostia='FK CINEMAX Doľany' desc;
EOF;
    $ret = $db->query($sql);
    $Zapasy = array();
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $zapasy[] = $row;
    }
    $db->close();
    return $zapasy;
}

function vypisZapas($domaci, $hostia, $skoreD, $skoreH, $datum, $poznamka, $id, $kolo)
{
    if (strpos($domaci, "FK CINEMAX Doľany") !== false || strpos($hostia, "FK CINEMAX Doľany") !== false) {
        echo '<div class="row ml-1 mr-1 border-bottom bg-warning-pale">';
    } else {
        echo '<div class="row mr-1 border-bottom ml-1">';
    }
    echo '<div class="col-sm-2 font-weight-bold text-center">' . $datum . '</div>';
    echo '<div class="col-sm-3 text-center">' . $domaci . '</div>';
    echo '<div class="col-sm-2 text-center">' . $skoreD . ':' . $skoreH . '</div>';
    echo '<div class="col-sm-3 text-center">' . $hostia . '</div>';
    if (strpos($domaci, "FK CINEMAX Doľany") !== false || strpos($hostia, "FK CINEMAX Doľany") !== false) {
        if ($poznamka == null) {
            echo '<div class="col-sm-2 text-center ">
        <div class="d-inline myTooltip">
        <img id="infoImg' . $kolo . '" class="m-2" src="fotky/i-not.png" width="20">
         <span hidden id="infoText' . $kolo . '" class="myTooltipText"></span>
        </div>';
        } else {
            echo '<div class="col-sm-2 text-center ">
        <div class="d-inline myTooltip">
        <img id="infoImg' . $kolo . '" class="m-2" src="fotky/i.png" width="20">
        <span id="infoText' . $kolo . '" class="myTooltipText">' . $poznamka . '</span>
        </div>';
        }
        if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
            $param = '\'' . addslashes($poznamka) . '\',' . $id;
            //echo '<button class="btn btn-warning p-1" style="font-size:10px; vertical-align:middle;" onclick="infoBox('.$param.')">Pridaj/uprav <br>poznámku</button>';
            echo '<a class="buttonImg" href="javascript:infoBox(' . $param . ')"><img class="withHover" src="fotky/edit.png" width="30"></a>';
        }
        echo '</div>';

    } else {
        echo '<div class="col-sm-2"></div>';
    }
    echo '</div>';
}

function vratPosledneKolo($idSkupiny, $rok)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT kolo FROM Zapasy WHERE datetime(datum) < datetime('now') AND id_skupiny = "$idSkupiny" AND rok = "$rok"
		ORDER BY datum desc LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
}

function vratZapasyKonkretnehoRokuASkupiny($skupina, $rok)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT * FROM Zapasy WHERE skupina="$skupina" AND rok="$rok";
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        $pole[] = $row;
    }
    $db->close();
    return $pole;
}

function vratKolo($idZapasu)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT kolo FROM Zapasy WHERE id="$idZapasu";
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
}

?>