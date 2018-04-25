<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/players.php");
$player = $_GET["player"];
$player = filter_var($player,FILTER_SANITIZE_NUMBER_INT);
$success = Players::delete($player);
APIFrame::finish($success);
?>
