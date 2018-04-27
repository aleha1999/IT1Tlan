<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/competitions.php");
$comp = $_GET["comp"];
$results = Competitions::getParticipatingTeams($comp);
APIFrame::finish(is_array($results),$results);
?>
