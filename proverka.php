<?php 
session_start();
// $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
// $user = json_decode($s, true);
//$user['network'] - соц. сеть, через которую авторизовался пользователь
//$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
//$user['first_name'] - имя пользователя
//$user['last_name'] - фамилия пользователя
require_once "mysqli_MY.php"; $mysqli= connectMysqli();
if(isset($user) && !isset($_POST['login']))
{
       $id = $user['identity'];
       $result = $mysqli->query("SELECT * FROM reg WHERE login='$id'");
       if($result->num_rows == 0)
       {
           $name = $user['first_name'];
           $surname = $user['last_name'];
           $mysqli->query("INSERT INTO reg ('login','name','surname') values ('$id','$name','$surname')");
           $user['id'] = $mysqli->insert_id;
       }
        else
        {
            $row = $result->fetch_assoc();
            $user['id'] = $row['id'];
        }
        $_SESSION['user']= $user;
       header("location: index.php");
       exit;
 }
else
{
    if(isset($_POST['login']))
    {
        $login = $_POST['login'];
        $password = md5($_POST['password']);
        $result = $mysqli->query("SELECT * FROM reg WHERE login='$login'");
        if($row = $result->fetch_assoc())
        {
            if($row['password'] == $password)
            {
                $user = Array();
                $user['first_name'] = $row['name'];
                $user['last_name'] = $row['surname'];
                $user['id'] = $row['id'];
                if( $row['access'] >= 10)
                    $user['admin']="admin";
                $_SESSION['user']= $user;
                $name = $row['name'];
                echo "0";
                exit;
            }
        }
    }

}