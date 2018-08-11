<?php
include('../funkcie.php');

if (isset($_POST['submit'])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif");
    $album = $_POST['albumName'];
    $url = $_POST['url'];
    $idAlbumu = $_POST['idAlbumu'];
    foreach($_FILES["files"]["name"] as $key => $$file_name){
	    $file_name=$_FILES["files"]["name"][$key];
	    $file_tmp=$_FILES["files"]["tmp_name"][$key];
	    $ext=pathinfo($file_name,PATHINFO_EXTENSION);
	    if(in_array($ext,$extension)){
	        $filename=replaceSpecialChars(basename($file_name,$ext));
	        $destination = dirname(getcwd()) . "/fotky/".$album."/".$filename."-".time().".".$ext;
	        $fileURL = "fotky/".$album."/".$filename."-".time().".".$ext;
	        if(!file_exists($destination)){
	            move_uploaded_file($file_tmp=$_FILES["files"]["tmp_name"][$key],$destination);
	            compressImage($destination);
	            pridajDoDatabazy($idAlbumu, $fileURL);
	        }
	    }
    }
    unset($_FILES['uploadedfile']);
    header('Location: ' . $url);
}



function compressImage($pathToImage)
{
	# TODO
	# zmensi velskost a format (JPG) obrazka
}

function pridajDoDatabazy($idAlbumu, $fileURL){
	$db = napoj_db();
  	$sql =<<<EOF
		INSERT INTO Fotky (url, datum, id_album) VALUES ("$fileURL", date('now'), "$idAlbumu");
EOF;
  	$ret = $db->query($sql);
  	$db->close();	
}


?>