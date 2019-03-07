<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 24/7/18
 * Time: 10:53 AM
 */

namespace App\core\contracts\support;


interface Jsonable
{
    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);
}