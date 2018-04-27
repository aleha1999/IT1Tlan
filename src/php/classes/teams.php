<?php
require_once("db.php");
class Teams {
    public static function get() {
        $q = new QueryBuilder();
        $q->query = "
        SELECT TeamID, Captain, Name, Nationality, Logo, rating FROM Teams LEFT JOIN (
            SELECT
                Team, ROUND(AVG(Rating),1) as rating
            FROM
                PlayerTeamParticipation,
                Players
            WHERE
                Players.PlayerID = PlayerTeamParticipation.Player
            GROUP BY
            	Team) b
        ON Teams.TeamID = b.Team";
        $q->execute();
        return $q->getResults();
    }

    public static function add($name,$captain,$origin,$logo) {
        $q = new QueryBuilder();
        $q->insert(array('Name','Captain','Nationality','Logo'))->into('Teams')->values(array($name,$captain,$origin,$logo));
        return $q->execute()->success;
    }

    public static function edit($team,$name,$captain,$origin,$logo) {
        $rows = array("Name","Captain","Nationality");
        $values = array($name,$captain,$origin);
        if($logo != null) {
            array_push($rows,"Logo");
            array_push($values,$logo);
        }
        return DB::update("Teams",$rows,$values,"TeamID",$team);
    }

    public static function delete($teamid) {
        return DB::delete("Teams","TeamID",$teamid);
    }

    public static function addplayer($playerid,$teamid) {
        $table = "PlayerTeamParticipation";
        $rows = array("Player","Team");
        $values = array($playerid,$teamid);
        return DB::insert($table,$rows,$values);
    }

    public static function removeplayer($playerid,$teamid) {
        $q = new QueryBuilder();
        $q->delete()->from("PlayerTeamParticipation")->where("Player")->equals($playerid)->nd("Team")->equals($teamid);
        $q->execute();
        return $q->success;
    }

    public static function getPlayers($teamid) {
        $q = new QueryBuilder();
        $q->select(array("Player","Team"))->from("PlayerTeamParticipation")->where("Team")->equals($teamid);
        $q->execute();
        if(!$q->success)
            return false;
        return $q->getResults();
    }
}
?>
