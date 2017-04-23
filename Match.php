<?php
class Match
{
    public  $firstTeam;
    public  $secondTeam;
    public  $firstTeamScore;
    public $secondTeamScore;
    public $round;
    public $id;
    public function __construct($firstTeam,$secondTeam,$firstTeamScore,$secondTeamScore,$round)
    {
        $this->firstTeam = $firstTeam;
        $this->secondTeam = $secondTeam;
        $this->firstTeamScore = $firstTeamScore;
        $this->secondTeamScore = $secondTeamScore;
        $this->round = $round;
    }
    public function __toString()
    {
        return ($this->firstTeam."  $this->firstTeamScore:$this->secondTeamScore  ".$this->secondTeam) ;
    }
} 