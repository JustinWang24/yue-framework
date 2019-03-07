<?php
/**
 * Created by Justin.
 * This is a contract which describe how to handle a incoming request data
 * User: Justin
 * Date: 19/7/18
 * Time: 9:48 AM
 */

namespace App\core\contracts;


interface IRequest
{
    /**
     * To get the value by give key, whatever from _GET or _POST
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * Get all passed value from Request
     * @return mixed
     */
    public function all();
}