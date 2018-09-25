<?php
include('../funkcie.php');

if (isset($_POST['submit'])){
    $pageUrl = $_POST['url'];
    $meno = $_POST['meno'];
    $priezvisko = $_POST['priezvisko'];
    $rocnik = $_POST['rocnik'];
    $post = $_POST['post'];
    $timy = $_POST['timy'];
    $skupina = $_POST['skupina'];
    $fileURL = "";

    $ifFile = false;
    if ( $_FILES['file']['error'] <= UPLOAD_ERR_OK ){
        $file = $_FILES['file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $isFile = true;
        echo $ext;
    }

    if($skupina == 'Seniori'){
        if(!$isFile){
            $fileURL = 'fotky/seniori/face.png';
        }
        else{
            $fileURL = 'fotky/seniori/'.replaceSpecialChars($meno).replaceSpecialChars($priezvisko).".".$ext;
            pridajFotkuNaServer($file, $fileURL);

        }
    }
    else{
        if(!$isFile){
            $fileURL = 'fotky/pripravka/face.png';
        }
        else{
            $fileURL = 'fotky/pripravka/'.replaceSpecialChars($meno).replaceSpecialChars($priezvisko).".".$ext;
            pridajFotkuNaServer($file, $fileURL);
        }  
    }





























































    pridajDoDatabazy($meno, $priezvisko, $rocnik, $post, $timy, $fileURL, $skupina);


    unset($_FILES['file']);
    header('Location: ' . $pageUrl);
}

function pridajFotkuNaServer($file, $targetFileURL){
    $destination = dirname(getcwd()) . "/".$targetFileURL;
    move_uploaded_file($file["tmp_name"], $destination);
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
