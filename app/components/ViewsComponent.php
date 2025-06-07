<?php

    /**
     * 
     * 
     * this is the views component file
     * to keep the data for the views
     * 
     * 
    */

    // namespace
    namespace App\Components;

    // use app files
    use App\Core\Session;
    use App\Core\Helpers;

    /**
     * * ViewsComponent class
     */
    class ViewsComponent {
        /**
         * * constructor
         */
        public function __construct() {
            // start session
            Session::start();
        }
        

        /**
         * function to get the view data
         */
        public static function getViewData() {

            return [
                'siteName' => 'ShareNami',
                'sitePreview' => "Secure File Sharing Platform",
                'ownerName' => 'DabeTech',
                'helpers' => Helpers::class
            ];
            
        }
    }