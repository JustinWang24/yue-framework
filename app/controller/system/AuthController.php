<?php
/**
 * To log user in and out
 * Created by Justin Wang.
 * User: justinwang
 * Date: 14/8/18
 * Time: 10:06 AM
 */

namespace App\controller\system;
use App\core\BaseController;
use Klein\Request;
use Klein\Response;

class AuthController extends BaseController
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }

    public function login(){

    }

    public function verify_user(){

    }

    public function reset_password(){

    }

    public function logout(){

    }
}