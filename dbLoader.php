<?php
include('parser/Parser.php');

date_default_timezone_set('Europe/Bratislava');
error_reporting(E_ALL ^ E_DEPRECATED);

/*nacita zapasy a tabulku z futbalnetu a naplni databazu*/
class dbLoader{

	public $parsery = array();
	private $response;

	/*overi ci treba aktualizovat tabulku zapasov a tabulku tabuliek*/
	public function over(){
		$this->response = new OverResponse;
		$properties = parse_ini_file("properties.ini");
		$this->response->setEnableConsoleLog($properties["enableConsoleLog"]);
		$start = microtime(true);
		$this->response->addMessage("over() started");
		$poleUpdata = $this->najdiLigySNaposledyOdohranymiZapasmiBezSkore();
		$poleInsert = $this->vratSkupinyARokyBezZapasov();
		$this->response->addMessage("toUpdate = " . json_encode($poleUpdata));
		$this->response->addMessage("toInsert = " . json_encode($poleInsert));
		foreach ($poleUpdata as $dvojica) {
			$skupina = $dvojica['skupina'];
			$rok = $dvojica['rok'];
			$url = $this->ziskajUrlPodlaSkupinyARoku($skupina, $rok);
			$status = $this->aktualizujLigu($url, $skupina, $rok);
			if ($status == false) {
				return;
			}
		}
		foreach ($poleInsert as $dvojica) {
			$skupina = $dvojica['skupina'];
			$rok = $dvojica['rok'];
			$url = $this->ziskajUrlPodlaSkupinyARoku($skupina, $rok);
			$this->vlozNoveUdajeDoDatabazy($url, $skupina, $rok);
		}
		$this->response->addMessage("over() exetution time = ". (microtime(true) - $start) . " sec");
		return $this->response;
	}

	public function overDatumyNasledujucichNZapasov($n, $skupina){
		$response = new OverResponse;
		$properties = parse_ini_file("properties.ini");
		$response->setEnableConsoleLog($properties["enableConsoleLog"]);
		$start = microtime(true);
		$db = napoj_db();
	    $sql1 =<<<EOF
	    SELECT z.*, l.url  FROM Zapasy as z JOIN Ligy as l ON l.id_skupiny = z.id_skupiny AND l.rok = z.rok
		WHERE z.datum > datetime('now') AND (z.hostia LIKE 'FK CINEMAX Doľany%' OR z.domaci LIKE 'FK CINEMAX Doľany%') ORDER BY z.datum ASC LIMIT "$n";
EOF;
	    $sql2 =<<<EOF
	    SELECT z.*, l.url  FROM Zapasy as z JOIN Ligy as l ON l.id_skupiny = z.id_skupiny AND l.rok = z.rok
	    JOIN Skupiny as s ON l.id_skupiny = s.id
		WHERE z.datum > datetime('now') AND s.kod = "$skupina" AND (z.hostia LIKE 'FK CINEMAX Doľany%' OR z.domaci LIKE 'FK CINEMAX Doľany%') ORDER BY z.datum ASC LIMIT "$n";
EOF;
		if ($skupina == null) $ret = $db->query($sql1);
		else {$ret = $db->query($sql2);}
	    $pole = array();
	    while ($row=$ret->fetchArray(SQLITE3_ASSOC)){
	    	$pole[] = $row;
	    }
	    $db->close();
	    foreach($pole as $zapas){
	    	$url = $zapas["url"];
	    	$parser = null;
	    	if (isset($this->parsery[$url])){
	    		$parser = $this->parsery[$url];
	    	}
	    	else{
	    		$parser = $this->preparsujFutbalnet($url, $response);
	    		if ($parser == null) {
	    			return;
	    		}
	    	}
	    	$parserZapas = $this->najdiRovnakyZapasVParserZapasoch($zapas, $parser->zapasy)["zapas"];
			$response->addMessage("overenie = ". $zapas["datum"] . ", ". $parserZapas->datum);
	    	if ($zapas["datum"] != $parserZapas->datum){
	    		$response->addMessage("zle ".$zapas["id"] . ", ". $parserZapas->datum);
	    		$this->updatniDatumZapasuSId($zapas["id"], $parserZapas->datum);
	    	}
	    }
		$response->addMessage("overDatumyNasledujucichNZapasov(".$n.") exetution time = ". (microtime(true) - $start) ." sec");
	    return $response;
	}

	public function vlozNoveUdajeDoDatabazy($url, $skupina, $rok){
		$start = microtime(true);
		$parsovac = $this->preparsujFutbalnet($url, $this->response);
		if ($parsovac == null) {
			return;
		}
		$this->vlozAktualneZapasy($skupina, $rok, $parsovac->zapasy);
		$this->vlozAktualneDataTabulky($skupina, $rok, $parsovac->tabulka);
		$this->response->addMessage("vlozNoveUdajeDoDatabazy() exetution time = ". (microtime(true) - $start) ." sec");
	}

	public function aktualizujLigu($url, $skupina, $rok){
		$start = microtime(true);
		$parsovac = $this->preparsujFutbalnet($url, $this->response);
		if ($parsovac == null) {
			return false;
		}
		$this->aktualizujZapasy($parsovac, $skupina, $rok);
		$this->aktualizujTabulky($parsovac, $skupina, $rok);
		$this->response->addMessage("aktualizujLigu(". $skupina.", ".$rok.") exetution time = ". (microtime(true) - $start) ." sec");
		return true;
	}

	public function preparsujFutbalnet($url, $response){
		$start = microtime(true);
		$parsovac = new Parser;
		$status = $parsovac->parsuj($url);
		if ($status == false) {
			return null;
		}
		$response->addMessage("preparsuj() exetution time = ". (microtime(true) - $start) ." sec");
		if (!isset($this->parsery[$url])){
			$this->parsery[$url] = $parsovac;
		}
		return $parsovac;
	}

	public function aktualizujZapasy($parsovac, $skupina, $rok){
		$start = microtime(true);
		$zapasy = $this->vratOdohraneZapasyLigyBezSkore($skupina, $rok);
		$this->response->addMessage("zapasyToUpdate (". $rok . ", " . $skupina . ") = " . json_encode($zapasy));
		foreach ($zapasy as $zapas) {
			$this->aktualizujDetailyZapasu($zapas, $parsovac->zapasy);
		}
		$this->response->addMessage("aktualizujZapasy() exetution time = ". (microtime(true) - $start) ." sec");
	}

	public function aktualizujTabulky($parsovac, $skupina, $rok){
		$start = microtime(true);
		$this->vymazUdajeZTabuliek($skupina, $rok);
		$this->vlozAktualneDataTabulky($skupina, $rok, $parsovac->tabulka);
		$this->response->addMessage("aktualizujTabulky() exetution time = ". (microtime(true) - $start) ." sec");
	}

	public function ziskajUrlPodlaSkupinyARoku($skupina, $rok){
		$db = napoj_db();
	    $sql =<<<EOF
	    SELECT url FROM Ligy as l
	    JOIN Skupiny as s ON l.id_skupiny = s.id
	    WHERE s.kod = "$skupina" AND rok = "$rok";
EOF;
	    $ret = $db->query($sql);
	    $row = $ret->fetchArray(SQLITE3_ASSOC);
	    $db->close();
	    return $row['url'];
	}

	public function vymazUdajeZTabuliek($skupina, $rok){
	    $db = napoj_db();
	    $sql =<<<EOF
	       DELETE FROM Tabulky WHERE rok = "$rok" AND
	       id_skupiny IN (SELECT id FROM skupiny WHERE kod="$skupina");
EOF;
	    $ret = $db->exec($sql);
	    if(!$ret){
	      echo $db->lastErrorMsg();
	    }
	    $db->close();
	}

	public function vlozAktualneZapasy($skupinaKod, $rok, $zapasy){
		$skupinaId = $this->dajSkupinuPodlaKodu($skupinaKod)["id"];
		$db = napoj_db();
		$db->exec('BEGIN;');
		foreach ($zapasy as $zapas) {
		  $sql =<<<EOF
    		INSERT INTO Zapasy (datum, rok, domaci, hostia, skoreD, skoreH, kolo, id_skupiny)
    		VALUES ("$zapas->datum", "$rok", "$zapas->domaci", "$zapas->hostia", "$zapas->skoreDomaci", "$zapas->skoreHostia", "$zapas->kolo", "$skupinaId");
EOF;
		  $sql1 =<<<EOF
    		INSERT INTO Zapasy (datum, rok, domaci, hostia, kolo, id_skupiny)
    		VALUES ("$zapas->datum", "$rok", "$zapas->domaci", "$zapas->hostia", "$zapas->kolo", "$skupinaId");
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

	public function vlozAktualneDataTabulky($skupinaKod, $rok, $tabulka){
		$skupinaId = $this->dajSkupinuPodlaKodu($skupinaKod)["id"];
		$db = napoj_db();
		$db->exec('BEGIN;');
		foreach ($tabulka as $riadok) {
		  $sql =<<<EOF
    		INSERT INTO Tabulky (id_skupiny, rok, klub, p_zapasov, p_vyhier, p_remiz, p_prehier, skore, body, poradie, fp)
    		VALUES ("$skupinaId", "$rok", "$riadok->klub", "$riadok->z", "$riadok->v", "$riadok->r", "$riadok->p", "$riadok->skore", "$riadok->b", "$riadok->poradie", "$riadok->fp");
EOF;
		  $ret = $db->exec($sql);
		  if(!$ret){
		    echo $db->lastErrorMsg();
		  }
		}
		$db->exec('COMMIT;');
		$db->close();
	}

	public function aktualizujDetailyZapasu($zapas, $parserZapasy){
		// v $parserZapasy najde zapas $parserZapas, ktory je rovny zapasu $zapas (domaci, hostia, kolo)
		// v databaze atualizuje zapas $zapas udajmi zo $parserZapas (skoreDomaci, skoreHostia, datum)
		// ak $parserZapas nema skore .. daj NULL do db
		// zapas je riadok tabulky, $parserZapas je objekt typu Zapas (parser/Zapas.php)
		$tuple = $this->najdiRovnakyZapasVParserZapasoch($zapas, $parserZapasy);
		$parserZapas = $tuple['zapas'];
		$isInversed = $tuple['inversed'];
		if ($parserZapas == null){
			return;
		}
		$id = $zapas['id'];
		$db = napoj_db();
	    $sql1 =<<<EOF
	    	UPDATE Zapasy SET skoreD = "$parserZapas->skoreDomaci", skoreH = "$parserZapas->skoreHostia", datum = "$parserZapas->datum" WHERE id = "$id";
EOF;
		$sql2 =<<<EOF
	    	UPDATE Zapasy SET datum = "$parserZapas->datum" WHERE id = "$id";
EOF;
		$sql3 =<<<EOF
	    	UPDATE Zapasy SET 	domaci = "$parserZapas->domaci",
	    						hostia = "$parserZapas->hostia",
	    						skoreD = "$parserZapas->skoreDomaci",
	    						skoreH = "$parserZapas->skoreHostia",
	    						datum = "$parserZapas->datum" WHERE id = "$id";
EOF;
		$sql4 =<<<EOF
	    	UPDATE Zapasy SET 	domaci = "$parserZapas->domaci",
	    						hostia = "$parserZapas->hostia",
	    						datum = "$parserZapas->datum" WHERE id = "$id";
EOF;
	    if($parserZapas->skoreDomaci === null && $parserZapas->skoreHostia === null){
	    	if ($isInversed){
	    		// ak je skore null a domaci a hostia sa vymenili
	    		$ret = $db->exec($sql4);
	    	}
	    	else{
	    		// ak je skore null a domaci a hostia sa nevymenili
	    		$ret = $db->exec($sql2);
	    	}
		}
		else{
		    if ($isInversed){
		    	// ak skore nie je null a domaci a hostia sa vymenili
	    		$ret = $db->exec($sql3);
	    	}
	    	else{
	    		// ak skore nie je null a domaci a hostia sa nevymenili
	    		$ret = $db->exec($sql1);
	    	}
		}
		$db->close();
	}
	
	public function najdiRovnakyZapasVParserZapasoch($zapas, $parserZapasy){
		foreach ($parserZapasy as $parserZapas) {
			if ($parserZapas->kolo === $zapas["kolo"]){
				if ($parserZapas->domaci === $zapas["domaci"] && $parserZapas->hostia === $zapas["hostia"]){
					return array('zapas' => $parserZapas,'inversed' =>  false);
				}
				if ($parserZapas->domaci === $zapas["hostia"] && $parserZapas->hostia === $zapas["domaci"]){
					return array('zapas' => $parserZapas,'inversed' =>  true);
				}
			}
		}
		// nenasiel k nemu rovnaky zapas
		$to      = 'lukasslaninka19@gmail.com';
		$subject = 'FK Cinemax Parser error';
		$message = '' .json_encode($zapas).' was not found in $parserZapasy.';
		//echo "<script>console.log('mail message = ". $message ."');</script>";
		$headers = 'From: fkcinemaxparser@parser.com' . "\r\n" .
		    'Reply-To: fkcinemaxparser@parser.com' . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		set_error_handler(
		    create_function(
		        '$severity, $message, $file, $line',
		        ''
		    )
		);
		try {
		    mail($to, $subject, $message, $headers);
		}
		catch (Exception $e) {
		    //echo "<script>console.log('Mail was not sent.');</script>";
		}
		restore_error_handler();
		return array('zapas' => null, 'inversed' =>  false);
	}

	public function najdiLigySNaposledyOdohranymiZapasmiBezSkore(){
		$db = napoj_db();
	    $sql =<<<EOF
	    SELECT DISTINCT s.kod as skupina, z.rok FROM Zapasy as z
	    JOIN Skupiny as s ON s.id = z.id_skupiny
	    WHERE 
				datum < datetime('now') 
					AND 
				(	
					(skoreD is null AND skoreH is null) 
							OR 
					(skoreD=0 AND skoreH=0 AND julianday('now') - julianday(datum) < 100 )
				);
EOF;
	    $ret = $db->query($sql);
	    $pole = array();
	    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	    	$pole[] = $row;
	    }
	    $db->close();
	    return $pole;
	}

	public function vratOdohraneZapasyLigyBezSkore($skupina, $rok){
		$db = napoj_db();
	    $sql =<<<EOF
	    SELECT z.* FROM Zapasy as z 
	    JOIN Skupiny as s ON s.id = z.id_skupiny
	    WHERE datum < datetime('now') 
		   AND ((skoreD is null AND skoreH is null) OR (skoreD=0 AND skoreH=0 AND julianday('now') - julianday(datum) < 100 ))
		   AND rok = "$rok" AND s.kod = "$skupina";
EOF;
	    $ret = $db->query($sql);
	    $pole = array();
	    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	    	$pole[] = $row;
	    }
	    $db->close();
	    return $pole;
	}

	public function vratSkupinyARokyBezZapasov(){
		$db = napoj_db();
	    $sql =<<<EOF
				SELECT s.kod as skupina, l.rok
				FROM Ligy as l
				JOIN Skupiny as s ON l.id_skupiny = s.id
				WHERE not exists
					(SELECT * FROM Zapasy as z where l.id_skupiny = z.id_skupiny AND l.rok = z.rok);
EOF;
	    $ret = $db->query($sql);
	    $pole = array();
	    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
	    	$pole[] = $row;
	    }
	    $db->close();
	    return $pole;
	}

	public function updatniDatumZapasuSId($id, $datum){
		$db = napoj_db();
	    $sql =<<<EOF
	    	UPDATE Zapasy SET datum = "$datum" WHERE id = "$id";
EOF;
		$ret = $db->exec($sql);
		$db->close();
	}

	private function dajSkupinuPodlaKodu($kod) {
		$db = napoj_db();
		$sql =<<<EOF
        SELECT * FROM Skupiny WHERE kod="$kod";
EOF;
		$ret = $db->query($sql);
		$res = $ret->fetchArray(SQLITE3_ASSOC);
		$db->close();
		return $res;
	}
}

class OverResponse{

	public $messages = array();
	public $enableConsoleLog;

	public function addMessage($message) {
		$this->messages[] = $message;
	}

	public function setEnableConsoleLog($enableConsoleLog) {
		$this->enableConsoleLog = $enableConsoleLog;
	}

}


?>
