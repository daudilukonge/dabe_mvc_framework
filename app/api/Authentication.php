<?php

    /**
     * 
     * 
     * This is api authentication file
     * responsible for user authentication (login + register)
     * 
     * 
     */

     // namespace 
     namespace App\Api;


     // use app files
     use App\Core\BaseController;
     use App\Core\Session;
     use App\Middleware\CSRFToken;
     use App\Models\Mailer;
     use App\Middleware\OTPGenerator;
     use App\Middleware\JWTToken;


     /**
      * Authentication api class
      */
      class Authentication extends BaseController {

        private $token;


        /**
         * construct function
         */
        public function __construct() {

            // parent construct
            parent::__construct();

            // start session
            Session::start();

            // new objects of classes
            $this->token = new JWTToken() ;

        }



        /**
         * Function to register user
         */
        public function registerUser() {

            // check api route was used 
            if (!$this->request->isRequestAPI()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'Bad routing request'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // check http method
            if (!$this->request->isPost()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'The HTTP method is not allowed for this route.'
                ];
                // send json response
                $this->response->json($jsonData, 405);

            }


            // get headers
            $headers = getallheaders();

            // get csrf token
            $csrfHeader = $headers["X-CSRF-Token"] ?? '';
            $csrf_token = stripos($csrfHeader, 'Bearer ') === 0 ? substr($csrfHeader,7) : $csrfHeader;

            // validate csrf token
            if (!isset($csrf_token) || !CSRFToken::validateCsrfToken($csrf_token)) {

                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'You are not authorized to access this page.'
                ];
                // send json response
                $this->response->json($jsonData, 401);

            }

            // get json data
            $data = $this->request->jsonInput();
            $username = $data['formObject']['name'] ?? null;
            $email = $data['formObject']['email'] ?? null;
            $password = $data['formObject']['password'] ?? null;

            // validate data
            [$validateStatus, $validateMessage] = $this->user->validateRegisterData($username, $email, $password);
            if ($validateStatus === false) {

                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $validateMessage
                ];
                // send json response
                $this->response->json($jsonData, 404);

            }


            // register user
            [$registerStatus, $registerMessage] = $this->user->register($username, $email, $password);
            if ($registerStatus === false) {

                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $registerMessage
                ];
                // send json response
                $this->response->json($jsonData, 406);

            }


            // generate otp and send to the user for email verification
            $otp = OTPGenerator::generateOTP();
            Mailer::sendOTP($email, $username, $otp);

            // store otp to the database
            $this->user->storeOTP($email, $otp, "Registered", 10);

            // regenerate session id
            Session::regenerate();

            // set session data
            Session::set('user', [
                'email' => $email,
                'name' => $username,
                'role' => 'user',
                'otp_reason' => 'Registered'
            ]);

            
            // json response
            $jsonData = [
                'status' => 1,
                'error' => false,
                'message' => $registerMessage
            ];
            // send json response
            $this->response->json($jsonData, 200);
            
        }





        /**
         * Function to login user
         */
        public function loginUser() {

            // check api route was used 
            if (!$this->request->isRequestAPI()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'Bad routing request'
                ];
                // send json response
                $this->response->json($jsonData, 400);
                exit;

            }


            // check http method
            if (!$this->request->isPost()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'The HTTP method is not allowed for this route.'
                ];
                // send json response
                $this->response->json($jsonData, 405);
                exit;

            }


            // get headers
            $headers = getallheaders();

            // get csrf token
            $csrfHeader = $headers["X-CSRF-Token"] ?? '';
            $csrf_token = stripos($csrfHeader, 'Bearer ') === 0 ? substr($csrfHeader,7) : $csrfHeader;

            // validate csrf token
            if (!isset($csrf_token) || !CSRFToken::validateCsrfToken($csrf_token)) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'You are not authorized to access this page.',
                ];
                // send json response
                $this->response->json($jsonData, 401);
                exit;

            }

            // get json data
            $data = $this->request->jsonInput();
            $email = $data['formObject']['email'] ?? null;
            $password = $data['formObject']['password'] ?? null;


            // validate user email
            [$validateStatus, $validateMessage] = $this->user->validateLoginData($email, $password);
            if ($validateStatus === false) {

                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $validateMessage
                ];
                // send json response
                $this->response->json($jsonData, 404);
                exit;

            }


            // check if user exists
            if ($this->user->emailExists($email) === false) {

                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'Invalid Email or Password',
                    'email' => $email
                ];
                // send json response
                $this->response->json($jsonData, 401);
                exit;

                // Later the user failed attempts to login will be logged and recorded based on the ip address and email address
                // and the user will be blocked for a certain period of time
                // if the user fails to login more than 5 times
                // within  1 hour
                
            }


            // login user
            [$loginStatus, $loginInfo] = $this->user->login($email, $password); 

            if ($loginStatus === false) {
 
                // destroy session
                Session::destroy();

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $loginInfo
                ];
                // send json response
                $this->response->json($jsonData, 401);
                exit;

                // Later the user failed attempts to login will be logged and recorded based on the email address
                // to avoid brute force attacks
                // and the user will be blocked for a certain period of time
                // if the user fails to login more than 5 times
                // within  1 hour

            }


            // update user active status to online
            $activeStatus = 1;
            $updateActiveStatus = $this->user->updateUserActiveStatus($email, $activeStatus);

            if ($updateActiveStatus === false) {

                // send an error email to admin
                // indicating that user has successful logged in
                // but failed to update the user active status to online

            }


            // regenerate session id
            Session::regenerate();

            // set session data
            Session::set('user', [
                'id' => $loginInfo['id'],
                'email' => $email,
                'name' => $loginInfo['name'],
                'role' => $loginInfo['role'],
                'account_status' => $loginInfo['status'],
                'profile_picture' => $loginInfo['profile_image'],
                'otp_reason' => '',
                'active_status' => $activeStatus
            ]);
            

            // user data
            $userData = [
                'email' => $loginInfo['email'],
                'id' => $loginInfo['id'],
                'name' => $loginInfo['name'],
                'role' => $loginInfo['role'],
            ];

            // generating access and refresh tokens
            $accessToken = $this->token->generateJwtToken($userData, 3600);
            $refreshToken = $this->token->generateRefreshToken($userData, 86400);

            // get user data from tokens
            $access_token_userData = $this->token->getUserDataFromToken($accessToken);

            // json response
            $jsonData = [
                'status' => 1,
                'error' => false,
                'message' => 'Welcome ' . $loginInfo['name'] . '.',
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user_data' => $access_token_userData,
            ];
            // send json data
            $this->response->json($jsonData, 200);

        }





        /**
         * Function to confirm user email using OTP
         */
        public function confirmEmailOTP() {

            // check api route was used 
            if (!$this->request->isRequestAPI()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'Bad routing request'
                ];
                // send json response
                $this->response->json($jsonData, 400);
                exit;

            }


            // check http method
            if (!$this->request->isPost()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'The HTTP method is not allowed for this route.'
                ];
                // send json response
                $this->response->json($jsonData, 405);
                exit;

            }


            // get headers
            $headers = getallheaders();

            // get csrf token
            $csrfHeader = $headers["X-CSRF-Token"] ?? '';
            $csrf_token = stripos($csrfHeader, 'Bearer ') === 0 ? substr($csrfHeader,7) : $csrfHeader;

            // validate csrf token
            if (!isset($csrf_token) || !CSRFToken::validateCsrfToken($csrf_token)) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'You are not authorized to access this page.',
                ];
                // send json response
                $this->response->json($jsonData, 401);
                exit;

            }


            // get data
            $data = $this->request->jsonInput();
            $otp = $data['formObject']['user_otp'] ?? '';
            $email = $data['formObject']['email'] ?? '';
            $otp_reason = $data['formObject']['otp_reason'] ?? '';

            // validate data
            [$validateStatus, $validateMessage] = $this->user->validateOTPData($email, $otp, $otp_reason);
            if ($validateStatus === false) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $validateMessage
                ];
                // send json response
                $this->response->json($jsonData, 404);
                exit;

            }


            // get otp data from database
            [$otpStatus, $otpResponse] = $this->user->getOTP($email, $otp_reason);
            if ($otpStatus === false) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $otpResponse
                ];
                // send json response
                $this->response->json($jsonData, 404);
                exit;

            }


            // get otp value 
            $otpValue = $otpResponse['otp_code'];
            $expireTime = $otpResponse['expires_at'];

            // check if otp is expired
            if (strtotime($expireTime) < time()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'OTP is expired. Please try again.'
                ];
                // send json response
                $this->response->json($jsonData, 400);
                exit;

            }

            // check if otp is valid
            if ($otpValue !== $otp) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => 'Invalid OTP. Please try again.'
                ];
                // send json response
                $this->response->json($jsonData, 400);
                exit;

            }


            // get user data
            [$userStatus, $userInfo] = $this->user->getUserData($email);
            if ($userStatus === false) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'message' => $userInfo
                ];
                // send json response
                $this->response->json($jsonData, 404);
                exit;

            }


            // otp is valid, delete otp from database
            $deleteOtpStatus = $this->user->deleteOTP($email, $otp_reason);
            if (!$deleteOtpStatus) {

                $errorMessage = 'Failed to delete OTP from database.';

                // send email notification to admin
                Mailer::sendAdminErrorNotification('daudilukonge@gmail.com', 'ShareNami - Admin', $errorMessage, [
                    'id' => $userInfo['id'],
                    'name' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'status' => $userInfo['status'],
                    'role' => $userInfo['role'],
                    'created_at' => $userInfo['created_at']
                ]);

            }


            // update user account to verified
            $updateUserStatus = $this->user->updateUserStatus($email, 'Verified');
            if (!$updateUserStatus) {

                $errorMessage = 'Failed to update user account status to verified.';

                // send email notification to admin
                Mailer::sendAdminErrorNotification('daudilukonge@gmail.com', 'ShareNami - Admin', $errorMessage, [
                    'id' => $userInfo['id'],
                    'name' => $userInfo['name'],
                    'email' => $userInfo['email'],
                    'status' => $userInfo['status'],
                    'role' => $userInfo['role'],
                    'created_at' => $userInfo['created_at']
                ]);

            }


            // send user welcome email
            Mailer::sendWelcomeNotification($email, $userInfo['name']);

            // regenerate session id
            Session::regenerate();

            // set session data
            Session::set('user', [
                'id' => $userInfo['id'],
                'email' => $email,
                'name' => $userInfo['name'],
                'role' => $userInfo['role'],
                'account_status' => $userInfo['status'],
                'profile_picture' => $userInfo['profile_image'],
                'otp_reason' => ''
            ]);

            // user data
            $userData = [
                'email' => $userInfo['email'],
                'id' => $userInfo['id'],
                'name' => $userInfo['name'],
                'role' => $userInfo['role'],
            ];

            // send admin notification of user registration
            Mailer::sendAdminRegisterNotification('daudilukonge@gmail.com', 'DabeTech - Admin', [
                'id' => $userInfo['id'],
                'name' => $userInfo['name'],
                'email' => $userInfo['email'],
                'status' => $userInfo['status'],
                'role' => $userInfo['role'],
                'created_at' => $userInfo['created_at']
            ]);

            // generating access and refresh tokens
            $accessToken = $this->token->generateJwtToken($userData, 3600);
            $refreshToken = $this->token->generateRefreshToken($userData, 86400); 

            // get user data from tokens
            $access_token_userData = $this->token->getUserDataFromToken($accessToken);

            // json response
            $jsonData = [
                'status' => 1,
                'error' => false,
                'message' => 'Welcome ' . $userInfo['name'] . '.',
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user_data' => $access_token_userData,
            ];

            // send json data
            $this->response->json($jsonData, 200);

        }



        /**
         * Function to logout user
         */
        public function logoutUser() {

            // check if api route was used 
            if (!$this->request->isRequestAPI()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'Redirect',
                    'message' => 'Bad routing request'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // check http method
            if (!$this->request->isPost()) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'Redirect',
                    'message' => 'The HTTP method is not allowed for this route.'
                ];
                // send json response
                $this->response->json($jsonData, 405);

            }

            // get headers
            $headers = getallheaders();

            // get tokens
            $accessTokenHeader = $headers['ACCESS-TOKEN'] ?? '';
            $accessToken = stripos($accessTokenHeader, 'Bearer ') === 0 ? substr($accessTokenHeader, 7) : $accessTokenHeader;
            $refreshTokenHeader = $headers['REFRESH-TOKEN'] ?? '';
            $refreshToken = stripos($refreshTokenHeader, 'Bearer ') === 0 ? substr($refreshTokenHeader, 7) : $refreshTokenHeader;

            // verify tokens
            $refreshTokenData = $this->token->getUserDataFromRefreshToken( $refreshToken );

            // check if refresh token is valid
            if (!$refreshTokenData) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'Expired Token',
                    'message' => 'Invalid or Expired Refresh Token.'
                ];
                // send json response
                $this->response->json($jsonData, 401);

            }

            $email = $refreshTokenData['data']['email'];

            // update user active status
            $this->user->updateUserActiveStatus($email, 0);

            Session::logout();

            // json response
            $jsonData = [
                'status' => 1
            ];
            // send json response
            $this->response->json($jsonData, 200);

        }
        

    }