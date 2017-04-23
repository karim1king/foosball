<?php
$ides = $_POST['ides'];
$tur = $_POST['turName'];
$voteTable = $tur."_vote";
$id = $_POST['idPlayer'];
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
$result = $mysqli->query("INSERT INTO $voteTable (player) VALUES ($id)");
$j=1;
for($i=count($ides)-1;$i>=0;$i--)
{
    $mysqli->query("UPDATE `reg` SET `rank` = `rank` + $j WHERE `id` = $ides[$i]");
    $j++;
}