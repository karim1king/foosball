<?php
            require_once "Match.php";
            require_once "Team.php";
            $turName = $_POST['turname'];
            $match_Table = $turName."_matches";
            $teamTable = $turName."_teams";
            $filter = $_POST["porcname"];
            require_once "mysqli_MY.php"; $mysqli= connectMysqli();
            $type = $_POST['type'];

            function getSelect($teamId,$matchId,$teamsArray)
            {
                $str="";
                $str .= "<select name='"."p".$matchId."[]"."'>";
                $str .=  "<option value='null'>".null."</option>";
                for($i=0;$i<count($teamsArray);$i++)
                {
                    $id = $teamsArray[$i]['id'];
                    $name = $teamsArray[$i]['teamName'];
                    if($id != $teamId)
                    {
                        $str .=  "<option value=".$id.">".$name."</option>";
                    }
                    else
                    {
                        $str .=  "<option selected value='".$id."'>".$name."</option>";
                    }
                }
                $str .= "</select>";
                return $str;
            }
            if($type == 1)
            {
                $matchCount = $mysqli->query("SELECT COUNT(*)  AS NumberOfOrders FROM $match_Table WHERE (round=1)");
                $matchCount = $matchCount->fetch_assoc();
                $matchCount = $matchCount['NumberOfOrders'];

                $result = $mysqli->query("SELECT * FROM $match_Table");
                echo "<p class='leg'>Раунд: 1</p>";
                echo "<table align='center'  border='1' style='width:100%'>";
                $var = 0;
                $count = 0;
                $roundC = 1;
                while($row = $result->fetch_assoc())
                {
                    $match = new Match($row['fteam'],$row['steam'],$row['fscore'],$row['sscore'],$row['round']);
                    $id = $row['id']."[]";
                    if($var < $matchCount)
                    {
                        $firstTeamName;
                        $secondTeamName;
                        if(Team::getTeamNameById($match->firstTeam,$turName,$mysqli,$filter,$firstTeamName) |  Team::getTeamNameById($match->secondTeam,$turName,$mysqli,$filter,$secondTeamName))
                            echo "<tr><td align='center'>$firstTeamName</td><td width='10px'><input type='text' name=$id value=$match->firstTeamScore></td><td width='10px'><input type='text' name=$id value=$match->secondTeamScore><td align='center'>$secondTeamName</td></tr>";
                        $var++;
                    }
                    else
                    {
                        echo "</table>";
                        $roundC++;;
                        echo "<p class='leg'>Раунд: $roundC</p>";
                        echo "<table align='center'  border='1' style='width:100%'>";
                        $firstTeamName;
                        $secondTeamName;
                        if(Team::getTeamNameById($match->firstTeam,$turName,$mysqli,$filter,$firstTeamName) |  Team::getTeamNameById($match->secondTeam,$turName,$mysqli,$filter,$secondTeamName))
                            echo "<tr><td align='center'>$firstTeamName</td><td width='10px'><input type='text' name=$id value=$match->firstTeamScore></td><td width='10px'><input type='text' name=$id value=$match->secondTeamScore><td align='center'>$secondTeamName</td></tr>";
                        $var=1;
                    }
                    echo"</tr>";
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
                while ($row = $teams->fetch_assoc())
                {
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
                            $fteamId = $row['fteam'];
                            $steamId = $row['steam'];
                            $id = $row['id']."[]";
                            $firstTeamName;
                            $secondTeamName;
                            $first = getSelect($fteamId,$matchId,$teamsArray);
                            $second = getSelect($steamId,$matchId,$teamsArray);
                            if(Team::getTeamNameById($match->firstTeam,$turName,$mysqli,$filter,$firstTeamName) |  Team::getTeamNameById($match->secondTeam,$turName,$mysqli,$filter,$secondTeamName))
                                echo "<tr><td align='center'>$first</td><td width='10px'><input type='text' name=$id value=$match->firstTeamScore></td><td width='10px'><input type='text' name=$id value=$match->secondTeamScore><td align='center'>$second</td></tr>";
                        }
                        echo "</table>";
                        $roundC++;;
                    }
                }
            }
            $mysqli->close();
