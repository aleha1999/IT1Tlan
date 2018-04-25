<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/teams.php");
$team = $_GET["team"];
$team = filter_var($team,FILTER_SANITIZE_NUMBER_INT);
$result = Teams::getPlayers($team);
APIFrame::finish(is_array($result),$result);
?>
