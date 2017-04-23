<?php session_start();
function mySort($f2, $f1)
{
    if ($f1->points < $f2->points) return -1;
    elseif ($f1->points > $f2->points) return 1;
    else return 0;
}

class TeamInTable
{
    public $name;
    public $played;
    public $win;
    public $lost;
    public $scored;
    public $missed;
    public $points;

    public function __construct($name, $played, $win, $lost, $scored, $missed)
    {
        $this->name = $name;
        $this->played = $played;
        $this->win = $win;
        $this->lost = $lost;
        $this->scored = $scored;
        $this->missed = $missed;
        $this->points = 3 * $win;
    }
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <script type="text/javascript" src='script.js'></script>
<?php
    require_once "Player.php";
    require_once "TeamInTable.php";
    $matchTable = $_GET['turname'] . "_matches";
    $teamTable = $_GET['turname'] . "_teams";

    require_once "mysqli_MY.php";
    $mysqli = connectMysqli();
    $teamResult = $mysqli->query("SELECT * FROM $teamTable");
    $rows = Array();
    while ($team = $teamResult->fetch_assoc()) {
        $teamId = $team['id'];
        $result = $mysqli->query("SELECT * FROM $matchTable WHERE fteam=$teamId OR steam=$teamId");
        $win = 0;
        $lost = 0;
        $scored = 0;
        $missed = 0;
        $played = 0;
        while ($row = $result->fetch_assoc()) {
            if ($row['fteam'] == $teamId) {
                if ($row['fscore'] != null) {
                    $played++;
                    $scored += $row['fscore'];
                    $missed += $row['sscore'];
                    if ($row['fscore'] == $row['sscore'])
                        continue;
                    if ($row['fscore'] > $row['sscore']) {
                        $win++;
                    } else {
                        $lost++;
                    }
                }
            } else {
                if ($row['sscore'] != null)
                {
                    $played++;
                    $scored += $row['sscore'];
                    $missed += $row['fscore'];
                    if ($row['fscore'] == $row['sscore'])
                        continue;
                    if ($row['sscore'] > $row['fscore']) {
                        $win++;
                    } else {
                        $lost++;
                    }
                }
            }
        }
        $a = new TeamInTable($team['teamName'], $played, $win, $lost, $scored, $missed);
        $rows[] = $a;
        usort($rows, "mySort");
    }
    $tur = $_GET['turname'];
    $result = $mysqli->query("SELECT turname FROM turtable WHERE id=$tur");
    $tur = $result->fetch_assoc();
    $mysqli->close();
?>
</head>
<body>
<div id="main">
<?php
    include 'menu.php';
?>
    <div class="title">
        <p>Таблица турнира: <strong style="color: #3f3f3f"><?php echo $tur['turname']?></strong></p>
    </div>
    <hr/>
    <div>
        <p class="legfilter">Турнирная таблица:  <?php echo $team['teamName'] ?></p>
        <div class="block">
            <table align="center" border="2" cellspacing='0' cellpadding="7">
                <br/>
                <tr style="background-color: rgba(130, 230, 49, 0.87);">
                    <th>место</th>
                    <th>команда</th>
                    <th>игр</th>
                    <th>побед</th>
                    <th>поражений</th>
                    <th>забитые</th>
                    <th>пропущенные</th>
                    <th>очки</th>
                </tr>
                <?php
                $i = 0;
                while ($i < count($rows)) {
                    $name = $rows[$i]->name;
                    $played = $rows[$i]->played;
                    $win = $rows[$i]->win;
                    $lost = $rows[$i]->lost;
                    $scored = $rows[$i]->scored;
                    $missed = $rows[$i]->missed;
                    $points = $rows[$i]->points;
                    $m = ($i + 1);
                    echo "<tr>";
                    echo "<th>$m</th>
                                        <th>$name</th>
                                        <th>$played</th>
                                        <th>$win</th>
                                        <th>$lost </th>
                                        <th>$scored</th>
                                        <th>$missed</th>
                                        <th>$points</th>";
                    echo "</tr>";
                    $i++;
                }
                ?>
            </table>
            <br/>
        </div>
    </div>
</div>
</body>
</html>
