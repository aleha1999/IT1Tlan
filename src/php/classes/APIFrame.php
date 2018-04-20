<?php
class APIFrame {
    public static function finish($success,$data = array(),$key = "data") {
        header("Content-Type: application/json");
        die(json_encode(array("success"=>$success,$key=>$data)));
    }
}
?>
