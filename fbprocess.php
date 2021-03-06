<?php
session_start();
$reponse = array();
//set AccessToken in session
if (isset($_POST["accesstoken"])) {
    $_SESSION["accesstoken"] = $_POST["accesstoken"];
    return json_encode($reponse);
}

if (!$_SESSION["accesstoken"]) {
    $reponse["status"] = 0;
    echo json_encode($reponse);
}
/**
 *To remove non empty Directory
 * @param type $dir : Directory Name
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object); else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
/**
 *Download File Form URL 
 * @param type $url : File Url to Download 
 * @param type $dir : Directory Path to store
 */
function getfile($url, $dir) {
    file_put_contents($dir . substr($url, strrpos($url, '/'), strlen($url)), file_get_contents($url));
}

/* creates a compressed zip file */
/**
 *
 * @param type $dir : Dir name to zip it
 * @param type $zip_file  : Zip file name to save
 * @return boolean|\ZipArchive 
 */
function createZipFromDir($dir, $zip_file) {
    $zip = new ZipArchive;
    if (true !== $zip->open($zip_file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
        return false;
    }
    zipDir($dir, $zip);
    return $zip;
}

function zipDir($dir, $zip, $relative_path = DIRECTORY_SEPARATOR) {
    $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if (file === '.' || $file === '..') {
                continue;
            }
            if (is_file($dir . $file)) {
                $zip->addFile($dir . $file, $file);
            } elseif (is_dir($dir . $file)) {
                zipDir($dir . $file, $zip, $relative_path . $file);
            }
        }
    }
    closedir($handle);
}
/**
 *
 * @param type $files :  URL of files to zip
 * @param type $destination : destination path to store that zip
 * @param type $overwrite  : Booleand flag to overwrite file or not
 */
function create_zip($files = array(), $destination = '', $overwrite = false) {
    //if the zip file already exists and overwrite is false, return false
    $albumid = $_GET["albumid"];
    if (file_exists($albumid)) {
        rrmdir($albumid);
    }
    mkdir($albumid);
    //if files were passed in...
    if (is_array($files)) {
        //cycle through each file
        foreach ($files as $file) {
            //make sure the file exists
            getfile($file, $albumid);
        }
    }
    createZipFromDir($albumid, $albumid . ".zip");
    rrmdir($albumid);
}
// Initialize facebook sdk
require_once("vendors/facebook.php");
require_once("fbAppData.php");
$config = array();
$config['appId'] = $fbAppId;
$config['secret'] = $fbAppSecret;
$config['fileUpload'] = false; // optional
$facebook = new Facebook($config);
$facebook->setAccessToken($_SESSION["accesstoken"]);
if ($facebook->getUser() == 0) {
    $reponse["status"] = 0;
    echo json_encode($reponse);
}
//Fetch User albums Photo
$albumPhotos = $facebook->api('/' . $_GET["albumid"] . '/photos', 'GET');
$files_to_zip = array();
foreach ($albumPhotos["data"] as $photos) {
    $files_to_zip[] = $photos["source"];
}

//if true, good; if false, zip creation failed
create_zip($files_to_zip);
// set example variables
$file = $albumid . ".zip";
$reponse["status"] = 1;
echo json_encode($reponse);
// http headers for zip downloads
//Set Headers:
/*
  header('Pragma: public');
  header('Expires: 0');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file)) . ' GMT');
  header('Content-Type: application/force-download');
  header('Content-Disposition: inline; filename="$file"');
  header('Content-Transfer-Encoding: binary');
  header('Content-Length: ' . filesize($file));
  header('Connection: close');
  readfile($file);
  exit(); */
?>