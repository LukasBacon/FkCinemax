<?php
class RiadokTabulky{
	public $poradie;
	public $klub;
	public $z;
	public $v;
	public $r;
	public $p;
	public $skore;
	public $b;
	public $fp;

	public function toString(){
		return ("RiadokTabulky = [poradie = " . $this->poradie . ", klub = " . $this->klub . ", z = " . $this->z . ", v = " . $this->v . 
			", r = " . $this->r . ", p = " . $this->p . ", skore = " . $this->skore . ", b = " . $this->b . ", fp = " . $this->fp . "]");
	}
}
?>