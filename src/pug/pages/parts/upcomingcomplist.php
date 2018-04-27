<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/db.php");
$qb = new QueryBuilder();
$qb->query = "SELECT Title, Date FROM Competitions WHERE date > ?";
$qb->vars = array(time());
$qb->execute();
$res = $qb->getResults();
foreach ($res as $value) {
    $time = date("D/m/Y H:j",$value["Date"]);
    echo "<tr><td>{$value["Title"]}</td><td>{$time}</td></tr>";
}
?>
