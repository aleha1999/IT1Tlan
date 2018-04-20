<?php
require("db.php");

class Competitions {
    function create($title,$GameID,$date) {
        $rows = array("Title","Game","Date");
        $table = "Competitions";
        $values = array($title,$GameID,$date);
        return DB::insert($table,$rows,$values);
    }

    function get($matchid = "all") {
        $rows = array("CompID","Title","Game","Date");
        $table = "Competitions";
        $where = "CompID";
        $equals = $matchid;
        if($matchid == "all") {
            $where = null;
            $equals = null;
        }
        return DB::get($table,$rows,$where,$equals);
    }

    function addParticipatingTeam($comp,$team) {
        $rows = array("Competition","Team");
        $table = "CompTeamParticipation";
        $values = array($comp,$team);
        return DB::insert($table,$rows,$values);
    }

    function getParticipatingTeams($compid) {
        $rows = array("TeamID");
        $table="CompTeamParticipation";
        $where = "CompID";
        $equals = $compid;
        return DB::get($table,$rows,$where,$equals);
    }
}
?>
