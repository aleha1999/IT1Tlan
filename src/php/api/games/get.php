<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/games.php");
$res = Games::get();
APIFrame::finish(is_array($res),$res);
?>
