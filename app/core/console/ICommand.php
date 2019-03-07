<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/8/18
 * Time: 1:39 PM
 */

namespace App\core\console;


interface ICommand
{
    public function parseOption($option);

    public function parseArguments($arguments);

    public function go();
}