<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
<?php
    class TurOrTeam
    {
       public $id;
       public $name;
        public function __construct($id,$name)
        {
            $this->id=$id;
            $this->name = $name;
        }
    }


 include_once 'mysqli_MY.php';
   $mysqli = connectMysqli();
    $playerId = $_GET['id'];
    $playerItr = $mysqli->query("SELECT * FROM reg WHERE id = $playerId");
    $player = $playerItr->fetch_assoc();
    $allTurs = $mysqli->query("SELECT * FROM turtable");
    $myTurs = Array();
    $myTeams = Array();
    $currentTeams = Array();
    $currentTurs = Array();
    while($turRow = $allTurs->fetch_assoc())
    {
        $turId = $turRow['id'];
        $teamTable = $turId."_teams";
        $allTeams = $mysqli->query("SELECT * FROM $teamTable WHERE idfplayer=$playerId OR idsplayer=$playerId LIMIT 1");
        if($allTeams->num_rows != 0)
        {
            $team = $allTeams->fetch_assoc();
            if($turRow['state']==1)
            {
                $currentTurs[] = new TurOrTeam($turRow['id'],$turRow['turname']);
                $currentTeams[] = new TurOrTeam($team['id'],$team['teamName']);
            }
            $myTurs[] = new TurOrTeam($turRow['id'],$turRow['turname']);
            $myTeams[] =new TurOrTeam($team['id'],$team['teamName']);
        }

    }
?>
</head>
<body>
<div id="main">
    <?php
    include 'menu.php';
    ?>
    <div class="title">
        <p>Об Участнике</p>
    </div>

    <div id="forma" >
        <form action="generateT.php" method="post">

            <hr/>
            <div>
                <p class="leg">Участник:  <?php echo $player['name'] ?></p>

                <div class="block" style="padding-left: 20px;">
                    <p><strong>Имя:</strong> <?php echo $player['name'] ?></p>
                    <p><strong>Фамилия:</strong>  <?php echo $player['surname'] ?></p>
                    <p><strong>Текущая команда</strong>
                        <?php
                        $i=0;
                        while($i < count($currentTeams))
                        {
                            $name = $currentTeams[$i]->name;
                            $teamId = $currentTeams[$i]->id;
                            $turId = $currentTurs[$i]->id;
                            echo "<a href='teamdet.php?id=$teamId&turname=$turId'>$name</a> , ";
                            $i++;
                        }
                        ?></p>
                    <p><strong>Текущий турнир</strong>
                        <?php
                        $i=0;
                        while($i < count($currentTurs))
                        {
                            $name = $currentTurs[$i]->name;
                            $turId = $currentTurs[$i]->id;
                            echo "<a href='roundlist.php?turname=$turId'>$name</a>  , ";
                            $i++;
                        }
                        ?>
                    </p>
                    <?php
                        if(isset($_SESSION['user']))
                        {
                            $id =$player['id'];
                            if($_SESSION['user']['id'] == $id)
                            {
                                $cTeam = isset($currentTeams[0]) ? $currentTeams[0]->id : "";
                                $cTur = isset($currentTurs[0]) ? $currentTurs[0]->id : "";
                                echo "<a style='color: #00a328' href='playeredit.php?id=$id&currentTeam=$cTeam&currentTur=$cTur'>Редактировать <img src='edit.png'></a>";
                            }
                        }
                    ?>
                    <hr>
                    <p>Все команды:
                    <?php
                        $i=0;
                        while($i < count($myTeams))
                        {
                            $name = $myTeams[$i]->name;
                            $teamId = $myTeams[$i]->id;
                            $turId = $myTurs[$i]->id;
                            echo "<a href='teamdet.php?id=$teamId&turname=$turId'>$name</a> , ";
                            $i++;
                        }
                    ?></p>
                    <p>Все турниры:
                    <?php
                        $i=0;
                        while($i < count($myTeams))
                        {
                            $name = $myTurs[$i]->name;
                            $turId = $myTurs[$i]->id;
                            echo "<a href='roundlist.php?turname=$turId'>$name</a>  , ";
                            $i++;
                        }
                    ?></p>
                </div>
            </div>

        </form>
    </div>
</div>
</body>
</html>
