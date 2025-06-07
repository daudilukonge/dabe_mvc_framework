<?php

    /**
     * 
     * 
     * home controller file
     * 
     * 
    */

    // namespace
    namespace App\Controllers;

    // use basecontroller
    use App\Core\BaseController;
    use App\Components\ViewsComponent;

    // home controller class
    class HomeController extends BaseController {
        protected $data = [];
        public $pageName;
        public $cssFile;

        /**
         * constructor
         */
        public function __construct() {
            parent::__construct();
            // get view data
            $this->data = ViewsComponent::getViewData();
            $this->cssFile = 'landing.css';
        }

        // index function
        public function index() {
            $this->pageName = 'Home';

            $homeData = array_merge($this->data, [
                'pageName' => $this->pageName,
                'cssFile' => $this->cssFile,
            ]);
            // pass to view file
            $this->view('home', $homeData);
        } 
    }