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
    if ($skupina["zobrazenie_nasl_a_predch_zapasov"] === "text") {
        $zapas = vratnasledujuciZapas($skupina['kod']);
        if ($zapas === FALSE) {
            return;
        }
        $kolo = $zapas['kolo'];
        echo '<li class="list-group-item">';
        if ($zapas["domaci"] === "" || $zapas["hostia"] === "") {
            echo '<p class="card-text"><strong>' . $skupina["nazov"] . '</strong><br> Kolo ' . $kolo . ' - ' . vypisDatumACas($zapas["datum"]) . '<br>VOĽNO<br></p>';

        } else {
            echo '<p class="card-text"><strong>' . $skupina["nazov"] . '</strong><br> Kolo ' . $kolo . ' - ' . vypisDatumACas($zapas["datum"]) . '<br>' . $zapas["domaci"] . ' : ' . $zapas["hostia"] . '<br><small>' . $zapas['poznamka'] === null ? "" : $zapas['poznamka'] . '</small></p>';
        }
        echo '</li>';
    } else if ($skupina["zobrazenie_nasl_a_predch_zapasov"] === "button"){
        echo '<li class="list-group-item">';
        echo '<a class="btn btn-primary" href="zapasy.php?skupina=' . $skupina["kod"] . '">Zápasy '. $skupina["nazov_genitiv"] . '</a>';
        echo '</li>';
    }
}

function vypisPosledneZapasy($skupina)
{
    if ($skupina["zobrazenie_nasl_a_predch_zapasov"] === "text") {
        $zapas = vratposlednyZapas($skupina['kod']);
        if ($zapas === FALSE) {
            return;
        }
        $kolo = $zapas['kolo'];
        echo '<li class="list-group-item">';
        echo '<p class="card-text"><strong>' . $skupina["nazov"] . '</strong><br> Kolo ' . $kolo . ' - ' . vypisDatumACas($zapas["datum"]) . '<br>' . $zapas["domaci"] . ' <strong>' . $zapas["skoreD"] . ':' . $zapas["skoreH"] . ' </strong>' . $zapas["hostia"] . '<br><small>' . $zapas['poznamka'] . '</small></p>';
        echo '</li>';
    } else if ($skupina["zobrazenie_nasl_a_predch_zapasov"] === "button"){
        echo '<li class="list-group-item">';
        echo '<a class="btn btn-primary" href="zapasy.php?skupina=' . $skupina["kod"] . '">Zápasy ' . $skupina["nazov_genitiv"] . '</a>';
        echo '</li>';
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

function vratZapasyKola($skupina, $rok, $kolo)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT z.* FROM Zapasy as z JOIN Skupiny as s ON z.id_skupiny=s.id
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

function vratPosledneKolo($skupina, $rok)
{
    $db = napoj_db();
    $sql = <<<EOF
    SELECT kolo FROM Zapasy as z JOIN Skupiny as s ON s.id = z.id_skupiny
    WHERE datetime(datum) < datetime('now') AND s.kod = "$skupina" AND rok = "$rok"
		ORDER BY datum desc LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row;
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