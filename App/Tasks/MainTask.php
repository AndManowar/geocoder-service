<?php
/********************************
 * Created by GoldenEye.
 * copyright 2010 - 2018
 ********************************/

use Phalcon\Cli\Task;

/**
 * Class MainTask
 *
 * @package App\Tasks
 * @author Alexey Yermakov slims.alex@gmail.com
 */
class MainTask extends Task
{
    public function mainAction()
    {
        echo "Allow actions:" . PHP_EOL .
            "php console.php import data" . PHP_EOL .
            "php console.php import types" . PHP_EOL .
            "php console.php parent-data build" . PHP_EOL;
    }

}