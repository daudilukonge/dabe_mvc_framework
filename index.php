<?php
    session_start();
    date_default_timezone_set("Africa/Dar_es_Salaam");
    /**
     * 
     * 
     * this is the root file
     * 
     * 
    */
    // error 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // load autoload file and web file
    require_once "vendor/autoload.php";
    require_once "routes/web.php";

    // run the app
    $app->run();