<?php
function vypis_albumy(){
	$db = napoj_db();
  $sql =<<<EOF
		SELECT a.id, a.nazov, f.url FROM Albumy as a JOIN Fotky as f
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
			echo '<a style= "color:black;" href = "album.php?id='.$row['id'].'&'.$row['nazov'].'">';
				echo '<div style="border:solid black 1px;" class="card mb-4">';
		    	echo '<div class="card-header">';
		    		echo '<p>'.$row['nazov'].'</p>';
		    	echo '</div>';
					echo '<div style="" class="card-body">';
		    		echo '<img class="card-img-top img-thumbnail background-size:cover;" style="" 
		    		src="'.$row['url'].'">'; 
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

?>