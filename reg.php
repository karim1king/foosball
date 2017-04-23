<?php
require_once "mysqli_MY.php";
require_once "user.php";
$login = $_POST['login'];
$password = md5($_POST['password1']);
$email =$_POST['email'];
$name =$_POST['name'];
$surname =$_POST['surname'];
$user = new User($login,$password,$email,$name,$surname);
$numError = testLogin($login) + testEmail($email);
//    echo $numError;
if($numError == 0)
{
    $user->addUser();
    header("Location: index.php");
}
else
{
    header("Location: regform.php?error=$numError&name=$name&surname=$surname&email=$email&login=$login");
}
function testEmail ($email)
{
        $mysqli= connectMysqli();
        $result = $mysqli->query("SELECT email FROM reg Where (email='$email') limit 1 ");
        $num_results = $result->num_rows;
        $mysqli->close();
        if($num_results>0)
        {
            return 1;
        }
        else
        {
           return 0;
        }
}
function testLogin ($login)
{
    $mysqli= connectMysqli();
        $result = $mysqli->query("SELECT login FROM reg Where (login='$login') limit 1");
        $num_results = $result->num_rows;
         $mysqli->close();
        if ($num_results > 0) {
           return 2;
        } else {
            return 0;
        }
}



