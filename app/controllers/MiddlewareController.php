<?php

    /**
     * 
     * 
     * This is middleware controller file
     * handles all contoller functions necessary for middleware (security) files
     * 
     * 
     */

    // namespace
    namespace App\Controllers;

    // use app files
    use App\Core\BaseController;
    use App\Components\ViewsComponent;


    /**
     * Middleware conrtroller class
     */
    class MiddlewareController extends BaseController {
        protected $data = [];
        public $pageName;
        public $pageDescription;
        public $cssFile;
        protected $userData;

        /**
         * contructor
         */
        public function __construct() {

            parent::__construct();

            // get data
            $this->data = ViewsComponent::getViewData();

            $this->pageDescription = 'Verify your email';
            $this->cssFile = 'verify.css';

        }


        /**
         * Function to view email verification page
         */
        public function emailVerification() {

            // view file data
            $this->pageName = "Email Verification";

            $pageData = array_merge($this->data, [
                'pageName' => $this->pageName,
                'pageDescription' => $this->pageDescription,
                'cssFile' => $this->cssFile,
                'User' => $this->user
            ]);

            $this->view('verification', $pageData);

        }

    }