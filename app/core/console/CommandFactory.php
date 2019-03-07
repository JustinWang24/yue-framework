<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/8/18
 * Time: 1:43 PM
 */

namespace App\core\console;
use Commando\Command;

class CommandFactory
{
    /**
     * @param Command $command
     * @return ICommand
     */
    public static function GetInstance(Command $command){

        $ClassName = null;

        switch ($command[0]){
            case 'assets':
                $ClassName = FrontendAssetManager::class;
                break;
            default:
                break;
        }

        return $ClassName ? new $ClassName($command) : null;
    }
}