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
}
?>
