<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/teams.php");
$player = $_POST["player"];
$team = $_POST["team"];
$player = filter_var($player,FILTER_SANITIZE_NUMBER_INT);
$team = filter_var($team,FILTER_SANITIZE_NUMBER_INT);
APIFrame::finish(Teams::removeplayer($player,$team));
?>
