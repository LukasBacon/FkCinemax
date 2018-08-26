<?php
include('parser/Parser.php');

/*nacita zapasy a tabulku z futbalnetu a naplni databazu*/
class dbLoader{

	/*overi ci treba aktualizovat tabulku zapasov a tabulku tabuliek*/
	public static function over(){
		$pole = dbLoader::najdiNaposledyOdohraneZapasyBezSkore();
		$pole += dbLoader::vratSkupinyARokyBezZapasov();
		foreach ($pole as $dvojica) {
			$skupina = $dvojica['skupina'];
			$rok = $dvojica['rok'];
			$url = dbLoader::ziskajUrlPodlaSkupinyARoku($skupina, $rok);
			dbLoader::aktualizujDatabazu($url, $skupina, $rok);
		}
	}

	public static function aktualizujDatabazu($url, $skupina, $rok){
		$parsovac = dbLoader::preparsujFutbalnet($url);
		dbLoader::aktualizujZapasy($parsovac, $skupina, $rok);
		dbLoader::aktualizujTabulky($parsovac, $skupina, $rok);
	}

	public static function preparsujFutbalnet($url){
		$parsovac = new Parser;
		$parsovac->parsuj($url);					
		return $parsovac;
	}

	public static function aktualizujZapasy($parsovac, $skupina, $rok){
		dbLoader::vymazUdajeZoZapasov($skupina, $rok);
		dbLoader::vlozAktualneZapasy($skupina, $rok, $parsovac->zapasy); 
	}

	public static function aktualizujTabulky($parsovac, $skupina, $rok){
		dbLoader::vymazUdajeZTabuliek($skupina, $rok);
		dbLoader::vlozAktualneDataTabulky($skupina, $rok, $parsovac->tabulka);
	}

	public static function ziskajUrlPodlaSkupinyARoku($skupina, $rok){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT url FROM Ligy WHERE skupina = "$skupina" AND rok = "$rok";
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();	
    return $row['url'];
	}

	public static function vymazUdajeZoZapasov($skupina, $rok){
    $db = napoj_db();
    $sql =<<<EOF
       DELETE FROM Zapasy WHERE skupina = "$skupina" AND rok = "$rok";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
	}

	public static function vymazUdajeZTabuliek($skupina, $rok){
    $db = napoj_db();
    $sql =<<<EOF
       DELETE FROM Tabulky WHERE skupina = "$skupina" AND rok = "$rok";
EOF;
    $ret = $db->exec($sql);
    if(!$ret){
      echo $db->lastErrorMsg();
    }
    $db->close();
	}

	public static function vlozAktualneZapasy($skupina, $rok, $zapasy){
		$db = napoj_db();
		foreach ($zapasy as $zapas) {
		  $sql =<<<EOF
    		INSERT INTO Zapasy (datum, rok, domaci, hostia, skoreD, skoreH, kolo, skupina)
    		VALUES ("$zapas->datum", "$rok", "$zapas->domaci", "$zapas->hostia", "$zapas->skoreDomaci", "$zapas->skoreHostia", "$zapas->kolo", "$skupina");
EOF;
		  $sql1 =<<<EOF
    		INSERT INTO Zapasy (datum, rok, domaci, hostia, kolo, skupina)
    		VALUES ("$zapas->datum", "$rok", "$zapas->domaci", "$zapas->hostia", "$zapas->kolo", "$skupina");
EOF;
			if($zapas->skoreDomaci === null && $zapas->skoreHostia === null){
		  		$ret = $db->exec($sql1);
			}
			else{
		  		$ret = $db->exec($sql);
			}
		  	if(!$ret){
		    	echo $db->lastErrorMsg();
		  	}	
		}
		$db->close();
	}

	public static function vlozAktualneDataTabulky($skupina, $rok, $tabulka){
		$db = napoj_db();
		foreach ($tabulka as $riadok) {
		  $sql =<<<EOF
    		INSERT INTO Tabulky (skupina, rok, klub, p_zapasov, p_vyhier, p_remiz, p_prehier, skore, body, poradie, fp)
    		VALUES ("$skupina", "$rok", "$riadok->klub", "$riadok->z", "$riadok->v", "$riadok->r", "$riadok->p", "$riadok->skore", "$riadok->b", "$riadok->poradie", "$riadok->fp");
EOF;
		  $ret = $db->exec($sql);
		  if(!$ret){
		    echo $db->lastErrorMsg();
		  }	
		}
		$db->close();
	}

	public static function najdiNaposledyOdohraneZapasyBezSkore(){
		$db = napoj_db();
    $sql =<<<EOF
    SELECT skupina, rok FROM Zapasy WHERE datum < datetime('now') AND skoreD is null AND skoreH is null;
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    	$pole[] = $row;
    }
    $db->close();	
    return $pole;
	}

	public static function vratSkupinyARokyBezZapasov(){
		$db = napoj_db();
    $sql =<<<EOF
			SELECT skupina, rok 
			FROM Ligy as l
			WHERE not exists 
				(SELECT * FROM Zapasy as z where l.skupina = z.skupina AND l.rok = z.rok);
EOF;
    $ret = $db->query($sql);
    $pole = array();
    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
    	$pole[] = $row;
    }
    $db->close();	
    return $pole;		
	}
}

?>