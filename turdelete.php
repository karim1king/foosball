<?php
    $tur = $_GET['turname'];
    $match_Table = $tur."_matches";
    $team_Table = $tur."_teams";
    $playoff_Table = $tur."_play_off";
    $voteTable = $tur."_vote";
    require_once "mysqli_MY.php"; $mysqli= connectMysqli();
    $mysqli->query("Delete FROM turtable WHERE id=$tur");
    $mysqli->query("DROP TABLE $match_Table");
    $mysqli->query("DROP TABLE $team_Table");
    $mysqli->query("DROP TABLE $playoff_Table");
    $mysqli->query("DROP TABLE $voteTable");
    $mysqli->close();
    header("location: turlistedit.php");