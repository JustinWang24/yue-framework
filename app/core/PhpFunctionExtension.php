<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 19/7/18
 * Time: 3:19 PM
 */

namespace App\core;

use Twig_SimpleFunction;
use BadFunctionCallException;

class PhpFunctionExtension extends \Twig_Extension
{
    private $functions = array(
        'uniqid',
        'floor',
        'ceil',
        'addslashes',
        'chr',
        'chunk_​split',
        'convert_​uudecode',
        'crc32',
        'crypt',
        'hex2bin',
        'md5',
        'sha1',
        'strpos',
        'strrpos',
        'ucwords',
        'wordwrap',
        'gettype',
        'empty',
        'count',
        'date',
        'json_encode',
        /**
         * Self defined functions
         */
        'asset',                // Give the asset url
        'url',                  // Give the route url
        'session_get',          // Retrieve data from session
        'session_flash',        // Retrieve data from session flash
        'gaga_indicator_init',  // Retrieve data from session flash
        'ordinal',              // Add suffix to number
        'env',                  // output any env setting
        'number_format',        // format number
        'empty',                // empty
        'get_route',            // get url by give route name
        'frontend_layout',      // load layout according to current theme
        'frontend_view',        // load view according to current theme
        'carbon_format',        // use Carbon to format date
    );
    public function __construct(array $functions = array())
    {
        if ($functions) {
            $this->allowFunctions($functions);
        }
    }
    public function getFunctions()
    {
        $twigFunctions = array();
        foreach ($this->functions as $function) {
            $twigFunctions[] = new Twig_SimpleFunction($function, $function);
        }
        return $twigFunctions;
    }
    public function allowFunction($function)
    {
        $this->functions[] = $function;
    }
    public function allowFunctions(array $functions)
    {
        $this->functions = $functions;
    }
    public function getName()
    {
        return 'php_function';
    }
}