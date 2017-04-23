<?php
    $turName= $_POST['turname'];
    require_once "mysqli_MY.php"; $mysqli= connectMysqli();
    $result = $mysqli->query("SELECT * FROM turtable WHERE turname='$turName'");
    if($result->num_rows == 0)
    {
        echo "false";
    }
    else
    {
        echo "true";
    }
    $mysqli->close();