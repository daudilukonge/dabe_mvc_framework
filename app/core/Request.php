<?php

    /**
     * 
     * 
     * Request class for handling HTTP requests
     * and parsing request data
     * 
     * 
    */

    // namespace
    namespace App\Core;

    class Request {
        // method function
        public function getMethod() {
            return $_SERVER['REQUEST_METHOD'];
        }

        // uri function
        public function getURI() {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            return parse_url($uri, PHP_URL_PATH);
        }

        // input function
        public function input($key, $default = null) {
            return $_POST[$key] ?? $_GET[$key] ?? $default;
        }

        // all function 
        public function all() {
            return array_merge($_POST, $_GET);
        }

        // chech get method function 
        public function isGet() {
            return $this->getMethod() === 'GET'; 
        }

        // check post method function
        public function isPost() {
            return $this->getMethod() === 'POST';
        }

        // file handling function
        public function file($fileName) {
            return $_FILES[$fileName] ?? null;
        }
 
        // json input handling function
        public function jsonInput() {
            $input = file_get_contents('php://input');
            return json_decode($input, true) ?? [];

            /**
             * this function can be used like this
             * $data = $this->request->jsonInput(); // assuming that request is an instance of Request class and request object is created in the controller
             * $username = $data['username'] ?? null;
            */
        }

        // check if request is for api
        public function isRequestAPI() {

            return strpos($this->getURI(), '/api') === 0; // check if uri starts with /api
            
        }
    }