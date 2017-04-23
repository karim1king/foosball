<?php session_start(); ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script type="text/javascript" src='validate.js'></script>
    <script type="text/javascript" src='script.js'></script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <style type="text/css">
        td{
            width: 200px;
        }
    </style>

</head>
<body onload="getMatches();">
<div id="main">
    <?php
    include 'menu.php';
    ?>
    <div class="title">
        <p>Турниры</p>
    </div>

    <div>
        <p class="legfilter" >Турниры:</p>
        </div>
        <?php
            require_once "mysqli_MY.php"; $mysqli= connectMysqli();
            $result = $mysqli->query("SELECT * FROM turtable ORDER BY id DESC");
            $count = 0;
            while($row = $result->fetch_assoc())
            {
                $turname = $row['turname'];
                $turID = $row['id'];
                $turState = $row['state'];
                $turType = $row['type'];
                switch ($turState)
                {
                    case 0: $turState = "Не начат";break;
                    case 1: $turState = "Текущий";break;
                    case 2: $turState = "Закончен";break;
                }
                if($count%2 == 0)
                    echo "<div class='block' style='text-align: center'>";
                else
                    echo "<div class='block' style='background-color: #d1d1d1; text-align: center'>";

                    echo "<table><tr>";
                    echo "<td><span style='float: left'>$turState</span></td>";
                    echo "<td>$turname</td>";
                    echo "<td><a href='roundlist.php?turname=$turID'>Матчи</a></td>";
                    echo "<td><a href='turTable.php?turname=$turID'>Таблица</a></td>";
                    if($turType == 2)
                        echo "<td><a href='grid.php?turname=$turID'>Сетка</a></td>";
                else
                    echo "<td><pre>        </pre></td>";
                if($row['vote'] == 1)
                    echo "<td><a href='vote.php?turname=$turID'>Голосование</a></td>";
                else
                    echo "<td><pre>           </pre></td>";
                    echo "</tr></table>";
                    echo "</div>";
                $count++;
            }
        ?>
    </div>

</div>
</body>
</html>
