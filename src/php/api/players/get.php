<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/players.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
$result = Players::get();
APIFrame::finish(is_array($result),$result);
?>
