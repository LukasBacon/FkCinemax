<?php
include('../funkcie.php');

if (isset($_POST['submit'])){
    $error=array();
    $extension=array("jpeg","jpg", "JPG","png");
    $meno = $_POST[''];
    $priezvisko = $_POST[''];
    $rocnik = $_POST[''];
    $post = $_POST[''];
    $timy = $_POST[''];
    $skupina = $_POST[''];
    $fileURL = "";
    if($skupina == 'Seniori'){
        if(nofile){
            $fileURL = 'seniori/foto.png';
        }
        else{
            $fileURL = 'seniori/'+$meno+$priezvisko+koncovka;
        }
    }
        if(nofile){
            $fileURL = 'pripravka/foto.png';
        }
        else{
            $fileURL = 'pripravka/'+$meno+$priezvisko+koncovka;
        }    else{

    }
    /*neviem co*/
    foreach($_FILES["files"]["name"] as $key => $file_name){
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
	            pridajDoDatabazy($meno, $priezvisko, $rocnik, $post, $timy, $fileURL, $skupina);
	        }
	    }
    }
    unset($_FILES['uploadedfile']);
    header('Location: ' . $url);
}

function compressImage($pathToImage){
	list($width, $height) = getimagesize($pathToImage);
	if ($width > $height){
		resizeImage($pathToImage, $pathToImage, 1000, 0, 100);
	}
	else{
		resizeImage($pathToImage, $pathToImage, 0, 1000, 100);
	}
}

function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 80){
    // Obtain image from given source file.
    if (!$image = @imagecreatefromjpeg($sourceImage))
    {
        return false;
    }
    // Get dimensions of source image.
    list($origWidth, $origHeight) = getimagesize($sourceImage);
    if ($maxWidth == 0)
    {
        $maxWidth  = $origWidth;
    }

    if ($maxHeight == 0)
    {
        $maxHeight = $origHeight;
    }
    // Calculate ratio of desired maximum sizes and original sizes.
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;
    // Ratio used for calculating new image dimensions.
    $ratio = min($widthRatio, $heightRatio);
    // Calculate new image dimensions.
    $newWidth  = (int)$origWidth  * $ratio;
    $newHeight = (int)$origHeight * $ratio;
    // Create final image with new dimensions.
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    imagejpeg($newImage, $targetImage, $quality);
    // Free up the memory.
    imagedestroy($image);
    imagedestroy($newImage);
    return true;
}

function pridajDoDatabazy($meno, $priezvisko, $rocnik, $post, $timy, $fileURL, $skupina){
	$db = napoj_db();
  	$sql =<<<EOF
		INSERT INTO Hraci (meno, priezvisko, rok_narodenia, skupina, typ_hraca, url, kluby) VALUES ("$meno", "$priezvisko", "$rocnik", "$skupina", "$post", "$fileURL", "$timy");
EOF;
  	$ret = $db->query($sql);
  	$db->close();	
}

?>