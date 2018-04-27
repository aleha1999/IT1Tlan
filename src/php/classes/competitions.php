<?php
require("db.php");

class Competitions {
    public static function create($title,$GameID,$date) {
        $rows = array("Title","Game","Date");
        $table = "Competitions";
        $values = array($title,$GameID,$date);
        return DB::insert($table,$rows,$values);
    }

    public static function update($compid,$title,$GameID,$date) {
        $rows = array("Title","Game","Date");
        $table = "Competitions";
        $values = array($title,$GameID,$date);
        return DB::update($table,$rows,$values,"CompID",$compid);
    }

    public static function get($matchid = "all") {
        $q = "SELECT
        Competitions.CompID,
        Competitions.Title,
        Competitions.Date,
        Competitions.Game,
        Games.Name AS GameName FROM Competitions,Games WHERE Competitions.Game = Games.GameID";
        $qb = new QueryBuilder();
        $qb->query = $q;
        $qb->execute();
        return $qb->getResults();
    }

    public static function delete($compid) {
        return DB::delete("Competitions","CompID",$compid);
    }

    public static function addParticipatingTeam($comp,$team) {
        $rows = array("Competition","Team","Points");
        $table = "CompTeamParticipation";
        $values = array($comp,$team,0);
        return DB::insert($table,$rows,$values);
    }

    public static function removeParticipatingTeam($comp,$team) {
        $qb = new QueryBuilder();
        $qb->delete(array("CompID","TeamID"))->from("CompTeamParticipation")->where("TeamID")->equals($team)->nd("CompID")->equals($comp);
        return $qb->execute()->success;
    }

    public static function getParticipatingTeams($compid) {
        $qb = new QueryBuilder();
        $qb->query = "SELECT Teams.Name,CompTeamParticipation.Team,CompTeamParticipation.Competition,CompTeamParticipation.Points FROM Teams,CompTeamParticipation WHERE Teams.TeamID = CompTeamParticipation.Team AND CompTeamParticipation.Competition = ? ORDER BY CompTeamParticipation.Points DESC";
        $qb->vars = array($compid);
        return $qb->execute()->getResults();
    }

    public static function getPointsInComp($team,$comp) {
        $qb = new QueryBuilder();
        $qb->query = "SELECT Points FROM CompTeamParticipation WHERE Team = ? AND Competition = ?";
        $qb->vars = array($team,$comp);
        $qb->execute();
        return $qb->getResults();
    }

    public static function updatePoints($team,$comp,$points) {
        $qb = new QueryBuilder();
        $qb->update("CompTeamParticipation")->set(array("Points"),array($points))->where("Team")->equals($team)->nd("Competition")->equals($comp);
        return $qb->execute()->success;
    }
}
?>
