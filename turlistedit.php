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
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.js'></script>
    <script src='validate.js'></script>
    <script type="text/javascript" src='script.js'></script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <style type="text/css">
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
                if($count%2 == 0)
                {
                    echo "<div class='block' style='text-align: center'>";
                    echo "<a href='roundlist.php?turname=$turID'>$turname</a>&nbsp&nbsp";
                    echo "<a href='roundlisteditM.php?turname=$turID'><img src='edit.png' alt=''></a>&nbsp&nbsp&nbsp";
                    echo "<a href='turdelete.php?turname=$turID'><img src='delete.png' alt=''></a>&nbsp&nbsp&nbsp";
                    echo "</div>";
                }
                else
                {
                    echo "<div class='block' style='background-color: #d1d1d1; text-align: center'>";
                    echo "<a href='roundlist.php?turname=$turID'>$turname</a>&nbsp&nbsp";
                    echo "<a href='roundlisteditM.php?turname=$turID'><img src='edit.png' alt=''></a>&nbsp&nbsp&nbsp";
                    echo "<a href='turdelete.php?turname=$turID'><img src='delete.png' alt=''></a>&nbsp&nbsp&nbsp";
                    echo "</div>";
                }
                $count++;
            }
        $mysqli->close();
        ?>
    </div>

</div>
</body>
</html>
