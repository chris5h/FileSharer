<?php
class Db {
    public static function conn() {
        $conn =  mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if($conn === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }   else    {
            return $conn;
        }
    }
}

