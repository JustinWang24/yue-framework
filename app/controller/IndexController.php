<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 14/8/18
 * Time: 9:49 AM
 */

namespace App\controller;
use App\core\BaseController;
use Klein\Request;
use Klein\Response;

class IndexController extends BaseController
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    /**
     * Homepage
     */
    public function homepage(){
        /**
         * Inject a variable to the view
         */
        $this->dataForView['pageTitle'] = env('APP_NAME');

        // Todo: Do something awesome

        /**
         * Load and render the view
         */
        $this->render(frontend_view('homepage'));
    }
}