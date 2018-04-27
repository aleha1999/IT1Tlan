<?php
require_once("db.php");
class Players {
    public static function add($firstname,$surname,$phonenumber,$tag,$rating,$nationality,$email,$address,$postnumber) {
        return DB::add(
            "Players",
            array($firstname,$surname,$phonenumber,$tag,$rating,$nationality,$email,$address,$postnumber),
            array("Firstname","Surname","Phonenumber","Tag","Rating","Nationality","Email","Address","Postnr"));
    }

    public static function get($playerid = "all") {
        $row = "PlayerID";
        if($playerid == "all") {
            $playerid = null;
            $row = null;
        }
        return DB::get(
            "Players",
            array("PlayerID","Firstname","Surname","Phonenumber","Tag","Rating","Nationality","Email","Address","Postnr"),$row,$playerid);
    }

    public static function delete($player) {
        return DB::delete("Players","PlayerID",$player);
    }

    public static function update($player,$firstname,$surname,$phonenumber,$tag,$rating,$nationality,$email,$address,$postnumber) {
        return DB::update("Players",
        array("Firstname","Surname","Phonenumber","Tag","Rating","Nationality","Email","Address","Postnr"),
        array($firstname,$surname,$phonenumber,$tag,$rating,$nationality,$email,$address,$postnumber),
        "PlayerID",
        $player
    );
    }

    public static function getTeams($player) {
        $q = "SELECT l.Player,l.Team, Teams.Name FROM (SELECT * FROM PlayerTeamParticipation WHERE Player = ?) l INNER JOIN Teams ON l.Team = Teams.TeamID";
        $qb = new QueryBuilder();
        $qb->query = $q;
        $qb->vars = array($player);
        return $qb->execute()->getResults();
    }
}
?>
