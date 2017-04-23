<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <?php
        require_once "Player.php";
        $matchTable = $_GET['turname']."_matches";
        $teamTable = $_GET['turname']."_teams";
         $teamId = $_GET['id'];
        require_once "mysqli_MY.php"; $mysqli= connectMysqli();
        $result = $mysqli->query("SELECT * FROM $teamTable WHERE id=$teamId");
        $team = $result->fetch_assoc();
        $firstPlayer = Player::getPlayerNameById($team['idfplayer'],$mysqli);
        $secondPlayer = Player::getPlayerNameById($team['idsplayer'],$mysqli);
        $result = $mysqli->query("SELECT * FROM $matchTable WHERE fteam=$teamId OR steam=$teamId");
        $win=0;
        $lost=0;
        $scored=0;
        $missed=0;
        $played=0;

        while($row = $result->fetch_assoc())
        {
            if($row['fteam']==$teamId)
            {
                if( $row['fscore'] != null)
                {
                    $played++;
                    $scored += $row['fscore'];
                    $missed   += $row['sscore'];
                    if($row['fscore']==$row['sscore'])
                        continue;
                    if($row['fscore']>$row['sscore'])
                    {
                        $win++;
                    }
                    else
                    {
                        $lost++;
                    }
                }
            }
            else
            {
                if( $row['sscore'] != null)
                {
                    $played++;
                    $scored += $row['sscore'];
                    $missed += $row['fscore'];
                    if ($row['fscore'] == $row['sscore'])
                        continue;
                    if ($row['sscore'] > $row['fscore']) {
                        $win++;
                    } else {
                        $lost++;
                    }
                }
            }
        }
        $mysqli->close();
    ?>

    <script type="text/javascript">

    </script>
</head>
<body>
<div id="main">
    <?php
    include 'menu.php';
    ?>
    <div class="title">
        <p>О команде</p>
    </div>

    <div id="forma" >
        <form action="generateT.php" method="post">

            <hr/>
            <div>
                <p class="leg">Команда:  <?php echo $team['teamName'] ?></p>
                <div class="block" >
                    <?
                        $idfplayer = $team['idfplayer'];
                    ?>
                    <p><strong>Первый игрок : </strong><?php echo $firstPlayer?></p>
                    <p><strong>Второй игрок : </strong><?php echo $secondPlayer?></p>
                    <hr/>
                    <p><strong>кол-во сыгранных игр : </strong><?php echo $played?></p>
                    <p><strong>кол-во побед : </strong><?echo $win?></p>
                    <p><strong>кол-во поражений : </strong><?php echo$lost?></p>
                    <p><strong>забитые : </strong><?echo $scored?></p>
                    <p><strong>пропущенные голы : </strong><?echo $missed?></p>
                </div>
            </div>

        </form>
    </div>
</div>
</body>
</html>
