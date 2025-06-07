<?php

    /**
     * 
     * 
     * this is the web file
     * for adding routes
     * adding allowed http methods
     * setting Error Controller
     * and load environment variables
     * 
     * 
    */

    // namespace
    namespace App\Routes;
    
    // use app files
    use App\Api\Authentication;
    use App\Api\Authorization;
    use App\Api\Settings;
    use App\Controllers\AdminController;
    use App\Controllers\MiddlewareController;
    use App\Core\App;
    use App\Controllers\HomeController;
    use App\Controllers\ErrorController;
    use App\Controllers\UserController;
    use App\Controllers\AuthController;
    use App\Config\Env;
    use App\Core\Session;

    // start session
    Session::start();

    // create new app
    $app = new App; 

    // load environment variable
    Env::load(__DIR__ . '/../');

    // add allowed http methods
    $app->addAllowedHTTPMethods([
        'put',
        'delete',
        'patch',
    ]);

    // set error controller
    $app->setErrorController(ErrorController::class, 'showError'); 

    // DEFINE THE ROUTES
    // home controller routes
    $app->addRoute('GET', '/', HomeController::class, 'index');
    $app->addRoute('GET', '/home', HomeController::class, 'index');

    // user controller routes
    $app->addRoute('get', '/users/users-list', UserController::class, 'listUsers');
    $app->addRoute('get', '/user/conversations', UserController::class, 'conversations');
    $app->addRoute('get', '/user/settings', UserController::class, 'userSettings');
    $app->addRoute('get', '/user/chat', UserController::class, 'chatWithUser');

    // auth controller routes
    $app->addRoute('get', '/login', AuthController::class, 'login');
    $app->addRoute('get', '/register', AuthController::class, 'register'); 

    // middleware controller routes
    $app->addRoute('get', '/user/register/otp-verification-page', MiddlewareController::class, 'emailVerification');

    // admin controller routes
    $app->addRoute('get', '/admin', AdminController::class, 'index');

    // api routes
    $app->addRoute('post', '/api/users/login', Authentication::class, 'loginUser');
    $app->addRoute('post', '/api/users/logout', Authentication::class, 'logoutUser');
    $app->addRoute('post', '/api/users/register', Authentication::class, 'registerUser');
    $app->addRoute('post', '/api/users/register/otp-verification', Authentication::class, 'confirmEmailOTP');
    $app->addRoute('post', '/api/users/settings/change-profile-picture', Settings::class, 'changeProfile');
    $app->addRoute('post', '/api/user/user-active-status', Authorization::class, 'userActiveStatus');


    // return app instance
    return $app;