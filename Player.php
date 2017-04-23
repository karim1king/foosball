<?php
/**
 * Created by PhpStorm.
 * User: Karim
 * Date: 13.02.2015
 * Time: 20:00
 */

class Player
{
    public $name;
    public $surname;
    public $rank;
    public $id;
    public function __construct($name,$surname,$rank,$id)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->rank = $rank;
        $this->id = $id;
    }
    public function __toString()
    {
        return ($this->name." ".$this->surname) ;
    }

    public function toShortString()
    {
        return ($this->name." ".mb_substr($this->surname,0,1,"utf-8") ) ;
    }
/*    public function hrefString()
    {
        return "<a href='teamdet.php?team=".$this->id."'>$this->$name\\s$surname</a>";

    }*/
    public static function getPlayerNameById($id,$mysqli)
    {
        $result = $mysqli->query("SELECT * FROM reg WHERE id=$id");
        $row = $result->fetch_assoc();
        $fullName= $row["name"] ." ". $row["surname"];
        $idPlayer = $row['id'];
        return "<a href='playerdet.php?id=".$idPlayer."'>$fullName</a>";
    }
} 