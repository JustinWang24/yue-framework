<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/8/18
 * Time: 1:37 PM
 */

namespace App\core\console;


use Commando\Command;

class FrontendAssetManager implements ICommand
{
    /**
     * @var Command $command
     */
    protected $command;

    /**
     * @var array $option
     */
    protected $option = [];

    /**
     * @var array $arguments
     */
    protected $arguments = [];

    public function __construct(Command $command)
    {
        $this->command = $command;
    }


    public function parseOption($option)
    {
        // TODO: Implement parseOption() method.
    }

    public function parseArguments($arguments)
    {
        // TODO: Implement parseArguments() method.
    }

    /**
     * Command run: copy all theme's asset files (css, js, images, fonts) to the public_html folder
     */
    public function go()
    {
        // TODO: Implement go() method.
        $sourcePath = env('VIEW_PATH').'frontend'.DIRECTORY_SEPARATOR.env('THEME_NAME').DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

        $destinationPath = env('ROOT_PATH').DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.env('THEME_NAME').DIRECTORY_SEPARATOR;

        echo 'From: '.$sourcePath.PHP_EOL;
        echo 'To: '.$destinationPath.PHP_EOL;
        $result = copy_dir($sourcePath,$destinationPath);
        echo $result ? 'Done!'.PHP_EOL : 'Failed!'.PHP_EOL;
    }


}