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
        return DB::get($rable,$rows,$where,$equals);
    }

    public static function delete($gameid) {
        $table = "Games";
        $where = "GameID";
        $equals = $gameid;
        return DB::delete($table,$where,$equals);
    }
}
?>
