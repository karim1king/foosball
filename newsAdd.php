<?php
session_start();
if(!isset($_SESSION['user']['admin']))
{
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Добавить новость</title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script type="text/javascript" src="ajax.js"> </script>
    <script type="text/javascript" src="date.js"> </script>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script type="text/javascript">
        function initForm() {
            document.getElementById("date").value = (new Date()).shortFormat();
            document.getElementById("body").focus();
        }
    </script>
</head>
<body onload="initForm();">
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
            <form id="formx" method="post" action="addblogentry.php"  enctype= "multipart/form-data">
            Дата: <input type="text" id="date" name="date" value="" size="10" /><br />
            Текст: <!--<input type="text" id="body" name="body" value="" size="60" /><br />-->
            <br/>
            <textarea rows="4" cols="50" id="body" name="body"></textarea>
            <br/>
            Фото (необязательно): <input type="file" name="filename"/><br />
            <input class="button" type="submit" id="add" value="Добавить" /><br />
            <div id="status"></div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
