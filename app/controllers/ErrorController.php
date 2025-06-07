<?php

    /**
     * 
     * 
     * this is error controller file
     * pass error to view file
     * to display the errors associating with routing
     * 
     * 
    */

    // namespace
    namespace App\Controllers;

    // use base controller
    use App\Core\BaseController;

    /**
     * 
     * error controller class
     * 
    */
    class ErrorController extends BaseController {
        public function showError($code) {
            $message = '';
            $heading = '';

            switch ($code) {
                case 401:
                    $heading = 'Unauthorized';
                    $message = "You are not authorized to access this page.";
                    break;
                case 403:
                    $heading = 'Forbidden';
                    $message = "You don't have permission to access this page.";
                    break;
                case 404:
                    $heading = 'Page Not Found';
                    $message = "The requested page does not exists.";
                    break;
                case 405:
                    $heading = 'Method Not Allowed';
                    $message = "The HTTP method is not allowed for this route.";
                    break;
                default:
                    $heading = 'Unexpected Error Occured';
                    $message = "Please try again later!";
                    break;
            }

            // pass data to the view
            $this->view('error', [ 
                'code' => $code,
                'message' => $message,
                'heading' => $heading
            ]);
        }
    }