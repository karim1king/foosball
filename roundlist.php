<?php
session_start();
$tur = $_GET['turname'];
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$result = $mysqli->query("SELECT * FROM turtable WHERE id=$tur");
$tur = $result->fetch_assoc();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script src='validate.js'></script>
    <script src='script.js'></script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <style type="text/css">
        #filter input[type="text"]
        {
            margin: 5px;
            height: 30px;
            padding: 5px;
            width: 260px;
            font-size: 100%;
            box-shadow: 0 0 10px rgb(97, 97, 97);
        }
        #result_div table tr
        {
            height: 20px;
        }
    </style>

    <script type="text/javascript">
        var turName;
        function getMatchesValid(input,help)
        {
            getMatches(input.value);
        }
        function getMatches(date)
        {
            var get = location.search;
            var turName;
            if(get != '')
            {
                tmp = get.split('=');
                turName = tmp[1];
                if(turName == '')
                    return;
                document.getElementById("turname").value = turName;
            }
            else
            {
                return;
            }
            var type = document.getElementById("turType").value;
            $('div#download_img').css('display', 'block');
            $.ajax({
                type: "POST",
                url: "getroundlist.php",
                data: {"turname":turName,"porcname": date,"type":type},
                cache: false,
                success: function (responce)
                {
                    $('div#result_div').html(responce);
                    $('div#download_img').css('display', 'none');
                }
            })
        }
    </script>
</head>
<body>
<div id="main">
    <?php
     include 'menu.php';
    ?>
    <div class="title">
        <p>Матчи турнира: <strong style="color: #3f3f3f"><?echo $tur['turname']?></strong></p>
    </div>

    <div>
        <p class="legfilter" >Фильтрация:</p>
        <form method="post" action="savescore.php">
            <div id="filter">
                <select name="turType" id="turType" onchange="changeTurType(this);">
                    <option value="1">Регулярный чемпионат</option>
                    <?php

                    if($tur['type'] == 2)
                    {
                        echo "<option value='2'>Play-off</option>";
                    }
                    $mysqli->close();
                    ?>
                </select>
                Название команды или игрока:
                <input type="text" name="playerName" id="playerName" size="30"/>
                <input type="button"onclick="getMatches(document.getElementById('playerName').value);"class="buttonfilter" value="ОК"/>
                <span id="filter_help"></span>
            </div>
            <div id="download_img" align="center"><img src="loading.gif" alt="loding"/></div>
            <p class="leg">Раунды:</p>
            <div class="block" id="result_div"></div>
            <input type="hidden"  id="turname" name="turname" value=""/>
        </form>
    </div>

</div>
</body>
</html>
