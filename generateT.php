<?php
        function mySort($f1,$f2)
        {
            if($f1->rank < $f2->rank) return -1;
            elseif($f1->rank > $f2->rank) return 1;
            else return 0;
        }
        $checked = $_POST['players'];
        $tur = $_POST['turName'];
        $turDate = $_POST['turDate'];
        $turSate = $_POST['turState'];
        $turType = $_POST['turType'];
        $part = $_POST['play-off'];
        if(is_array($checked)&& $tur != '' && $turDate!='' &&  count($checked)!=0 && count($checked)%2==0)
        {
            require_once "Teams.php";
            require_once "Team.php";
            require_once "Player.php";
            require_once "Match.php";
            require_once "Rounds.php";
            require_once "mysqli_MY.php"; $mysqli= connectMysqli();
            $result = $mysqli->query("SELECT * FROM reg");
            $checkedPlayers = array();
            while($temp = $result->fetch_assoc())
            {
                if(in_array($temp['id'], $checked))
                {
                    $checkedPlayers[] = new Player($temp['name'],$temp['surname'],$temp['rank'],$temp['id']);
                }
            }

            $teams = new Teams();
            uasort($checkedPlayers,"mySort");
            $checkedPlayers = array_values($checkedPlayers);
            $id = 0;
            while(count($checkedPlayers) != 0)
            {
                $player1 = $checkedPlayers[0];
                $indexPlayer2 = count($checkedPlayers) - rand(1,ceil(count($checkedPlayers)* 0.2));
                $player2 = $checkedPlayers[$indexPlayer2];
                $teams->add(new Team($player1,$player2,$id));
                unset($checkedPlayers[$indexPlayer2]);
                unset($checkedPlayers[0]);
                $checkedPlayers = array_values($checkedPlayers);
                $id++;
            }

            $rounds = new Rounds();

            $numOfRound =  $_POST['numOfRound'];
            $count=0;
            while($count < $numOfRound)
            {
                $matches = Array();

                for($i=0; $i<$teams->length();$i++)
                {
                    for($j=$i+1; $j<$teams->length();$j++ )
                    {
                        $a = new Match($teams->teams[$i],$teams->teams[$j],null,null,$count+1);
                        $matches[] = $a;
                    }
                }
                $rounds->rounds[]= $matches;
                $count++;
            }

            $mysqli->query("INSERT INTO turtable (turname,turdate,state,type) values('$tur',STR_TO_DATE('$turDate', '%d/%m/%Y'),$turSate,$turType)");
            $tur = $mysqli->insert_id;
            $match_Table = $tur."_matches";
            $team_Table = $tur."_teams";
            $mysqli->query("CREATE TABLE $team_Table (id MEDIUMINT NOT NULL AUTO_INCREMENT,idfplayer INT, idsplayer INT,teamName VARCHAR(25),wincount INT,PRIMARY KEY (id),UNIQUE (teamName))");
            for($i=0;$i<$teams->length();$i++)
            {
                $team = $teams->teams[$i];
                $id1 =$team->firstPlayer->id;
                $id2 =$team->secondPlayer->id;
                $teamName = $team->teamName;
                $mysqli->query("INSERT INTO $team_Table(idfplayer,idsplayer,teamName,wincount) VALUES ('$id1','$id2','$teamName','$i')");
                $team->id = $mysqli->insert_id;
            }
            $mysqli->query("CREATE TABLE $match_Table(id MEDIUMINT NOT NULL AUTO_INCREMENT,fteam text, steam text, fscore INT ,sscore INT,round INT,PRIMARY KEY (id))");
            for($j=0;$j<count($rounds->rounds);$j++)
            {
                $matches = $rounds->rounds[$j];
                for($i=0;$i<count($matches);$i++)
                {
                    $match = $matches[$i];
                    $id1 =$match->firstTeam->id;
                    $id2 =$match->secondTeam->id;
                    $round = $match->round;
                    $mysqli->query("INSERT INTO $match_Table(fteam, steam, fscore,sscore,round) values($id1,$id2,null,null,$round)");
                }
            }
            if($turType == 2)
            {
                $playoff_Table = $tur."_play_off";
                $mysqli->query("CREATE TABLE $playoff_Table(id MEDIUMINT NOT NULL AUTO_INCREMENT,fteam text, steam text, fscore INT ,sscore INT,round text,PRIMARY KEY (id))");
                switch($part)
                {
                    case 1:
                    {
                        for($i=0;$i<8;$i++)
                        {
                            $mysqli->query("INSERT INTO $playoff_Table (round) VALUES ('1/8')");
                        }
                    }
                    case 2:
                    {
                        for($i=0;$i<4;$i++)
                        {
                            $mysqli->query("INSERT INTO $playoff_Table (round) VALUES ('1/4')");
                        }
                    }
                }
                for($i=0;$i<2;$i++)
                {
                    $mysqli->query("INSERT INTO $playoff_Table (round) VALUES ('1/2')");
                }

                $mysqli->query("INSERT INTO $playoff_Table (round) VALUES ('1')");
                $mysqli->query("INSERT INTO $playoff_Table (round) VALUES ('3')");
            }
            $mysqli->close();
            header("Location: success.php");
        }
        else
        {
            echo "error!";
        }
