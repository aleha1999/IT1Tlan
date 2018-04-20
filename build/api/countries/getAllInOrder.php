<?php
require $_SERVER["DOCUMENT_ROOT"]."/php/countries.php";
require $_SERVER["DOCUMENT_ROOT"]."/php/APIFrame.php";
$countries = Countries::getAllInOrder();
APIFrame::finish(is_array($countries),$countries);
?>
