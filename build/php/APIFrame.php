<?php
class APIFrame {
    public static function finish($success,$data = array(),$key = "data") {
        header("Content-Type: application/json");
        die(json_encode(array("success"=>$success,$key=>$data)));
    }

    public static function emptyToNull($vars) {
        foreach($vars as &$var) {
            if(empty($var))
                $var = null;
        }
    }
}
?>
