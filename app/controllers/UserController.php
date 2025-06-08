<?php

    /**
     * 
     * 
     * User controller file
     * direct user to the view files ONLY
     * 
     * 
    */

    // namespace
    namespace App\Controllers;

    // use user and base controller
    use App\Core\BaseController;
    use App\Controllers\ErrorController;
    use App\Components\ViewsComponent;

    /**
     * UserController class
     */
    class UserController extends BaseController {
        protected $data = [];
        public $pageName;
        public $cssFile;

        /**
         * Constrctor function
         */
        public function __construct() {

            // call parent constructor
            parent::__construct();

            $this->data = ViewsComponent::getViewData(); // get view data 
            $this->cssFile = 'style.css'; // set css file
            
        }


        /**
         * user conversations function
         */
        public function conversations() {

            $this->pageName = 'Conversations'; // set page name

            if ($this->request->isGet()) {

                // merge data from components and this page data
                $convoData = array_merge($this->data, [
                    'pageName' => $this->pageName,
                    'cssFile' => $this->cssFile,
                    'User' => $this->user
                ]);

                // display conversation view
                $this->view('conversations', $convoData);
                

            } else {
                // redirect 
                $error = new ErrorController();
                $error->showError(405); // method not allowed
            }

        }
        


        /**
         * user settings function
         */
        public function userSettings() {

            $this->pageName = 'Settings'; // set page name

            if ($this->request->isGet()) {

                // merge data from components and this page data
                $settingsData = array_merge($this->data, [
                    'pageName' => $this->pageName,
                    'cssFile' => $this->cssFile,
                    'User' => $this->user
                ]);

                // display settings view
                $this->view('settings', $settingsData);

            } else {

                // redirect 
                $error = new ErrorController();
                $error->showError(405); // method not allowed
                
            }
        }



        /**
         * Chat with user function
         */
        public function chatWithUser() {

            $this->pageName = 'Chat'; // set page name

            if ($this->request->isGet()) {

                // merge data from components and this page data
                $chatData = array_merge($this->data, [
                    'pageName' => $this->pageName,
                    'cssFile' => $this->cssFile,
                    'User' => $this->user
                ]);

                // display chat view
                $this->view('chat', $chatData);

            } else {

                // redirect 
                $error = new ErrorController();
                $error->showError(405); // method not allowed
                
            }
        }



        /**
         * List all users
         */
        public function listUsers() {
            
            $this->pageName = 'Users List'; // set page name

            // get users list
            $users = $this->user->all(); 

            if ($this->request->isGet()) {

                // merge data from components and this page data
                $usersData = array_merge($this->data, [
                    'pageName' => $this->pageName,
                    'cssFile' => $this->cssFile,
                    'User' => $this->user,
                    'users' => $users
                ]);

                // display users view
                $this->view('users-list', $usersData);

            } else {

                // redirect 
                $error = new ErrorController();
                $error->showError(405); // method not allowed
                
            }

        }
    }