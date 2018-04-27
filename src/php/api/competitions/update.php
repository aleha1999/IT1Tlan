<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/competitions.php");
$compid = $_POST["compid"];
$title = $_POST["title"];
$game = $_POST["game"];
$date = $_POST["datetime"];
//Sanitize
$title = filter_var($title,FILTER_SANITIZE_STRING);
$game = filter_var($game,FILTER_SANITIZE_NUMBER_INT);
$date = filter_var($date,FILTER_SANITIZE_NUMBER_INT);
//
$result = Competitions::update($compid,$title,$game,$date);
APIFrame::finish($result);
?>
