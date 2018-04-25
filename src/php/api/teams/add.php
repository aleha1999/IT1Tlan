<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/teams.php");
//Text variables
$teamname =  $_POST["name"];
$captain = $_POST["captain"];
$nationality = $_POST["nationality"];
//Sanitize text variables
$teamname = filter_var($teamname,FILTER_SANITIZE_STRING);
$captain = filter_var($captain,FILTER_SANITIZE_NUMBER_INT);
$nationality = filter_var($nationality,FILTER_SANITIZE_STRING);
//Get and process file
if(!empty($_FILES["logofile"]["type"])) {
    if($_FILES["logofile"]["type"] != "image/png")
        APIFrame::finish(false,"Invalid file type","error");
    $resource = imagecreatefrompng($_FILES["logofile"]["tmp_name"]);
    if($resource === false)
        APIFrame::finish(false,"Could not open file","error");
    $resource = imagescale($resource,128,128,IMG_BICUBIC_FIXED);
    if($resource === false)
        APIFrame::finish(false,"Could not resize file","error");
    $dest = "../../img/logos/".$_FILES["logofile"]["name"];
    //Prevent file duplicates
    if(file_exists($dest)) {
        $info = pathinfo($dest);
        $fc = count(glob($info["dirname"]."/".$info["filename"]."*"));
        $dest = $info["dirname"]."/".$info["filename"]."_".$fc.".".$info["extension"];
    }
    //The image resource is now ready to be stuck out into a file, though we wait
    //until we know that the team could be added.
}
//Attempt to add team
if(!isset($dest))
    $dDest = null;
else {
    $finfo = pathinfo($dest);
    $dDest = $finfo["basename"];
}
$result = Teams::add($teamname,$captain,$nationality,$dDest);
if($result == true) {
    if(isset($resource))
        imagepng($resource,$dest,0);
    APIFrame::finish(true);
}
APIFrame::finish(false);
?>
