<?php
/**
 * Created by PhpStorm.
 * User: Karim
 * Date: 13.02.2015
 * Time: 18:30
 */

class Team
{
    public $firstPlayer;
    public $secondPlayer;
    public $teamName;
    public $id;

    public function __construct($firstPlayer, $secondPlayer,$id)
    {

        $this->firstPlayer = $firstPlayer;
        $this->secondPlayer = $secondPlayer;
        $this->id =$id;
        $this->teamName = $firstPlayer->toShortString() . '+' . $secondPlayer->toShortString();
    }

    public function __toString()
    {
        return ($this->firstPlayer->toShortString() . '+' . $this->secondPlayer->toShortString());
    }

    public function t($turName)
    {
//        return "<a href='teamdet.php?team=".$this->id."'>$this->teamName</a>";
        return "<a href='teamdet.php?id=".$this->id.'&turname='.$turName."'>$this->teamName</a>";

    }
    public static function nameById($id,$mysqli)
    {
        $result = $mysqli->query("SELECT name,surname FROM reg WHERE id=$id");
        $row = $result->fetch_assoc();
        return $fullName= $row["name"] ." ". $row["surname"];
    }
    public static function getTeamNameById($id,$turName,$mysqli,$condition,&$TeamName)
    {
        if($id != null)
        {
            $teamTable = $turName."_teams";
            $result = $mysqli->query("SELECT * FROM $teamTable WHERE id=$id");
            $row = $result->fetch_assoc();

                $name= $row["teamName"];
                $TeamName = "<a href='teamdet.php?id=".$id.'&turname='.$turName."'>$name</a>";
                $fname = Team::nameById($row['idfplayer'],$mysqli);
                $sname = Team::nameById($row['idsplayer'],$mysqli);
                if($condition != null && (mb_stripos($name,$condition,0, 'UTF-8')===FALSE && mb_stripos($fname,$condition,0, 'UTF-8')===FALSE && mb_stripos($sname,$condition,0, 'UTF-8')===FALSE))
                    return false;
                else
                    return  true;
        }
        else
        {
            $TeamName = null;
        }
        return  true;
    }

  }
