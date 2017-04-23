<?php
session_start();
if(!isset($_SESSION['user']))
{
    header("location:turlist.php");
    exit;
}
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$idPlayer = $_SESSION['user']['id'];
$tur = $_GET['turname'];
$voteTable = $tur."_vote";
$result = $mysqli->query("SHOW TABLES LIKE '$voteTable'");
if($result->num_rows == 0)
{
    header("location:turlist.php");
    exit;
}
$result = $mysqli->query("SELECT player FROM $voteTable WHERE player=$idPlayer");
$error = 0;
if($result->num_rows != 0)
{
    $error = 1;
}
$result = $mysqli->query("SELECT * FROM turtable WHERE id='$tur' AND vote=1 LIMIT 1");
if($result->num_rows == 0)
{
    header("HTTP/1.0 404 Not Found");
    exit;
}
$row = $result->fetch_assoc();
$turname = $row['turname'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">

    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script type="text/javascript" src="script.js"></script>
    <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
        #sortable li span { position: absolute; margin-left: -1.3em; }
    </style>
    <script>
        var turName=<?php echo $tur;?>;
        var idPlayer=<?php echo $idPlayer;?>;
        function sendIdes()
        {
            var elems = document.getElementsByClassName("ui-state-default");
            var ides = new Array();
            var id;
            var i=0;

            while(i < elems.length)
            {
                id = elems[i].id;
                ides.push(id);
                i++;
            }
           $.ajax({
                type: "POST",
                url: "voteSend.php",
                data: {'ides': ides,'turName':turName,'idPlayer':idPlayer},
                cache: false,
                success: function (responce)
                {
                    alert("Спасибо, ваш голос будет учтен");
                    document.location.href="/";
                }
            })
        }
    </script>
    <script>

        $(function()
        {
            $( "#sortable" ).sortable();
            $( "#sortable" ).disableSelection();
        });
    </script>
    <style type="text/css">
        .voteBlock
        {
            padding: 40px;
        }
    </style>
</head>
<body>
<div id="main">
    <?php
    include 'menu.php';
    ?>
    <div class="title">
        <p>Голосование по турниру: <strong style="color: #3f3f3f"><?echo $turname?></strong></p>
    </div>
    <hr/>
    <div class="block , voteBlock">
        <?php
            if($error == 1)
            {
                echo "<p style='color: red'>Вы уже проголосовали!</p>";
                $mysqli->close();
                exit;
            }
        ?>
        <ul id="sortable">
            <?php
                require_once "Player.php";
                $teamTable = $tur."_teams";
                $result = $mysqli->query("SELECT idfplayer,idsplayer FROM $teamTable");
                $bool = false;
                while($rowTeam = $result->fetch_assoc())
                {
                    $id1=$rowTeam['idfplayer'];
                    $id2=$rowTeam['idsplayer'];
                    if($id1 == $idPlayer || $id2 == $idPlayer)
                    {
                        $bool = true;
                    }
                    $str1 = Player::getPlayerNameById($id1,$mysqli);
                    $str2 = Player::getPlayerNameById($id2,$mysqli);
                    echo "<li id=$id1 class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>$str1</li>";
                    echo "<li id=$id2 class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>$str2</li>";
                }
                 $mysqli->close();
                // if(!$bool)
                // {
                //     header("location:turlist.php");
                //     exit;
                // }
            ?>
<!--            <li id='1' class='ui-state-default'><span class='ui-icon ui-icon-arrowthick-2-n-s'></span>Item 1</li>
            <li id="2"  class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
            <li id="3"  class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
            <li id="4" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
            <li id="5" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
            <li id="6" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
            <li id="7" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li>-->
        </ul>
        <br/>
        <input class="button" type="button" value="Голосовать" onclick="sendIdes()">
    </div>
</div>
</body>
</html>