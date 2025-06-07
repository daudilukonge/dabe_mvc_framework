<?php

    /**
     * 
     * 
     * Helper functions for the application
     * 
     * 
    */

    // namespace
    namespace App\Core;

    /**
     * Helper class
    */
    class Helpers {

        /**
         * Function to create base url helper
        */
        private function url($path = '') {

            // detect the protocol
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';

            // host name
            $host = $_SERVER['HTTP_HOST'];

            // base folder - if app is in a subfolder
            $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
            $basePath = rtrim($scriptName, '/');

            // final url
            return $protocol . $host . $basePath . '/' . ltrim($path, '/');

        }


        /**
         * Function to create url for assets
        */
        public static function asset($path = '') {

            // create url
            $assetPath = "assets";
            return (new self())->url("$assetPath/$path");

        }

        /**
         * Function to create url for routes
        */
        public static function route($path = '') {

            // create url
            return (new self())->url($path);

        }

        /**
         * Function to create url for redirect
        */
        public static function redirect($path = '') {

            // create url
            header('Location: ' . (new self())->url($path));
            exit();
            
        }
    }