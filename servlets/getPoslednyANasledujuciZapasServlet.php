<?php
include('../db.php');
include('../printers/zapasyPrinter.php');

$skupiny = $_POST['skupiny'];
$result = array();

$db = napoj_db();

foreach ($skupiny as $skupina) {
    $sql = <<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, z.skupina, l.nazov, z.poznamka 
    FROM Zapasy as z JOIN Ligy as l ON z.rok = l.rok AND z.skupina = l. skupina
		WHERE datetime(z.datum) < datetime('now') AND z.skupina = 'Seniori' AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum desc LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $posledny = $ret->fetchArray(SQLITE3_ASSOC);

    $sql = <<<EOF
    SELECT z.datum, z.rok, z.domaci, z.hostia, z.skoreD, z.skoreH, z.kolo, z.skupina, l.nazov, z.poznamka 
    FROM Zapasy as z JOIN Ligy as l ON z.rok = l.rok AND z.skupina = l. skupina
		WHERE datetime(z.datum) > datetime('now') AND z.skupina = 'Seniori' AND 
			(hostia LIKE '%FK CINEMAX Doľany%' OR domaci LIKE '%FK CINEMAX Doľany%')
		ORDER BY z.datum asc LIMIT 1
EOF;
    $ret = $db->query($sql);
    $nasledujuci = $ret->fetchArray(SQLITE3_ASSOC);

    $skupinaResult = array("posledny" => $posledny, "nasledujuci" => $nasledujuci);
    $result[$skupina] = $skupinaResult;
}

$db->close();

echo json_encode($result);

?>