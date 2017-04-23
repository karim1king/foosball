<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script type="text/javascript" src='script.js'></script>
    <style>
        td
        {
            width: 200px;
            height: 30px;
        }
        .head
        {
            background: #b6b0b5;
            text-align: center;
            border: 1px solid #000000;
        }
        .team
        {
            text-align: center;
            border: 2px solid black;
            background: #96ea50;
        }
        .score
        {
            text-align: center;
            align='right';
            width: 20px;
            border-left: 1px solid #000000;
        }
        .scoreR
        {
            text-align: center;
            align='right';
            width: 20px;
            border-right: 1px solid #000000;
            border-left: 1px solid #000000;
        }
        .center
        {
            margin-left: auto;
            margin-right: auto;
            width: 920px;
            overflow: auto;
        }
        div.clean
        {
            height: 50px;
        }
    </style>
    <?php
    require_once "Player.php";
    require_once "TeamInTable.php";
    $matchTable = $_GET['turname'] . "_play_off";
    $teamTable = $_GET['turname'] . "_teams";

    require_once "mysqli_MY.php";
    $mysqli = connectMysqli();
    $turName = $_GET['turname'];
    $result = $mysqli->query("SELECT * FROM turtable WHERE id=$turName");
    $tur = $result->fetch_assoc();
    ?>
</head>
<body>
<div id="main">
<?php
    include 'menu.php';
?>
    <div class="title">
        <p>Сетка турнира: <strong style="color: #3f3f3f"><?php echo $tur['turname']?></strong></p>
    </div>
    <hr/>
    <div>
        <!--<p class="legfilter">Турнирная таблица:  <?php echo $team['teamName'] ?></p>-->
        <div class="block">
            <div class="center">
            <?php
            include_once "Team.php";
                $array = Array("1/8","1/4","1/2","1");
                $j = 0;
                $numOfRow=1;
                while($j < count($array))
                {
                    $teamResult = $mysqli->query("SELECT * FROM $matchTable WHERE round='$array[$j]'");
                    $countResult = $teamResult->num_rows;
                    if($countResult !=0)
                    {
                        $title =$array[$j];
                        if($title == '1')
                        {
                            $title="Финал";
                        }
                        $c=0;
                        echo"<table style='float: left;' CELLSPACING=0>";
                        echo "<tr ><td class='head' colspan='2'>$title</td></tr>";
                        for($i=0;$i<$numOfRow;$i++)
                        {
                            echo"<tr><td colspan='2'></td></tr>";
                        }
                        while($row = $teamResult->fetch_assoc())
                        {
                            $firstTeam = 0;
                            $secondTeam = 0;
                            Team::getTeamNameById($row['fteam'],$_GET['turname'],$mysqli,null,$firstTeam);
                            Team::getTeamNameById($row['steam'],$_GET['turname'],$mysqli,null,$secondTeam);
                            $fscore = $row['fscore'];
                            $sscore = $row['sscore'];
                            echo"<tr class='team'><td>$firstTeam</td><td class='score'>$fscore</td></tr>";
                            for($f=0;$f<$numOfRow*2-1;$f++)
                            {
                                echo"<tr><td colspan='2' style='border-right: 2px solid black'></td></tr>";
                            }
                            echo"<tr class='team'><td>$secondTeam</td><td class='score'>$sscore</td></tr>";
                            if($c < $countResult-1)
                            {
                                for($l=0;$l<$numOfRow*2-1;$l++)
                                {
                                    echo"<tr ><td colspan='2' ></td></tr>";
                                }
                            }
                            $c++;
                        }
                        echo"</table>";
                    }
                    $numOfRow *= 2;
                    $j++;
                }
            ?>

            </div>
            <div class="clean"></div>
        <?php
            $teamResult = $mysqli->query("SELECT * FROM $matchTable WHERE round='3'");
            $row = $teamResult->fetch_assoc();
            $firstTeam = 0;
            $secondTeam = 0;
            Team::getTeamNameById($row['fteam'],$_GET['turname'],$mysqli,null,$firstTeam);
            Team::getTeamNameById($row['steam'],$_GET['turname'],$mysqli,null,$secondTeam);
            $fscore = $row['fscore'];
            $sscore = $row['sscore'];
            echo"<table align='center' CELLSPACING=0>";
            echo "<tr><td></td><td class='head' colspan='3'>За 3 место</td><td></td></tr>";
            echo "<tr class='team'><td>$firstTeam</td><td class='score'>$fscore</td><td class='score'><td class='scoreR'>$sscore</td><td >$secondTeam</td></tr>";
            echo"</table>";
            $mysqli->close();
        ?>
            <div class="clean"></div>
        </div>

    </div>

</div>
</body>
</html>
