<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/8/18
 * Time: 1:32 PM
 */

require_once 'vendor/autoload.php';
use Commando\Command;

//$hello_cmd->option()
//    ->require()
//    ->describedAs('A person\'s name');

class artisan extends Command
{
    protected $cmd;

    public function __construct(array $tokens = null)
    {
        parent::__construct($tokens);
        $this->cmd = new Command();
    }

    public function run(){
        $task = \App\core\console\CommandFactory::GetInstance($this->cmd);
        if($task)
            $task->go();
        else
            echo 'Can not find this command: '.$this->cmd[0].PHP_EOL;
    }
}

$artisan = new artisan();
$artisan->run();