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
			    		echo '<p style="color:black; font-weight-bold">'.$row['nazov'].'</p>';
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
	if(isset($_POST['novyAlbumBtn'])){
		pridaj_album($_POST['nazov']);
	}
	vypis_novy_album_karta();

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
  	if($pocet % 3 == 2){
  		echo "<div style='' class='row'>";
  	}
		echo '<div class="col-sm-4">';
				echo '<a id="aWithoutTextHover" href = "album.php?id='.$row['id'].'&nazov='.$row['nazov'].'&nazovPriecinku='.$row['nazov_priecinku'].'">';
					echo '<div id="cardAlbum" class="card mb-4">';
						echo '<div class="card-body p-0">';
							echo '<a href="javascript:vymazAlbum('.$row['id'].');">';
								echo '<img id="cancelImg" src="fotky/cancel.png">';
							echo '</a>';
							echo '<img id="albumImg" src="'.$row['url'].'">'; 
			    	echo '</div>';
			    	echo '<div class="card-footer text-left">';
			    		echo '<p id="albumNazov'.$row['id'].'" style="color:black;" class="float-left font-weight-bold">'.$row['nazov'].'</p>';
			    		echo '<input id="upravAlbumInput'.$row['id'].'" class=" float-left" type="text" required hidden>';
			    		echo '<a id="upravAlbumBtn'.$row['id'].'" class="d-inline float-right btn btn-admin" href="javascript:upravAlbum('.$row['id'].');">Uprav</a>';
			    	echo '</div>';
			    echo '</div>';
		    echo '</a>';
    echo '</div>';
    if($pocet % 3 == 1){
  		echo "</div>";
  	}
  	$pocet += 1;
  }
  $db->close();	
}

function vypis_novy_album_karta(){
	echo '<div class="row">';
		echo '<div class="col-sm-4">';
			echo '<div class="card" id="cardAlbum">';
				echo '<form>';
					echo '<div class="card-body p-5 bg-black text-center" style="height:281px;">';
							echo '<h4 class="card-title text-white">Pridaj nový album</h4>';
							echo '<input name="nazov" type="text" placeholder="Názov albumu" style="" required>';
					echo '</div>';
					echo '<div class="card-footer text-center">';
						echo '<input type="submit" name="novyAlbumBtn" value="Pridaj" class="btn btn-success">';
					echo '</div>';
				echo '</form>';
			echo '</div>';
		echo '</div>';
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