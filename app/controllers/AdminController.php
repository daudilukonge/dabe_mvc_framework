<?php

    /**
     * 
     * 
     * Admin Controller file
     * 
     * 
     */

    // namespace
    namespace App\Controllers;


    // use app files
    use App\Core\BaseController;
    use App\Controllers\ErrorController;
    use App\Components\ViewsComponent;


    /**
     * Admin Controller class
     */
    class AdminController extends BaseController {

        protected $data = [];
        public $pageName;
        public $cssFile;

        /**
         * construct
         */
        public function __construct() {

            // parent construct
            parent::__construct();

            $this->data = ViewsComponent::getViewData(); // get view data
            $this->cssFile = 'style.css'; // set css file

        }



        /**
         * Index method
         */
        public function index() {

            $this->pageName = 'Admin Dashboard'; // set page name

            if ($this->request->isGet()) {

                // merge data from components and this page data
                $adminData = array_merge($this->data, [
                    'pageName' => $this->pageName,
                    'cssFile' => $this->cssFile,
                    'User' => $this->user,
                    'users' => $this->user->all()
                ]);

                // render the view
                $this->view('admin', $adminData);

            } else {

                // redirect 
                $error = new ErrorController();
                $error->showError(405); // method not allowed

            }
        }
    }