<?php
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
    $turId = $_POST['turname'];
    $state = $_POST['turState'];
    $vote = $_GET['turVote'];
    $mysqli->query("UPDATE turtable SET state ='$state' WHERE id='$turId'");
    $result = $mysqli->query("SELECT vote FROM turtable WHERE id='$turId' LIMIT 1");
    while($row = $result->fetch_assoc())
    {
        if($vote != $row['vote'])
        {
            $mysqli->query("UPDATE turtable SET vote ='$vote' WHERE id='$turId'");
            $voteTable = $turId."_vote";
            if($vote == 1)
            {
                $mysqli->query(" CREATE TABLE $voteTable (player INT PRIMARY KEY)");
                $mysqli->query("UPDATE reg SET rank = 0");
            }   
            else
            {
                $mysqli->query(" DROP TABLE $voteTable");
            }
        }
    }
    $type = $_GET['turType'];
    $matchTable='';
    if($type==1)
    {
        $matchTable = $_POST['turname'] . "_matches";

    }
    else
    {
        $matchTable = $_POST['turname'] . "_play_off";
    }

    foreach ($_POST as $key => $value)
    {
        if ($key != "turname")
        {
            if (is_array($value))
            {
                if($key[0]=='p')
                {
                    $id = substr($key,1);
                    $fteam = $value[0];
                    $steam = $value[1];
                    $mysqli->query("UPDATE $matchTable  SET fteam = $fteam,steam = $steam WHERE id=$id");
                }
                else
                {
                    $fscore = $value[0];
                    $sscore = $value[1];
                    $mysqli->query("UPDATE $matchTable  SET fscore = $fscore,sscore = $sscore WHERE id=$key");
                }
            }
        }
    }
$mysqli->close();
    header("location:turlistedit.php");
