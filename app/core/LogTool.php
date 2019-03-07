<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 10/8/18
 * Time: 2:30 PM
 */

namespace App\core;
use App\core\logger\CoreLogMailHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LogTool
{
    /**
     * @var Logger
     */
    private static $_LOGGER;

    private static function _getLogger(){
        if(!self::$_LOGGER){
            self::$_LOGGER = new Logger('my_logger');

            try{
                self::$_LOGGER->pushHandler(new CoreLogMailHandler(Logger::CRITICAL));

                self::$_LOGGER->pushHandler(
                    new StreamHandler(
                        env('APP_PATH').'storage'.DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR.'log-'.date('Y-m-d').'.log',
                        Logger::DEBUG)
                );

            }catch (\Exception $exception){
                self::$_LOGGER = null;
            }
        }
        return self::$_LOGGER;
    }

    public static function Info($content){
        if(self::_getLogger()){
            if(is_array($content)){
                $content = json_encode($content);
            }
            self::_getLogger()->info($content);
        }
    }
}