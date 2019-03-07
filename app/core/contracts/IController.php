<?php
/**
 * Describe how a controller should work at least
 * User: Justin
 * Date: 19/7/18
 * Time: 9:53 AM
 */

namespace App\core\contracts;


interface IController
{
    /**
     * The entry point of all controllers
     * @param IRequest $request
     * @param $additional
     * @return mixed
     */
    public function handle(IRequest $request, $additional);
}