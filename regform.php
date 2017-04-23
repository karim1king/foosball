<?php
session_start();
 $numError = 0;
$login = $password = $email = $name = $surname = "";
if(isset($_POST['login']) && isset($_POST['email']) && isset($_POST['surname']))
{
    require_once "mysqli_MY.php";
    require_once "user.php";
    $login = $_POST['login'];
    $password = md5($_POST['password1']);
    $email =$_POST['email'];
    $name =$_POST['name'];
    $surname =$_POST['surname'];
    $user = new User($login,$password,$email,$name,$surname);
    $numError = testLogin($login) + testEmail($email);
    if($numError == 0)
    {
        $user->addUser();
        header("Location: index.php");
        exit;
    }
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

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript" src="validate.js"> </script>
    <script type="text/javascript" src="script.js"> </script>
    <link type="text/css" rel="stylesheet" href="adminStyle.css"/>
    <link type="text/css" rel="stylesheet" href="regStyle.css"/>
</head>
<body>
<div id="main">
    <?php
         include 'menu.php';
    ?>
    <div class="title">
        <p>Регистрация</p>
    </div>
    <div id="forma">
        <form action="#" method="post">
            <hr/>
            <div class="regBlock">

                <label>Логин:</label><br/>
                <input value="<?php echo $login?>" type="text" name="login"onblur="validateNonEmpty(this, document.getElementById('login_help'))"/>
                <span id="login_help" class="help">
                        <?php
                            if($numError == 2 || $numError == 3)
                            echo "Пользователь с таким логином уже существует!!";
                         ?>
                </span>
                <label>Имя:</label><br/>
                <input value="<?php echo $name?>" type="text" name="name" onblur="validateNonEmpty(this, document.getElementById('name_help'))"/>
                <span id="name_help" class="help"></span>

                <label>Фамилия:</label><br/>
                <input value="<?php echo $surname?>" type="text" name="surname" onblur="validateNonEmpty(this, document.getElementById('surname_help'))"/>
                <span id="surname_help" class="help"></span>

                <label>E-mail:</label><br/>
                <input value="<?php $email?>" type="text" name="email" onblur="validateEmail(this, document.getElementById('email_help'))"/>
                <span id="email_help" class="help">
                    <?php
                    if($numError == 1 || $numError == 3)
                      echo "Пользователь с таким адресом уже существует!!";
                     ?>
                </span>
                <label>Пароль:</label><br/>
                <input type="password" name="password1" onblur="validateLength(6,16,this, document.getElementById('password_help1'))"/>
                <span id="password_help1" class="help"></span>
                <label>Повторите пароль:</label><br/>
                <input type="password" name="password2" onblur="validateLength(6,16,this, document.getElementById('password_help2'));twoPassword(this.form)"/>
                <span id="password_help2" class="help"></span><br/>
                <input class="button" type="button"  value="Регистрация" onclick="placeOrder(this.form);"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>