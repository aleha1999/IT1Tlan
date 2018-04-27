<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/competitions.php");
$results = Competitions::get();
APIFrame::finish(is_array($results),$results);
?>
