<?php
require("php/db.php");
$q = new QueryBuilder();
$q->select(array("Postnumber","Placename","Council"))->from("Postnumbers")->order_by("Placename")->desc();
$q->build();
echo $q->query;
$q->execute();
print_r($q->getResults());
?>
