<?php
include('../funkcie.php');

$pageUrl = $_POST['url'];
$meno = $_POST['meno'];
$priezvisko = $_POST['priezvisko'];
$rocnik = $_POST['rocnik'];
$post = $_POST['post'];
$timy = $_POST['timy'];
$skupinaId = $_POST['skupinaId'];
$skupinaKod = $_POST['skupinaKod'];
$fileURL = "";

$isFile = false;
if ($_FILES['file']['error'] <= UPLOAD_ERR_OK) {
    $file = $_FILES['file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $isFile = true;
}

if (!$isFile) {
    $fileURL = 'fotky/hraci/face.png';
} else {
    $fileURL = 'fotky/hraci/' . replaceSpecialChars($meno) . replaceSpecialChars($priezvisko) . replaceSpecialChars($skupinaKod) . "." . $ext;
    pridajFotkuNaServer($file, $fileURL);
}

pridajDoDatabazy($meno, $priezvisko, $rocnik, $post, $timy, $fileURL, $skupinaId);

unset($_FILES['file']);
unset($_POST);
echo true;

function pridajFotkuNaServer($file, $targetFileURL)
{
    $destination = dirname(getcwd()) . "/" . $targetFileURL;
    move_uploaded_file($file["tmp_name"], $destination);
    compressImage($destination);
}

function pridajDoDatabazy($meno, $priezvisko, $rocnik, $post, $timy, $fileURL, $skupinaId)
{
    $db = napoj_db();

    $sql = <<<EOF
		INSERT INTO Hraci 
		(meno, priezvisko, rok_narodenia, typ_hraca, url, kluby, id_skupiny) 
		VALUES 
		("$meno", "$priezvisko", "$rocnik", "$post", "$fileURL", "$timy", "$skupinaId");
EOF;
    $db->exec($sql);

    $db->close();
}

?>
