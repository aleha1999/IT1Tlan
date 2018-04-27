<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/db.php");
$qb = new QueryBuilder();
$qb->query =
"SELECT
    Team, Competition, Title, Date
FROM
    (SELECT
        Team, Competition, Competitions.Date, Competitions.Title
    FROM
        (SELECT
        CompTeamParticipation.Team,
            CompTeamParticipation.Competition
    FROM
        CompTeamParticipation
    WHERE
        CompTeamParticipation.Team = ?) a
    INNER JOIN Competitions ON Competition = Competitions.CompID) b
WHERE
    Date > ?";
$qb->vars = array($_GET["team"],time());
$qb->execute();
$data = $qb->getResults();
foreach($data as $d) {
    $time = date("d/m/Y H:j",$d["Date"]+(60*60*2));
    echo "<tr><td>".$d["Title"]."</td><td>".$time."</td></tr>";
}
?>
