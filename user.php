<?php
    include_once "mysqli_MY.php";
    class User
    {
        private $login;
        private $password;
        private $email;
        private $name;
        private $surname;
        private $rank;
        public  function __construct($login,$password,$email,$name,$surname)
        {
            $this->password = $password;
            $this->login = $login;
            $this->email = $email;
            $this->name = $name;
            $this->surname = $surname;
        }
        public function checkUser()
        {
            $mysqli = connectMysqli();
            $result = $mysqli->query("SELECT password FROM reg WHERE login='$this->login'");
            $row = $result->fetch_assoc();
            $mysqli->close();
            return $row['password']== $this->password;
        }
        public function  addUser()
        {
            $mysqli = connectMysqli();
            $result = $mysqli->query("SELECT rank FROM reg ORDER BY rank");
            $result->data_seek(round($result->num_rows * 0.3));
            $row = $result->fetch_assoc();
            $this->rank =$row['rank'];
            $mysqli->query("INSERT INTO reg (login,password,email,name,surname,rank) VALUES ('$this->login','$this->password','$this->email','$this->name','$this->surname','$this->rank')");
            $mysqli->close();
        }
    }