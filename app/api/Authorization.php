<?php

    /**
     * 
     * Authorization API file
     * 
     * This file handles the authorization
     * related API requests.
     * 
     */
    // namespace
    namespace App\Api;

    use App\Core\BaseController;
    use App\Middleware\JWTToken;


    /**
     * Authorization class
     */
    class Authorization extends BaseController {

        protected $token;

        /**
         * Constructor function
         */
        public function __construct() {

            // parent constructor
            parent::__construct();

            // new objects
            $this->token = new JWTToken();

        }



        /**
         * Function to verify user tokens
         */
        public function userActiveStatus() {

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
            $accessTokenData = $this->token->getUserDataFromToken($accessToken);
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

            // new token status
            $newTokenStatus = false;

            if ($accessTokenData === null) {

                /**
                 * access token is invalid or expired
                 * generate new access token, since refresh token is valid
                 */
                // user data
                $userData = [
                    'email' => $refreshTokenData['data']['email'],
                    'id' => $refreshTokenData['data']['id'],
                    'name' => $refreshTokenData['data']['name'],
                    'role' => $refreshTokenData['data']['role'],
                ];
                $accessToken = $this->token->generateJwtToken($userData, 3600); 
                $accessTokenData = $this->token->getUserDataFromToken($accessToken); 
                $newTokenStatus = true;

            }


            // get json data
            $data = $this->request->jsonInput();
            $userIdleStatus = $data['idle'] ?? false;

            // get user ID
            $userEmail = $accessTokenData['data']['email'];

            // check user idle status
            if ($userIdleStatus === true) {

                // update user active status
                $this->user->updateUserActiveStatus($userEmail, 0);
                $this->response->json([
                    'status' => 'User is IDLE'
                ], 200);

            }

            // update user active status
            $this->user->updateUserActiveStatus($userEmail, 1);
            $this->response->json([
                'status' => 'user is active'
            ], 200);

        }
    }