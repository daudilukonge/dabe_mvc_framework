<?php

    /**
     * 
     * 
     * this is a base controller file
     * this file is used to render the view
     * 
     * 
    */

    // namespace
    namespace App\Core;

    use App\Core\Request;
    use App\Core\Response;
    use App\Middleware\Headers;
    use App\Models\User;


    /**
     * Basecontroller class
     */
    class BaseController {

        protected $request;
        protected $response;
        protected $user;

        public function __construct() {
            
            $this->request = new Request();
            $this->response = new Response();
            $this->user = new User();

            // headers
            // Headers::applyHeaders();
            
        }
 
        protected function redirect($url) {
            // redirect to the url 
            $this->response->redirect($url);
        }


        /**
         * Function to get user data
         */
        protected function getUserData($email) {

            return $this->user->getUserData($email);

        }
        
        // function to view
        protected function view($view, $data = []) {
            // extract data
            extract($data);
            // require view file
            $viewPath = __DIR__ . '/../views/' . $view . '.php';

            if (file_exists($viewPath)) {
                require_once $viewPath; // require view file
            } else {
                // view file not found, abort
                http_response_code(404); // not found
                die('View file not found'); // die with message
            }
        }

    }