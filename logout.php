<?php
    session_start();
    session_destroy();
    header("Location: https://test.thehallclan.net/admin.php");

?>