<?php

    /** 
     * 
     * 
     * auth controller file
     * to handle user authentication
     * login, logout, and register
     * 
     * 
     */

    // namespace
    namespace App\Controllers;

    // use other files
    use App\Core\BaseController;
    use App\Core\Session;
    use App\Components\ViewsComponent;


    /** 
     * AuthController class
     */
    class AuthController extends BaseController {
        protected $data = [];
        public $pageName;
        public $pageDescription; 
        public $cssFile;

        
        /**
         * constructor
         */
        public function __construct() {

            parent::__construct();
            // start session
            Session::start();

            // get view data
            $this->data = ViewsComponent::getViewData();
            $this->pageDescription = 'Share files securely with other users';
            $this->cssFile = 'style.css';

        }




        /**
         * login view function
         */
        public function login() {
            $this->pageName = 'Login';

            $loginData = array_merge($this->data, [
                'pageName' => $this->pageName,
                'pageDescription' => $this->pageDescription,
                'cssFile' => $this->cssFile,
            ]);
            // direct to login page
            $this->view('login', $loginData);

        }


        /**
         * register view function
         */
        public function register() {
            $this->pageName = 'Register';

            $registerData = array_merge($this->data, [
                'pageName' => $this->pageName,
                'pageDescription' => $this->pageDescription,
                'cssFile' => $this->cssFile,
            ]);
            // direct to register page
            $this->view('register', $registerData);

        }
    }