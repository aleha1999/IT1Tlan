<?php
require $_SERVER["DOCUMENT_ROOT"]."/php/players.php";
require $_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php";
$fname = $_POST["firstname"];
$lname = $_POST["lastname"];
$gamertag = $_POST["gamertag"];
$nationality = $_POST["nationality"];
$address = $_POST["address"];
$postnumber = $_POST["postnumber"];
$phone = $_POST["phone"];
$
$countries = Players::add();
APIFrame::finish(is_array($countries),$countries);
?>
