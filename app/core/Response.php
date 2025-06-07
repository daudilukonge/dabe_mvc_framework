<?php

    /**
     * 
     * 
     * Response class for handling HTTP responses
     * 
     * 
    */

    // namespace
    namespace App\Core;

    class Response {
        // set status code function
        public function setStatusCode(int $code) {
            http_response_code($code); // set the status code
        }

        // redirect function
        public function redirect(string $url) {
            header("Location: $url"); // redirect to the uri
            exit; // exit the script
        }

        // send json response function
        public function json(array $data, int $code = 200) {

            $this->setStatusCode($code); // set the status code
            Header('Content-Type: application/json'); // set the content type to json
            echo json_encode($data); // encode the data to json and echo it
            exit; // exit the script

        }

        // send html response function
        public function html(string $html, int $code = 200) {

            $this->setStatusCode($code); // set the status code
            Header('Content-Type: text/html'); // set the content type to html
            echo $html; // echo the html
            exit; // exit the script
            
        }
    }