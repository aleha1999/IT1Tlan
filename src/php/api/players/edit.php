<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/players.php");

//Vars
$player = $_POST["player"];
$fname = $_POST["firstname"];
$lname = $_POST["surname"];
$gamertag = $_POST["tag"];
$nationality = $_POST["nationality"];
$address = $_POST["address"];
$postnumber = $_POST["postnumber"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$rating = $_POST["rating"];
//Sanitize
$player = filter_var($player,FILTER_SANITIZE_NUMBER_INT);
$fname = filter_var($fname,FILTER_SANITIZE_STRING);
$lname = filter_var($lname,FILTER_SANITIZE_STRING);
$gamertag = filter_var($gamertag,FILTER_SANITIZE_STRING);
$nationality = filter_var($nationality,FILTER_SANITIZE_STRING);
$address = filter_var($address,FILTER_SANITIZE_STRING);
$postnumber = filter_var($postnumber,FILTER_SANITIZE_NUMBER_INT);
$phone = filter_var($phone,FILTER_SANITIZE_NUMBER_INT);
$email = filter_var($email,FILTER_SANITIZE_EMAIL);
$rating = filter_var($rating,FILTER_SANITIZE_NUMBER_INT);

//Variable checking
$vars = array(&$fname,&$lname,&$gamertag,&$nationality,&$address,&$postnumber,&$phone,&$email,&$rating);
APIFrame::emptyToNull($vars);
$result = Players::update($player,$fname,$lname,$phone,$gamertag,$rating,$nationality,$email,$address,$postnumber);
APIFrame::finish($result);
?>
