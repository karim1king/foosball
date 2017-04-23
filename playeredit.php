<?php session_start();?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <?php
         require_once "mysqli_MY.php"; $mysqli= connectMysqli();
        $playerId = $_GET['id'];
        $result = $mysqli->query("SELECT * FROM reg WHERE id=$playerId");
        $player = $result->fetch_assoc();
        $currentTeam = $_GET['currentTeam'];
        $currentTur = $_GET['currentTur'];
        $teamTable = $currentTur."_teams";
        if($currentTeam!="" && $currentTur !="")
        {
            $result = $mysqli->query("SELECT * FROM $teamTable WHERE id=$currentTeam");
            $team = $result->fetch_assoc();
        }
        $mysqli->close();
    ?>
</head>
<body>
<div id="main">
    <?php
    include 'menu.php';
    ?>
    <div class="title">
        <p>Об Учаснике</p>
    </div>

    <div id="forma">
        <form action="savePlayerEdit.php" method="post">

            <hr/>
            <div>
                <p class="leg">Учасник:  <?php echo $player['name'] ?></p>

                <div class="block" style="padding-left: 20px;">
                    <p><strong>Имя:</strong> <?php if(isset($player)) echo "<input name='name' type='text' value='". $player['name']."'/>" ?></p>
                    <p><strong>Фамилия:</strong>  <?php if(isset($player))echo "<input name='surname' type='text' value='". $player['surname']."'/>"?></p>
                    <p><strong>Команда:</strong>  <?php  if(isset($team)) echo "<input name='teamName' type='text' value='". $team['teamName']."'/>"?></p>
                </div>
                <input type="submit" class="button" value="Сохранить">
            </div>
            <input name="id" type="hidden" value="<?php echo $playerId?>"/>
            <input name="teamTable" type="hidden" value="<?php echo $teamTable?>"/>
            <input name="currentTeam" type="hidden" value="<?php echo $currentTeam ?>"/>
        </form>
    </div>
</div>
</body>
</html>
