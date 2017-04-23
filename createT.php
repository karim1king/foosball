<?php
session_start();
if(!isset($_SESSION['user']['admin']))
{
    header("location:index.php");
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script type="text/javascript" src="script.js"></script>
    <script src="validate.js"></script>
    <script type="text/javascript">
        function changeTurType(select)
        {
            var elem =  document.getElementById("play-off");
            if(select.selectedIndex == 0)
            {

                elem.style.visibility = "hidden";
            }
            else
            {
                elem.style.visibility = "visible";
            }
        }
    </script>
    <?php
    function getUsers()
    {
        require_once "mysqli_MY.php"; $mysqli= connectMysqli();
        $result = $mysqli->query("SELECT * FROM reg");
        $mysqli->close();
        return $result;
    }
    ?>

    <script type="text/javascript">
        function checkTur(form)
        {
            var numOfPlayer = document.querySelectorAll('input[type="checkbox"]:checked').length;
            var bool = true;

            if(numOfPlayer<4 || numOfPlayer%2 != 0 )
            {
                document.getElementById('numOfPlayerHelp').innerHTML = "Введите четное количество игроков!";
                bool = false;
            }
            else
                document.getElementById('numOfPlayerHelp').innerHTML = "";
            var elem = document.getElementById('numOfRound');
            if(isNaN(elem.value) || elem.value=="")
            {
                document.getElementById('numRoundHelp').innerHTML = "Введите число!";
                bool = false;
            }
            else
                document.getElementById('numRoundHelp').innerHTML = "";
            if(!validateDate(document.getElementById('turDate'),document.getElementById('dateHelp')))
            {
                //document.getElementById('numRoundHelp').innerHTML= "Введите число!";
                bool = false;
            }
            else
                document.getElementById('dateHelp').innerHTML = "";
            if(document.getElementById('turName').value== "")
            {
                document.getElementById('turNameHelp').innerHTML= "Введите название турнира!";
                bool = false;
                return;
            }
            else
                document.getElementById('turNameHelp').innerHTML = "";
            var turName = document.getElementById('turName').value;
            $.ajax({
                type: "POST",
                url: "checkturrname.php",
                data: {"turname":turName},
                cache: false,
                success: function (responce)
                {
                    if(responce == 'true')
                    {
                        $('span#turNameHelp').html("Турнир с таким нозванием уже существует");
                    }
                    else
                    {
                        if(bool)
                        document.forms["myform"].submit();
                    }
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
        <p>Создать турнир</p>
    </div>

    <div id="forma" >
        <form action="generateT.php" id="myform" method="post">

            <hr/>
            <div>
                <p class="leg">Вид турнира:</p>
                <div class="block" >
                    <input type="radio" name="turState"  checked="checked" value="0"/>не начат<br/>
                    <input type="radio" name="turState" value="1"/>текущий<br/>
                    <input type="radio" name="turState" value="2"/>законченный<br/>
                </div>
            </div>
            <div>
                <p class="leg">Игроки</p>
                <div class="block , blockUsers" >

                    <table align="center"  border="1" style="width:100%">
                        <?php
                            $result = getUsers();
                            $var = -1;
                            echo "<tr>";
                            while($temp = $result->fetch_assoc())
                            {
                                $id = $temp['id'];
                                $name = $temp['name'];
                                $surname = $temp['surname'];
                                if($var < 4)
                                {
                                    echo "<td><input type='checkbox' name='players[]' value='$id'/>$name  $surname.</td>";
                                    $var++;
                                }
                                else
                                {
                                    $var=0;
                                    echo"</tr>";
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='players[]' value='$id'/>$name  $surname.</td>";
                                }
                            }
                            echo"</tr>";
                        ?>
                    </table>
                </div>
                <span id="numOfPlayerHelp"></span>
            </div>

            <div>
                <p class="leg">Тип турнира:</p>
                <div class="block">
                    <select name="turType" onchange="changeTurType(this);">
                        <option value="1" >Регулярный чимпиона</option>
                        <option value="2">Регулярный чимпиона и Play-off</option>
                    </select>
                    <div id="roundCount" >
                        <label for="numOfRound" style="margin-right: 4px">Количество раундов:</label>
                        <input name="numOfRound"  type="text" id="numOfRound"><br/>
                        <span id="numRoundHelp"></span>
                        <label for="turName"  style="margin-right: 22px">Название турнира:</label>
                        <input name="turName"  type="text" id="turName"><br/>
                        <span id="turNameHelp"></span>
                        <label for="turDate">Дата начала турнира:</label>
                        <input name="turDate"   type="text" id="turDate"><br/>
                        <span id="dateHelp"></span>
                    </div>
                    <div  id="play-off" style="visibility: hidden">
                    play-off:
                    <select name="play-off"">
                        <option value="1" >1/8</option>
                        <option value="2">1/4</option>
                    </select>
                        </div>
                </div>
            </div>
            <input class="button" type="button" onclick="checkTur(this.form);" value="Создать турнир"/>
        </form>
    </div>
</div>
</body>
</html>
