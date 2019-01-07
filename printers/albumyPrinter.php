<?php
function vypis_albumy(){
	$db = napoj_db();
  $sql =<<<EOF
		SELECT a.id, a.nazov, f.url FROM Albumy as a LEFT JOIN Fotky as f
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
				echo '<a id="aWithoutTextHover" href = "album.php?id='.$row['id'].'&nazov='.$row['nazov'].'">';
					echo '<div id="cardAlbum" class="card mb-4">';
						echo '<div class="card-body p-0">';
							if ($row['url'] == NULL){
								echo '<img id="albumImg" src="fotky/no_photo.jpg">'; 
							}
							else{
								echo '<img id="albumImg" src="'.$row['url'].'">'; 
							}
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
	vypis_novy_album_karta();

	$db = napoj_db();
  $sql =<<<EOF
		SELECT a.id, a.nazov, f.url FROM Albumy as a LEFT JOIN Fotky as f
		ON f.id_album = a.id 
		GROUP BY nazov
		ORDER BY a.datum DESC;
EOF;
  $ret = $db->query($sql);
  $pocet = 0;
  while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
  	if($pocet % 3 == 2){
  		echo "<div style='' class='row'>";
  	}
		echo '<div class="col-sm-4">';
			echo '<div id="cardAlbum" class="card mb-4">';
				echo '<div class="card-body p-0">';
					echo '<a href="javascript:vymazAlbum('.$row['id'].');">';
						echo '<img id="cancelImg" src="fotky/remove.png">';
					echo '</a>';
					echo '<a id="aWithoutTextHover" href = "album.php?id='.$row['id'].'&nazov='.$row['nazov'].'">';
						if ($row['url'] == NULL){
							echo '<img id="albumImg" src="fotky/no_photo.jpg">'; 
						}
						else{
							echo '<img id="albumImg" src="'.$row['url'].'">'; 
						}
					echo '</a>';
			    echo '</div>';
			    echo '<div class="card-footer text-left">';
			    	echo '<p id="albumNazov'.$row['id'].'" style="color:black;" class="float-left font-weight-bold">'.$row['nazov'].'</p>';
			    	echo '<input id="upravAlbumInput'.$row['id'].'" class=" float-left" type="text" required hidden>';
			    		echo '<a id="upravAlbumBtn'.$row['id'].'" class="d-inline float-right" href="javascript:upravAlbum('.$row['id'].');"><img class="buttonImg withHover" src="fotky/edit.png" width="40"></a>';
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
				echo '<form method="post">';
					echo '<div class="card-body pt-5 bg-black text-center" style="height:281px;">';
							echo '<h4 class="card-title text-white">Pridaj nový album</h4>';
							echo '<input name="nazov" id="inputNewAlbum" type="text" placeholder="Názov albumu" required>';
					echo '</div>';
					echo '<div class="card-footer text-center">';
						echo '<a class="buttonImg withHover" id="addNewAlbumBtn" href="javascript:pridajAlbum()"><img class="withHover" src="fotky/add.png" width="40"></a>';
					echo '</div>';
				echo '</form>';
			echo '</div>';
		echo '</div>';

}

?>