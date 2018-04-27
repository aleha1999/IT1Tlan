<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/competitions.php");
$comp = $_POST["comp"];
$comp = filter_var($comp,FILTER_SANITIZE_NUMBER_INT);
$team = $_POST["team"];
$team = filter_var($team,FILTER_SANITIZE_NUMBER_INT);
$points = Competitions::getPointsInComp($team,$comp)[0];
APIFrame::finish(Competitions::updatePoints($team,$comp,$points['Points']-1));
?>
