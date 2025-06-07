<?php

    /**
     * 
     * 
     * This is Mailer file
     * responsible for setting and sending email to users
     * 
     * 
     */

    // namespace
    namespace App\Models;

    // use php mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    // use other app files
    use App\Core\Helpers;
    use App\Middleware\OTPGenerator;


    /**
     * Mailer class
     */
    class Mailer {

        /**
         * Function to send OTP
         */
        public static function sendOTP($toEmail, $username, $otp) {

            // email subject
            $subject = 'Email Verification - ShareNami';

            // email title and body
            $title = 'Email Verification OTP';
            $messageBody = "
                <p>Hello $username</p>
                <p>Thank you for Joining ShareNami, a great site for sharing unfiltered files and media</p>
                <p>Below is your One Time Password, Use it to verify your email</p>
                <p class='otp'>".$otp."</p>
                <p>Do not share this with ony person.</p>
                <p></p>
                <p>Enjoy using ShareNami</p>
            ";

            // send email
            return self::sendEmail($toEmail, $username, $title, $messageBody, $subject);
            
        }



        /**
         * Email to welcome user
         */
        public static function sendWelcomeNotification($toEmail, $username) {

            // email subject
            $subject = "Welcome to ShareNami";

            // email title and body
            $title = 'You have successfully registered ShareNami account';
            $messageBody = "
                <p>Hello $username</p>
                <p>Thank you for Joining ShareNami, a great site for sharing unfiltered files and media</p>
                <p>You can invite your loved ones, It's so easy.</p>
                <p>Log in again to your account, the go Settings > Invite Friends</p>
                <p>Booooom!</p>
                <p></p>
                <p>What are you waiting for? Start emjoying now!</p>
            ";


            // send email
            return self::sendEmail($toEmail, $username, $title, $messageBody, $subject);

        }


        /**
         * Email to notify admin about new user registration
         */
        public static function sendAdminRegisterNotification($toEmail, $username, $userData = []) {

            // email subject
            $subject = "New User Registration - ShareNami";

            // email title and body
            $title = "New User Registration Notification";
            $messageBody = "
                <p>Hello $username</p>
                <p>A new user has registered on ShareNami:</p>
                <p>User ID: {$userData['id']}</p>
                <p>Name: {$userData['name']}</p>
                <p>Email: {$userData['email']}</p>
                <p>Account Status: {$userData['status']}</p>
                <p>Role: {$userData['role']}</p>
                <p>Registered On: {$userData['created_at']}</p>
            ";

            // send email
            return self::sendEmail($toEmail, $username, $title, $messageBody, $subject);
        }


        /**
         * Send email to admin about errors
         */
        public static function sendAdminErrorNotification($toEmail, $username, $errorMessage, $userData = []) {

            // email subject
            $subject = "Error Notification - ShareNami";

            // email title and body
            $title = "Error Notification";
            $messageBody = "
                <p>Hello $username</p>
                <p>An error occurred in the application:</p>
                <p>Error Message: $errorMessage</p>
                <p>User Data:</p>
                <ul>
                    <li>ID: {$userData['id']}</li>
                    <li>Name: {$userData['name']}</li>
                    <li>Email: {$userData['email']}</li>
                    <li>Status: {$userData['status']}</li>
                    <li>Role: {$userData['role']}</li>
                    <li>Created At: {$userData['created_at']}</li>
                </ul>
            ";

            // send email
            return self::sendEmail($toEmail, $username, $title, $messageBody, $subject);
            
        }



        /**
         * Function to send welcome email notification
         */
        private static function sendEmail($toEmail = null, $username = null, $title = null, $messageBody = null, $subject = null) {

            $mail = new PHPMailer(true);

            // check if variables are available
            if (!isset($_ENV["MAIL_HOST"], $_ENV["MAIL_PORT"], $_ENV["MAIL_USERNAME"], $_ENV["MAIL_PASSWORD"], $_ENV["MAIL_FROM_NAME"], $_ENV["MAIL_FROM_EMAIL"])) {
                
                // NO CREDENTIALS
                return [
                    'success' => false,
                    'error' => 'Credentials not found'
                ];

            }



            // try creating email
            try {

                $mail->isSMTP();
                $mail->Host = $_ENV['MAIL_HOST'];
                $mail->SMTPAuth = true;
                $mail->Username = $_ENV['MAIL_USERNAME'];
                $mail->Password = $_ENV['MAIL_PASSWORD'];
                $mail->SMTPSecure = 'tls';
                $mail->Port = $_ENV['MAIL_PORT'];

                // set where email comes from
                $mail->setFrom($_ENV["MAIL_FROM_EMAIL"], $_ENV['MAIL_FROM_NAME']);

                // email to send
                $mail->addAddress($toEmail);

                // use HTML tags
                $mail->isHTML(true);

                $mail->Subject = $subject;

                $mail->Body = self::buildEmailTemplate($title, $messageBody);

                // check if email was sent
                if ($mail->send()) {

                    return [
                        "success"=> true,
                        'message' => 'Welcome Email was sent successfully'
                    ];

                } else {

                    return [
                        'success'=> false,
                        'error'=> 'Failed to send Welcome email'
                    ];

                }


            } catch(Exception $e) {

                return [
                    'success' => false,
                    'message' => 'Failed to send email',
                    'error' => $e->getMessage()
                ];

            }
        }



        /**
         * Email template function
         */
        public static function buildEmailTemplate($title, $messageBody) {

            $helpers = new Helpers();
            $logo = $helpers->asset('logo.png');

            return '
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 0;
                        }
                        .container {
                            max-width: 600px;
                            margin: 30px auto;
                            background: #ffffff;
                            border-radius: 8px;
                            box-shadow: 0 0 10px rgba(0,0,0,0.1);
                            padding: 30px;
                            font-size: 16px;
                        }
                        .site-logo {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 10px;
                            font-size: 24px;
                            font-weight: 700;
                            color: #4a6bff;
                        }

                        .site-logo img {
                            width: 30px;
                        }
                        .otp img {
                            text-align: center;
                            font-weight: bold;
                            font-size: 20px;
                        }
                        .header {
                            border-bottom: 1px solid #eeeeee;
                            padding-bottom: 10px;
                            margin-bottom: 20px;
                        }
                        .header h2 {
                            margin: 0;
                            color: #333333;
                        }
                        .content {
                            font-size: 16px;
                            color: #333333;
                        }
                        .footer {
                            border-top: 1px solid #eeeeee;
                            margin-top: 30px;
                            padding-top: 10px;
                            font-size: 13px;
                            color: #888888;
                            text-align: center;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="site-logo">
                            <span>ShareNami</span>
                        </div>
                        <div class="header">
                            <h2>' . htmlspecialchars($title) . '</h2>
                        </div>
                        <div class="content">
                            ' . $messageBody . '
                        </div>
                        <div class="footer">
                            &copy; ' . date('Y') . ' ShareNami. All rights reserved. By DabeTech
                        </div>
                    </div>
                </body>
                </html>
            ';
        }

    }