<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 24/7/18
 * Time: 10:54 AM
 */

namespace App\core\contracts\support;


interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}