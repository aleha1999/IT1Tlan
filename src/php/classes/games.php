<?php
require_once("db.php");

class Games {
    public static function add($name) {
        $rows = array("Name");
        $values = array($name);
        $table = "Games";
        return DB::insert($table,$rows,$values);
    }

    public static function get($gameid = "all") {
        $table = "Games";
        $rows = array("GameID","Name");
        $where = "GameID";
        $equals = $gameid;
        if($gameid == "all") {
            $where = null;
            $equals = null;
        }
        return DB::get($table,$rows,$where,$equals);
    }

    public static function update($gameid,$name) {
        return DB::update("Games",array("Name"),array($name),"GameID",$gameid);
    }

    public static function delete($gameid) {
        $table = "Games";
        $where = "GameID";
        $equals = $gameid;
        return DB::delete($table,$where,$equals);
    }
}
?>
