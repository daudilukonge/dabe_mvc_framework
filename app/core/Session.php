<?php

    /**
     * 
     * 
     * Session file
     * to handle session management
     * one time flash messages
     * and user authentication support
     * 
     * 
    */

    // namespace 
    namespace App\Core;

    // use app files
    use App\Middleware\CSRFToken;

    /**
     * Session class
    */
    class Session {

        /**
         * 
         * 
         * Function to start session
         * genefrate CSRF token
         * promote '_flash' to 'flash'
         * 
         * 
         */
        public static function start() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            } 
            
            // generate CSRF token if not set
            CSRFToken::generateCsrfToken();


            // Promote '_flash' to 'flash' and clear '_flash' only if the flash is not set
            if (isset($_SESSION['_flash']) && !isset($_SESSION['flash'])) {
                $_SESSION['flash'] = $_SESSION['_flash'];
                unset($_SESSION['_flash']);
            }

            // ensure 'flash' is an array to avoid errors
            if (!isset($_SESSION['flash'])) {
                $_SESSION['flash'] = [];
            }
        }

        
        public static function set($key, $value) {
            $_SESSION[$key] = $value;
        }

        
        public static function get($key, $default = null) {
            self::start();
            return $_SESSION[$key] ?? $default;
        }

        public static function has($key) {
            self::start();
            return isset($_SESSION[$key]);
        }

        
        public static function remove($key) {
            self::start();
            unset($_SESSION[$key]);
        }

        
        public static function destroy() {
            self::start();
            $_SESSION = [];
            session_destroy();
        }

        
        public static function regenerate() {
            self::start();
            session_regenerate_id(true);
        }

        public function getCsrfToken() {
            self::start();
            return $_SESSION['csrf_token'] ?? null;
        }


        /**
         * 
         * 
         * functions to set a flash message
         * and get a flash message
         * to check if flash message exists
         * flash function for views
         * 
         * 
         */
        public static function setFlash($key, $message) {
            self::start();
            $_SESSION['_flash'][$key] = $message;
        }

        
        public static function getFlash($key) {
            if (isset($_SESSION['_flash'][$key])) {
                $message = $_SESSION['_flash'][$key];
                unset($_SESSION['_flash'][$key]);
                return $message;
            }
            return null;
        }

        public static function flash($key) {
            $message = self::getFlash($key);
            if ($message) {
                return '<div class="flash-message flash-' . $key . '">' . htmlspecialchars($message) . '</div>';
            }
            return '';
        }


        /**
         * 
         * 
         * function to support user authentication
         * check if user is logged in
         * get user data
         * logout user
         * 
         * 
         */
        public static function isLoggedIn() {
            return self::has('user');
        }


        public static function getUser() {
            return self::get('user');
        }


        public static function logout() {
            self::destroy();
        }
    }