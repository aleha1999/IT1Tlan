<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/teams.php");
$result = Teams::get();
APIFrame::finish(is_array($result),$result);
?>
