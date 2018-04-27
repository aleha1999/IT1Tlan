<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/players.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
$result = Players::getTeams($_GET["player"]);
APIFrame::finish(is_array($result),$result);
?>
