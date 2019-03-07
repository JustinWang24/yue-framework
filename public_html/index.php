<?php
/**
 * Entry point of this application
 * User: Justin Wang
 * Date: 23/7/18
 * Time: 11:34 AM
 */

require_once __DIR__ . '/../vendor/autoload.php';
error_reporting(env('DEV_MODE',false) ? E_ERROR : 0);
ini_set('display_errors', env('DEV_MODE',false) ? true : false);

// Todo: Your own routes will be load first
\App\core\Route::Instance()->LoadFromTheme();

/**
 * Route: /  -> It's the entry point of the application, will render login and 3brands grid view
 */


$findHomePage = false;
foreach (\App\core\Route::Instance()->getPool() as $item) {
    if($item['path'] === '/'){
        $findHomePage = true;
        break;
    }
}
if(!$findHomePage){
    \App\core\Route::Instance()
        ->get('/',\App\controller\IndexController::class, 'homepage')
        ->name('homepage');
}

/**
 * Route: /user/logout  -> Show user's login form page
 */
//\App\core\Route::Instance()
//    ->get('/user/login',\App\controller\system\AuthController::class, 'login')
//    ->name('user.login');

/**
 * Route: /user/login  -> Authentication
 */
//\App\core\Route::Instance()
//    ->post('/user/login',\App\controller\system\AuthController::class, 'verify_user')
//    ->name('user.login.post');

/**
 * Route: /user/logout  -> Log the current user out safely
 */
//\App\core\Route::Instance()
//    ->get('/user/logout',\App\controller\system\AuthController::class, 'logout')
//    ->name('user.logout');

/**
 * Route: Handle reset password
 */
//\App\core\Route::Instance()
//    ->get('/user/reset-my-password',\App\controller\system\AuthController::class, 'reset_password')
//    ->name('user.reset.password');

/**
 * This is a must do action: dispatch at last
 */
\App\core\Route::Instance()->dispatch();
exit;