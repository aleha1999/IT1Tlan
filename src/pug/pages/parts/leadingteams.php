<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/db.php");
$qb = new QueryBuilder();
$qb->query =
"SELECT
    Logo, Name, Points
FROM
    Teams
        INNER JOIN
    (SELECT
        Team, SUM(POINTS) AS Points
    FROM
        CompTeamParticipation
    GROUP BY Team) r ON Teams.TeamID = r.Team
ORDER BY Points DESC";
$qb->execute();
$res = $qb->getResults();
foreach($res as $team) {
    echo "<tr><td>";
    if($team["Logo"] != null)
        echo "<img src='/img/logos/{$team["Logo"]}' height='32'/>";
    echo "</td>";
    echo "<td>{$team["Name"]}</td><td>{$team["Points"]}</td>";
    echo "</tr>";
}
?>
