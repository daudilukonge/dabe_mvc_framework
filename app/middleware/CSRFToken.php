<?php

    /**
     * 
     * 
     * CSRF Token Middleware
     * to handle CSRF token generation and validation
     * 
     * This middleware will ensure that all forms submitted to the application
     * include a valid CSRF token, protecting against cross-site request forgery attacks.
     * 
     * 
     */

     // namespace
     namespace App\Middleware;

     /**
      * CSRFToken class
      */
      class CSRFToken {
        /**
         * CSRFToken constructor.
         */
        public function __construct() {}


        /**
         * Function to generate CSRF token
         */
        public static function generateCsrfToken() {

            // check if token is available
            if (!isset($_SESSION['csrf_token'])) {
                // generate token
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }

            // return token
            return $_SESSION['csrf_token'];

        }


        /**
         * Function to validate CSRF token
         */
        public static function validateCsrfToken($token) {

            // check if token is valid
            return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
            
        }

      }