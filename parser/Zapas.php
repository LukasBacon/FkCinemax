<?php
class Zapas{
	public $kolo;
	public $datum;
	public $domaci;
	public $hostia;
	public $skoreDomaci;
	public $skoreHostia;

	public function nacitajDatumACas($datumCas){
		// 27.08.2016 17:00 -> YYYY-MM-DD HH:MM
		$den = substr($datumCas, 0, 2);
		$mesiac = substr($datumCas, 3, 2);
		$rok = substr($datumCas, 6, 4);
		$hodiny = substr($datumCas, 11, 2);
		$minuty = substr($datumCas, 14, 2);
		$this->datum = $rok . "-" . $mesiac . "-" . $den . " " . $hodiny . ":" . $minuty;
	}

	public function toString(){
		return ("Zapas = [kolo = " . $this->kolo . ", datum = " . $this->datum . ", domaci = " . $this->domaci . ", skoreDomaci = " . $this->skoreDomaci .
				", hostia = " . $this->hostia . ", skoreHostia = " . $this->skoreHostia ."]");
	}
}
?>