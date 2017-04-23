<?php
/**
 * Created by PhpStorm.
 * User: Karim
 * Date: 13.02.2015
 * Time: 23:19
 */

class Teams
{
    public $teams;

    public function __construct()
    {
        $this->teams = Array();
    }

    function length()
    {
        return count($this->teams);
    }
    function add($elem)
    {
        $this->teams[] = $elem;
    }
    function toHtmlTable($tur)
    {

        echo "<table align='center''  border='1' style='width:100%'>";
        $var = 0;
        echo "<tr>";
        $count = 0;
        while($count <count($this->teams))
        {
            if($var < 5)
            {
                $a = $this->teams[$count]->hrefString($tur);
                echo "<td>$a</td>";
                $var++;
                $count++;
            }
            else
            {
                $var=0;
                echo"</tr>";
                echo "<tr>";
            }
        }
        echo"</tr>";
        echo"</table>";
    }
} 