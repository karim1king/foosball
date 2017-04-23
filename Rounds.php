<?php
class Rounds
{
    public $rounds;

    public function __construct()
    {
        $rounds = Array();
    }
    public function toHtmlTable()
    {
        $var = 0;
        echo "<tr>";
        $count = 0;
        while($count <count($this->rounds))
        {
            echo "<table align='center'  border='1' style='width:100%'>";
            $matches =$this->rounds[$count];
            for($j=0;$j<count($matches);$j++)
            {
                if($var < 5)
                {
                    echo "<td>$matches[$j]</td>";
                    $var++;
                }
                else
                {
                    $var=0;
                    echo"</tr>";
                    echo "<tr>";
                }
                echo"</tr>";
            }
            $count++;
            echo "</table>";
        }
    }
} 