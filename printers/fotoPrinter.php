<?php  
function printPhotosOfAlbumWithId($id){
	$db = napoj_db();
	$sql =<<<EOF
	SELECT * FROM Fotky WHERE id_album = "$id";
EOF;
	$ret = $db->query($sql);
	$pole = array();
	while ($row = $ret->fetchArray(SQLITE3_ASSOC)){
		$pole[] = $row;
	}
	$db->close();
	printPhotosForTesting();
	print_r($pole);
}

function printRow(){

}

function printPhotosForTesting(){
		?>
	        <a href="fotky/f1.jpg" class="img-thumbnail">
                <img src="fotky/f1.jpg" />
            </a>
            <a href="fotky/f2.jpg" class="img-thumbnail">
                <img src="fotky/f2.jpg" />
            </a>
            <a href="fotky/f3.jpg" class="img-thumbnail">
                <img src="fotky/f3.jpg" />
            </a>
            <a href="fotky/f4.jpg" class="img-thumbnail">
                <img src="fotky/f4.jpg"/>
            </a>
            <a href="fotky/f5.jpg" class="img-thumbnail">
                <img src="fotky/f5.jpg"/>
            </a>
            <a href="fotky/f6.jpg" class="img-thumbnail">
                <img src="fotky/f6.jpg"/>
            </a>
            <a href="fotky/f7.jpg" class="img-thumbnail">
                <img src="fotky/f7.jpg" />
            </a>
            <a href="fotky/f8.jpg" class="img-thumbnail">
                <img src="fotky/f8.jpg" />
            </a>  
            <a href="https://www.youtube.com/watch?time_continue=2&v=3gt1Dc7lcPA" >
                <img src="fotky/video.jpg" width="75" height="75"/>
            </a>
	<?php
}

?>

