<?php
include('../funkcie.php');

if (isset($_POST['submit'])){
    $pageUrl = $_POST['url'];
    $id = $_POST['id'];
    $skupina = $_POST['skupina'];
    $meno = $_POST['meno'];
    $priezvisko = $_POST['priezvisko'];

    if ( $_FILES['file']['error'] <= UPLOAD_ERR_OK ){
        $file = $_FILES['file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    }
    else{
        header('Location: ' . $pageUrl);
    }

    $url = ziskajUrlFotktHraca($id);
    if ($url != "fotky/seniori/face.png" && $url != "fotky/pripravka/face.png"){
        vymazFotkuZoServera($url);
    }

    if($skupina == 'Seniori'){
        $newUrl = 'fotky/seniori/'.replaceSpecialChars($meno).replaceSpecialChars($priezvisko).".".$ext;
    }
    else{
        $newUrl = 'fotky/pripravka/'.replaceSpecialChars($meno).replaceSpecialChars($priezvisko).".".$ext;
    }

    pridajFotkuNaServer($file, $newUrl);
    updateUrlVDb($id, $newUrl);

    unset($_FILES['file']);
    header('Location: ' . $pageUrl);
}

function pridajFotkuNaServer($file, $targetFileURL){
    $destination = dirname(getcwd()) . "/".$targetFileURL;
    move_uploaded_file($file["tmp_name"], $destination);
}

function vymazFotkuZoServera($url){
    $pathToPhoto = dirname(getcwd()) . "/".$url;
    unlink($pathToPhoto);
}

function ziskajUrlFotktHraca($id){
    $db = napoj_db();
    $sql =<<<EOF
    SELECT url FROM Hraci WHERE id="$id";
EOF;
    $ret = $db->query($sql);
    $row = $ret->fetchArray(SQLITE3_ASSOC);
    $db->close();   
    return $row['url'];
}

function updateUrlVDb($id, $url){
    $db = napoj_db();
    $sql =<<<EOF
        UPDATE Hraci SET url="$url" WHERE id="$id";
EOF;
    $ret = $db->query($sql);
    $db->close();   
}
?>