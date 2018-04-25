<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/teams.php");
APIFrame::finish(Teams::delete($_POST["teamid"]));
?>
