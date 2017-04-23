<?php
$name = $_POST['name'];
$surname = $_POST['surname'];
$id = $_POST['id'];


require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$mysqli->query("UPDATE reg SET name='$name',surname='$surname' WHERE id='$id'");

if(isset($_POST["currentTeam"]) && isset($_POST["teamTable"]))
{
    $team = $_POST['currentTeam'];
    $teamTable = $_POST['teamTable'];
    $teamName =$_POST['teamName'];
    $mysqli->query("UPDATE $teamTable SET teamName='$teamName' WHERE id='$team'");

}
$mysqli->close();
header("location:playerdet.php?id=$id");