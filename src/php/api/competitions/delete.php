<?php
require($_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php");
require($_SERVER["DOCUMENT_ROOT"]."/php/competitions.php");
$compid = $_POST["compid"];
$compid = filter_var($compid,FILTER_SANITIZE_NUMBER_INT);
APIFrame::finish(Competitions::delete($compid));
?>
