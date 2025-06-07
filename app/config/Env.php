<?php

    /**
     * 
     * 
     * 
     * this file loads the env variables from .env file
     * 
     * 
     * 
    */

    // namespace 
    namespace App\Config;

    use Dotenv\Dotenv;
 
    // env class
    class Env {
        public static function load($path) {
            // validate file
            if (!file_exists($path)) {
                echo '.env file not exists'; 
            }

            $dotenv = Dotenv::createImmutable($path); 
            $dotenv->load();         
        }
    }