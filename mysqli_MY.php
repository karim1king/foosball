<?php
function connectMysqli()
{
    $mysqli =  new mysqli('localhost','root','','maindb');
  /*$mysqli = new mysqli('a132670.mysql.mchost.ru','a132670_1','k123456789','a132670_1');*/
    $mysqli->set_charset("utf8");
    return $mysqli;
}
