<?php session_start()?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src="validReg.js"> </script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <link type="text/css" rel="stylesheet" href="homeStyle.css"/>
    <script type="text/javascript" src="script.js"></script>
</head>

<body>
<div>
    <div id="mainUser">
        <?php
        include 'menu.php';
        ?>
<div class="title">
    <p>Чемпионаты по настольному футболу</p>
</div>
<hr/>
<div class="block">
    <div style="background-color: #bababa; text-align: center"><a style="background-color: #bababa; text-align: center" href="turTable.php">Турнирная таблица</a></div>
    <br/>
    <div style="background-color: #bababa; text-align: center"><a href="roundlist.php">Матчи</a></div>
</div>
</div>
</div>
</body>
</html>