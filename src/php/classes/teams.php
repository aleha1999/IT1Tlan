<?php
require_once("db.php");
class Teams {
    public static function get($teamid = "all") {
        $q = new QueryBuilder();
        $q->select(array('TeamID','Captain','Name','Origin'))->from("Teams");
        if($teamid != "all") {
            $q->where('TeamID')->equals($teamid);
        }
        $q->execute();
        return $q->getResults();
    }

    public static function add($name,$captain,$origin,$logo) {
        $q = new QueryBuilder();
        $q->insert(array('Name','Captain','Origin','Logo'))->into('Teams')->values(array($name,$captain,$origin,$logo));
        return $q->execute()->success;
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
        $q->deletete()->from("PlayerTeamParticipation")->where("Player")->equals($playerid)->and("Team")->equals($teamid);
        $q->execute();
        return $q->success;
    }
}
?>
