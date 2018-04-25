<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/games.php");
$res = Games::delete($_POST["game"]);
APIFrame::finish($res);
?>
