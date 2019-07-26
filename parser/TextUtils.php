<?php
class TextUtils{
	
	public static function retazecAt($text, $i){
      	return substr($text, $i, 1);
  	}

  	public static function nacitajSlovoPoKoniec($text, $i){
    	$resultString = "";
    	while (TextUtils::retazecAt($text, $i) != " " && TextUtils::retazecAt($text, $i) != "<" && $i < strlen($text)) {
      		$resultString .= TextUtils::retazecAt($text, $i);
      		$i++;
    	}
    	return $resultString;
  	}

    public static function nacitajSlovoPoZnak($text, $znak){
      $i = 0;
      $resultString = "";
      while (TextUtils::retazecAt($text, $i) != $znak && $i < strlen($text)) {
          $resultString .= TextUtils::retazecAt($text, $i);
          $i++;
      }
      return $resultString;
    }
	
	public static function preskocSlovo($text, $i){
  		while (TextUtils::retazecAt($text, $i) != " ") {
  			$i++;
  		}
  		$i++;	
  		return $i;
  	}
}
?>