<?php
function vypis_albumy(){
	$db = napoj_db();
  $sql =<<<EOF
		SELECT a.id, a.nazov, a.nazov_priecinku, f.url FROM Albumy as a JOIN Fotky as f
		ON f.id_album = a.id 
		GROUP BY nazov
		ORDER BY a.datum;
EOF;
  $ret = $db->query($sql);
  $pocet = 0;
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  	if($pocet % 3 == 0){
  		echo "<div style='' class='row'>";
  	}
		echo '<div class="col-sm-4">';
				echo '<a id="aWithoutTextHover" href = "album.php?id='.$row['id'].'&nazov='.$row['nazov'].'&nazovPriecinku='.$row['nazov_priecinku'].'">';
					echo '<div id="cardAlbum" class="card mb-4">';
						echo '<div class="card-body p-0">';
								echo '<img id="albumImg" src="'.$row['url'].'">'; 
			    	echo '</div>';
			    	echo '<div class="card-footer" style="text-align:left;">';
			    		echo '<p style="color:black;">'.$row['nazov'].'</p>';
			    	echo '</div>';
			    echo '</div>';
		    echo '</a>';
    echo '</div>';
    if($pocet % 3 == 2){
  		echo "</div>";
  	}
  	$pocet += 1;
  }
  $db->close();	
}

function vypis_albumy_admin(){

}

function pridaj_album($nazov){
	$nazovPriecinku = replaceSpecialChars($nazov);
	$db = napoj_db();
	$sql =<<<EOF
	SELECT exists (SELECT * FROM albumy WHERE nazov = "$nazov" OR nazov_priecinku = "$nazovPriecinku") as exist;
EOF;
  	$ret = $db->query($sql);
  	$row = $ret->fetchArray(SQLITE3_ASSOC); 
  	if($row['exist'] == 1) {
  		$db->close();	
  		return false;
	}

  	$sql =<<<EOF
		INSERT INTO Albumy (nazov, nazov_priecinku, datum) VALUES ("$nazov", "$nazovPriecinku", date('now'));
EOF;
  	$ret = $db->query($sql);
  	$db->close();	

  	$cestaKPrieckinku = getcwd() . "/fotky/".$nazovPriecinku;
  	if (!file_exists($cestaKPrieckinku)) {
    	mkdir($cestaKPrieckinku);
	}
	return true;
}
?>