<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/games.php");
$name = $_POST["name"];
$name = filter_var($name,FILTER_SANITIZE_STRING);
$gameid = $_POST["game"];
APIFrame::finish(Games::update($gameid,$name));
?>
