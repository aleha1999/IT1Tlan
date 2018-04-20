<?php
require_once("db.php");

class Countries {
    public static function get($countryID = "all") {
        $table = "Countries";
        $where = "ID";
        $equals = $countryID;
        if($countryID == "all") {
            $where = null;
            $equals = null;
        }
        return DB::get($table,array('ID','Name'),$where,$equals);
    }

    public static function getAllInOrder() {
        $q = new QueryBuilder();
        $q->query = "SELECT * FROM Countries WHERE 1 ORDER BY Name";
        if(!$q->execute()->success)
            return false;
        return $q->getResults();
    }
}
?>
