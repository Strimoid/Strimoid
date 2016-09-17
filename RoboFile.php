<?php // http://robo.li/

use Robo\Tasks;

class RoboFile extends Tasks
{
    function test()
    {
        return $this->taskCodecept()->run();
    }
}
