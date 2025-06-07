<?php

    /**
     * 
     * 
     * This is OTP generation file
     * 
     * 
     */

    // namespace
    namespace App\Middleware;


    /**
     * OTP generator class
     */
    class OTPGenerator {

        /**
         * Function to generate OTP
         */
        public static function generateOTP($length = 6) {

            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= mt_rand(0, 9);
            }
            return $otp;
            
        }

    }