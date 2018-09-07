<?php
include('parser/Parser.php');

/*nacita zapasy a tabulku z futbalnetu a naplni databazu*/
class dbLoader{

	/*overi ci treba aktualizovat tabulku zapasov a tabulku tabuliek*/
	public static function over(){
		$start = microtime(true);
		echo "<script>console.log('over() started');</script>"; 
		$poleUpdata = dbLoader::najdiLigySNaposledyOdohranymiZapasmiBezSkore();
		$poleInsert = dbLoader::vratSkupinyARokyBezZapasov();
		echo "<script>console.log('toUpdate = " . json_encode($poleUpdata) .  "');</script>";
		echo "<script>console.log('toInsert = " . json_encode($poleInsert) .  "');</script>";

		// update zapasov:
		// 		ziskam si unikatne dvojice (skupina, rok) -> liga, v ktorych treba updatnut zapasy
		// 		kazdu tuto ligu updatnem -> ziskam zapasy, ktore treba updatnut, preparsujem ligu do pola, y pola ziskam udaje ktore potrebujem updatnut v zapasoch a updatnem
		//		nevyhody: ked sa zmeni nazov klubu v zapasoch tak sme v rici, update nejako musi upozornit ze neneslo zapas, ktory malo updatnut, v zapasoch z futbalnetu
		//                - mozne riesenie:
		//						spravit funkciu, ktora bz sa spustila, ked sa zmeni nazov nejakeho klubu
		//								fungovala by takto:
		//									- v adminovy pri zapasoch ligy by bol vzsuvaci formular na zmenu nazvu klubu
		//									- bol by tam select obsahujuci doterajsie nazvu klubov
		//									- vybral by sa, ktory treba zmenit a napisal by sa jeho novy nazov (ktory bude v tabulke, lebo tak bude vzdy rovnaka s futbalnetom)
		//									- v databaze zapasoch by sa kazdy vyskyt stareho klubu prepisal na novy -> potom by update uz zbehol 	
		foreach ($poleUpdata as $dvojica) {
			$skupina = $dvojica['skupina'];
			$rok = $dvojica['rok'];
			$url = dbLoader::ziskajUrlPodlaSkupinyARoku($skupina, $rok);
			dbLoader::aktualizujLigu($url, $skupina, $rok);
		}
		foreach ($poleInsert as $dvojica) {
			$skupina = $dvojica['skupina'];
			$rok = $dvojica['rok'];
			$url = dbLoader::ziskajUrlPodlaSkupinyARoku($skupina, $rok);
			dbLoader::vlozNoveUdajeDoDatabazy($url, $skupina, $rok);
		}
		echo "<script>console.log('DBLoader:over() exetution time = ". (microtime(true) - $start) ." sec');</script>"; 
	}

	public static function vlozNoveUdajeDoDatabazy($url, $skupina, $rok){
		// testovane na https://obfz-bratislava-vidiek.futbalnet.sk/sutaz/2440/print?part=3850
		$start = microtime(true);
		$parsovac = dbLoader::preparsujFutbalnet($url);
		dbLoader::vlozAktualneZapasy($skupina, $rok, $parsovac->zapasy); 
		dbLoader::vlozAktualneDataTabulky($skupina, $rok, $parsovac->tabulka);
		echo "<script>console.log('DBLoader:vlozNoveUdajeDoDatabazy() exetution time = ". (microtime(true) - $start) ." sec');</script>";
	}


	public static function aktualizujLigu($url, $skupina, $rok){
		$start = microtime(true);
		$parsovac = dbLoader::preparsujFutbalnet($url);
		dbLoader::aktualizujZapasy($parsovac, $skupina, $rok);
		dbLoader::aktualizujTabulky($parsovac, $skupina, $rok);
		echo "<script>console.log('DBLoader:aktualizujLigu(". $skupina.", ".$rok.") exetution time = ". (microtime(true) - $start) ." sec');</script>"; 
	}

	public static function preparsujFutbalnet($url){
		$start = microtime(true);
		$parsovac = new Parser;
		$parsovac->parsuj($url);	
		echo "<script>console.log('DBLoader:preparsuj() exetution time = ". (microtime(true) - $start) ." sec');</script>"; 				
		return $parsovac;
	}

	public static function aktualizujZapasy($parsovac, $skupina, $rok){
		$start = microtime(true);
		$zapasy = dbLoader::vratOdohraneZapasyLigyBezSkore($skupina, $rok);	
		echo "<script>console.log('zapasyToUpdate (". $rok . ", " . $skupina . ") = " . json_encode($zapasy) . "');</script>";
		foreach ($zapasy as $zapas) {
			dbLoader::aktualizujDetailyZapasu($zapas, $parsovac->zapasy);
		}	
		echo "<script>console.log('DBLoader:aktualizujZapasy() exetution time = ". (microtime(true) - $start) ." sec');</script>"; 
	}

	public static function aktualizujTabulky($parsovac, $skupina, $rok){
		$start = microtime(true);
		dbLoader::vymazUdajeZTabuliek($skupina, $rok); 
		dbLoader::vlozAktualneDataTabulky($skupina, $rok, $parsovac->tabulka);
		echo "<script>console.log('DBLoader:aktualizujTabulky() exetution time = ". (microtime(true) - $start) ." sec');</script>"; 
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
		$db->exec('BEGIN;');
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
		$db->exec('COMMIT;');
		$db->close();
	}

	public static function vlozAktualneDataTabulky($skupina, $rok, $tabulka){
		$db = napoj_db();
		$db->exec('BEGIN;');
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
		$db->exec('COMMIT;');
		$db->close();
	}

	public static function aktualizujDetailyZapasu($zapas, $parserZapasy){
		// v $parserZapasy najde zapas $parserZapas, ktory je rovny zapasu $zapas (domaci, hostia, kolo)
		// v databaze atualizuje zapas $zapas udajmi zo $parserZapas (skoreDomaci, skoreHostia, datum)
		// ak $parserZapas nema skore .. daj NULL do db

		// zapas je riadok tabulky, $parserZapas je objekt typu Zapas (parser/Zapas.php)
		$parserZapas = DBLoader::najdiRovnakyZapasVParserZapasoch($zapas, $parserZapasy);
		$id = $zapas['id'];
		$db = napoj_db();
	    $sql1 =<<<EOF
	    	UPDATE Zapasy SET skoreD = "$parserZapas->skoreDomaci", skoreH = "$parserZapas->skoreHostia", datum = "$parserZapas->datum" WHERE id = "$id";
EOF;
		$sql2 =<<<EOF
	    	UPDATE Zapasy SET datum = "$parserZapas->datum" WHERE id = "$id";
EOF;

	    if($parserZapas->skoreDomaci === null && $parserZapas->skoreHostia === null){
		  	$ret = $db->exec($sql2);
		}
		else{
		  	$ret = $db->exec($sql1);
		}
	}

	public static function najdiRovnakyZapasVParserZapasoch($zapas, $parserZapasy){
		foreach ($parserZapasy as $parserZapas) {
			if ($parserZapas->kolo === $zapas["kolo"] && $parserZapas->domaci === $zapas["domaci"] && $parserZapas->hostia === $zapas["hostia"]){
				return $parserZapas;
			}
		}
		// nenaslo 
		// nejako na to upozorni !!!
		// TODO
	}

	public static function najdiLigySNaposledyOdohranymiZapasmiBezSkore(){
		$db = napoj_db();
	    $sql =<<<EOF
	    SELECT DISTINCT skupina, rok FROM Zapasy WHERE datum < datetime('now') AND skoreD is null AND skoreH is null;
EOF;
	    $ret = $db->query($sql);
	    $pole = array();
	    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	    	$pole[] = $row;
	    }
	    $db->close();	
	    return $pole;
	}

	public static function vratOdohraneZapasyLigyBezSkore($skupina, $rok){
		$db = napoj_db();
	    $sql =<<<EOF
	    SELECT * FROM Zapasy WHERE datum < datetime('now') AND skoreD is null AND skoreH is null AND rok = "$rok" AND skupina = "$skupina";
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