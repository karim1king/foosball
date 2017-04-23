<?php
require_once "Match.php";
require_once "Team.php";
$turName = $_POST['turname'];
$match_Table = $turName . "_matches";
$teamTable = $turName . "_teams";
$filter = $_POST["porcname"];
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$type = $_POST['type'];

if($type == 1) {
    $matchCount = $mysqli->query("SELECT COUNT(*)  AS NumberOfOrders FROM $match_Table WHERE (round=1)");
    $matchCount = $matchCount->fetch_assoc();
    $matchCount = $matchCount['NumberOfOrders'];

    $result = $mysqli->query("SELECT * FROM $match_Table");

    echo "<p class='leg'>Раунд: 1</p>";
    echo "<table align='center'  border='1' style='width:100%'>";
    $var = 0;
    $count = 0;
    $roundC = 1;
    while ($row = $result->fetch_assoc()) {
        $match = new Match($row['fteam'], $row['steam'], $row['fscore'], $row['sscore'], $row['round']);
        if ($var < $matchCount) {
            $firstTeamName = 0;
            $secondTeamName = 0;
            if (Team::getTeamNameById($match->firstTeam, $turName, $mysqli, $filter, $firstTeamName) | Team::getTeamNameById($match->secondTeam, $turName, $mysqli, $filter, $secondTeamName))
                echo "<tr><td align='center'>$firstTeamName</td><td width='10px'>$match->firstTeamScore</td><td width='10px'>$match->secondTeamScore</td><td align='center'>$secondTeamName</td></tr>";
            $var++;
        } else {
            echo "</table>";
            $roundC++;;
            echo "<p class='leg'>Раунд: $roundC</p>";
            echo "<table align='center'  border='1' style='width:100%'>";
            $firstTeamName = 0;
            $secondTeamName = 0;
            if (Team::getTeamNameById($match->firstTeam, $turName, $mysqli, $filter, $firstTeamName) | Team::getTeamNameById($match->secondTeam, $turName, $mysqli, $filter, $secondTeamName))
                echo "<tr><td align='center'>$firstTeamName</td><td width='10px'>$match->firstTeamScore</td><td width='10px'>$match->secondTeamScore</td><td align='center'>$secondTeamName</td></tr>";
            $var = 1;
        }
        echo "</tr>";
        $count++;
    }
    echo "</table>";
}
else
{
    $roundC = 0;
    $match_Table = $turName."_play_off";
    $teams = $mysqli->query("SELECT * FROM $teamTable");
    $teamsArray = array();
    while ($row = $teams->fetch_assoc()) {
        $teamsArray[] = $row;
    }
    $array = Array("1/8","1/4","1/2","1","3");
    for($i=0;$i<5;$i++)
    {
        $round = $array[$i];
        $result = $mysqli->query("SELECT * FROM $match_Table WHERE round='$round'");
        if($result->num_rows != 0)
        {
            if($i > 2)
            {
                if($round=="3")
                    echo "<p class='leg'>За 3 место</p>";
                else
                    echo "<p class='leg'>финал</p>";
            }
            else
            {
                echo "<p class='leg'>$round</p>";
            }
            echo "<table align='center'  border='1' style='width:100%'>";
            while($row = $result->fetch_assoc())
            {
                $match = new Match($row['fteam'],$row['steam'],$row['fscore'],$row['sscore'],$row['round']);
                $matchId= $row['id'];
                $id = $row['id']."[]";
                $firstTeamName;
                $secondTeamName;
                if (Team::getTeamNameById($match->firstTeam, $turName, $mysqli, $filter, $firstTeamName) | Team::getTeamNameById($match->secondTeam, $turName, $mysqli, $filter, $secondTeamName))
                    echo "<tr><td align='center'>$firstTeamName</td><td width='10px'>$match->firstTeamScore</td><td width='10px'>$match->secondTeamScore</td><td align='center'>$secondTeamName</td></tr>";
            }
            echo "</table>";
            $roundC++;;
        }
    }
}
$mysqli->close();


