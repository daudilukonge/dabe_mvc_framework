<?php

    /**
     * 
     * 
     * This is header file
     * to set headers
     * 
     * 
     */
    // namespace
    namespace App\Middleware;


    /**
     * Headers class
     */

    class Headers {

        public static function applyHeaders() {
            header("Content-Type: application/json; charset=UTF-8");
            header("Access-Control-Allow-Methods: POST");
            header("Access-Control-Allow-Origin: https://sharenami.great-site.net");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Allow-Credentials: true");

            // Security headers
            header("X-Content-Type-Options: nosniff");
            header("X-Frame-Options: DENY");
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");


            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

                http_response_code(204);
                exit();
                
            }
        }

    }
