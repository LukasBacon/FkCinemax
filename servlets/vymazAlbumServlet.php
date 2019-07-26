<?php
include('../db.php');

function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

function runServlet()
{
	$id = $_POST['id'];
	$db = napoj_db();

	#ziskanie nazvu priecinka
	$sql =<<<EOF
	SELECT nazov_priecinku FROM Albumy WHERE id = "$id";
EOF;
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);
	$nazovPriecinku = $row['nazov_priecinku'];
	if ($nazovPriecinku == null){
		return;
	}

	# vymazanie fotiek albumu z databazy
	$sql =<<<EOF
	DELETE FROM Fotky WHERE id_album = "$id";
EOF;
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);

	# vymazanie albumu z databazy
	$sql =<<<EOF
	DELETE FROM Albumy WHERE id = "$id";
EOF;
	$ret = $db->query($sql);
	$row = $ret->fetchArray(SQLITE3_ASSOC);

	$db->close();	

	# vymazanie priecinka
	$dirPath = dirname(getcwd()) . "/fotky/".$nazovPriecinku;
	delete_directory($dirPath);
}

runServlet();

?>