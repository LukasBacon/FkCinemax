<?php
include('../funkcie.php');

if (isset($_POST['submit'])){
    $error=array();
    $extension=array("jpeg","jpg", "JPG","png");
    $url = $_POST['url'];
    $idAlbumu = $_POST['idAlbumu'];
    $album = getAlbumName($idAlbumu);
    foreach($_FILES["files"]["name"] as $key => $file_name){
	    $file_name=$_FILES["files"]["name"][$key];
	    $file_tmp=$_FILES["files"]["tmp_name"][$key];
	    $ext=pathinfo($file_name,PATHINFO_EXTENSION);
	    if(in_array($ext,$extension)){
	        $filename=replaceSpecialChars(basename($file_name,$ext));
		$fileURL = "fotky/".$album."/fk-cinemax-dolany-".$filename."-".time().".".$ext;
	        $destination = dirname(getcwd()) . "/" . $fileURL;
	        if(!file_exists($destination)){
	            move_uploaded_file($file_tmp,$destination);
                $cas = date('Y-m-d G:i:s', filectime($destination));
	            compressImage($destination);
	            pridajDoDatabazy($idAlbumu, $fileURL, $cas);
	        }
	    }
    }
    unset($_FILES['uploadedfile']);
    header('Location: ' . $url);
}

function getAlbumName($id){
    $db = napoj_db();
    $sql =<<<EOF
        SELECT * FROM Albumy WHERE id="$id";
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $row['nazov_priecinku'];
}

function pridajDoDatabazy($idAlbumu, $fileURL, $cas){
	$db = napoj_db();
  	$sql =<<<EOF
		INSERT INTO Fotky (url, cas, id_album) VALUES ("$fileURL", "$cas", "$idAlbumu");
EOF;
  	$ret = $db->query($sql);
  	$db->close();	
}

?>
