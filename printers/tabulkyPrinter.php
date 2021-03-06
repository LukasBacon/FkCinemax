<?php

function vypisTabulku($skupina){
	$rok = najdiNajvacsiRok($skupina);
	$db = napoj_db();
  $sql =<<<EOF
    SELECT * FROM Tabulky as t JOIN Skupiny as s ON t.id_skupiny=s.id
    WHERE s.kod="$skupina" AND rok="$rok";
EOF;
  $ret = $db->query($sql);
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  	if(strpos($row['klub'], "FK CINEMAX Doľany") !== false){
  		echo "<tr class='table-warning'>";
  	}
  	else{
  		echo "<tr>";
  	}
  	echo "<td>".$row['poradie']."</td>";
  	echo "<td>".$row['klub']."</td>";
  	echo "<td>".$row['p_zapasov']."</td>";
  	echo "<td>".$row['p_vyhier']."</td>";
  	echo "<td>".$row['p_remiz']."</td>";
  	echo "<td>".$row['p_prehier']."</td>";
  	echo "<td>".$row['skore']."</td>";
  	echo "<td>".$row['body']."</td>";
  	echo "<td>".$row['fp']."</td>";
  	echo "</tr>";
  }
  $db->close();	
}

function nacitajKonkretnyRokTabulku($skupina, $rok){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT * FROM Tabulky as t JOIN Skupiny as s ON t.id_skupiny = s.id
    WHERE s.kod="$skupina" AND rok="$rok";
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    	$pole[] = $row;
    }
    $db->close();	
    return $pole;
}

function vratKonkretnyRokNazovLigy($skupina, $rok){
	$db = napoj_db();
  $sql =<<<EOF
    SELECT l.nazov FROM Ligy as l JOIN Skupiny as s ON l.id_skupiny = s.id
     WHERE s.kod = "$skupina" AND l.rok = "$rok";
EOF;
  $ret = $db->query($sql);
  $row = $ret->fetchArray(SQLITE3_ASSOC);
  $db->close();	
  return $row['nazov'];
}

function vratPrisluchajuceRokyKSkupine($skupina){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT l.rok FROM Ligy as l JOIN Skupiny as s ON l.id_skupiny = s.id
    WHERE s.kod="$skupina" ORDER BY rok DESC;
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    	echo "<option>".$row['rok']."</option>";
    }
    $db->close();	
}

function najdiNajvacsiRok($skupina){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT l.rok FROM Ligy as l JOIN Skupiny as s ON l.id_skupiny = s.id
    WHERE s.kod = "skupina" ORDER BY rok DESC LIMIT 1;
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();	
    return $row['rok'];
}
?>