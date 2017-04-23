<?php
session_start();
if(!isset($_SESSION['user']['admin']))
{
    header("location:index.php");
}
$tur = $_GET['turname'];
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$result = $mysqli->query("SELECT * FROM turtable WHERE id=$tur");
$tur = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script src='validate.js'></script>
    <script type="text/javascript" src='script.js'></script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <style type="text/css">
        input[type="text"]
        {
            margin: 5px;
            height: 30px;
            padding: 5px;
            width: 35px;
            font-size: 100%;
            box-shadow: 0 0 10px rgb(97, 97, 97);
        }
        #playerName
        {
            margin: 5px;
            height: 30px;
            padding: 5px;
            width: 260px;
            font-size: 100%;
            box-shadow: 0 0 10px rgb(97, 97, 97);
        }
        .alignLeft
        {
            float: left;
            width: 200px;
            margin-right: 20px;
            margin-left: 20px;
            margin-bottom: 20px;
        }
        .blockH
        {
            height: 80px;
        }
        .after_box
        {
            clear: left;
        }
    </style>

    <script type="text/javascript">
        var turName;
        function onLoad()
        {
            var get = location.search;
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
        }
        function getMatches(date)
        {

                var type = document.getElementById("turType").value;
                $('div#download_img').css('display', 'block');
            $.ajax({
                type: "POST",
                url: "roundlistedit.php",
                data: {"turname":turName,"porcname": date,"type":type},
                cache: false,
                success: function (responce)
                {
                    $('div#result_div').html(responce);
                    $('div#download_img').css('display', 'none');
                }
            })
        }
        function changeTurType(select)
        {
            var str =  select.form.action;
            if(select.value == 1)
            {
                select.form.action = "savescore.php?turType=1&turVote="+str.slice(-1);
            }
            else
            {
                select.form.action = "savescore.php?turType=2&turVote="+str.slice(-1);
            }
        }
        function changeTurVote(check)
        {
            var str =  check.form.action;
            if(check.value == 1)
            {

                check.form.action = str.substr(0,str.length-1)+"1";
            }
            else
            {
                check.form.action = str.substr(0,str.length-1)+"0";
            }
        }
    </script>
</head>
<body onload="onLoad();">
<div id="main">
    <?php
    include 'menu.php';
    ?>

    <div class="title">
        <p>Матчи турнира: <strong style="color: #3f3f3f"><?php echo $tur['turname']?></strong></p>
    </div>
    <div>
        <p class="legfilter" >Фильтрация:</p>
        <form method="post" action="savescore.php?turType=1&turVote=<?php echo $tur['vote'];?>">
            <div id="filter">
                <select name="turType" id="turType" onchange="changeTurType(this);">
                    <option value="1">Регулярный чемпионат</option>
                    <?php

                        if($tur['type'] == 2)
                        {
                            echo "<option value='2'>Play-off</option>";
                        }
                    ?>
                </select>
                Название команды или игрока:
                <input type="text" name="playerName" id="playerName" size="30"/>
                <input type="button"onclick="getMatches(document.getElementById('playerName').value);"class="buttonfilter" value="ОК"/>
                <input type="submit" class="buttonfilter" value="Сохранить"/>
                <span id="filter_help"></span>

             </div>
            <div>
                <div class="alignLeft">
                <p class="leg">Вид турнира:</p>
                <div class="block , blockH" >
                    <?php
                    $turState = $tur['state'];
                    $s = Array(null,null,null);
                        switch ($turState)
                        {
                            case 0: $s[0] = 'checked="checked"';break;
                            case 1: $s[1] = 'checked="checked"';break;
                            case 2: $s[2] = 'checked="checked"';break;
                        }
                        echo "<input type='radio' name='turState' $s[0] value='0'/>не начат<br/>
                    <input type='radio' name='turState' $s[1] value='1'/>текущий<br/>
                    <input type='radio' name='turState' $s[2] value='2'/>законченный<br/>";
                    ?>
                </div>
                </div>
                <div class="alignLeft">
                <p class="leg">Голосование:</p>
                <div class="block , blockH" >
                <?php
                    $turVote = $tur['vote'];
                    $a = Array(null,null);
                    if($turVote == 1)
                        $a[0] = 'checked="checked"';
                    else
                        $a[1] = 'checked="checked"';

                    echo "<input type='radio' name='vote' $a[0] value='1' onclick='changeTurVote(this);'/>Вкл<br/>
                        <input type='radio' name='vote' $a[1] value='0' onclick='changeTurVote(this);'/>Выкл<br/>"
                ?>
                </div>
                </div>
            </div>
            <div id="download_img" align="center"><img src="loading.gif" alt="loding"/></div>
            <p class="leg , after_box">Раунды:</p>
            <div class="block" id="result_div"></div>
            <input type="hidden"  id="turname" name="turname" value=""/>
        </form>
    </div>
</div>
</body>
</html>
