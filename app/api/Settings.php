<?php

    /**
     * 
     * Settings API file
     * 
     * This file handles the settings
     * related API requests.
     * 
     */

    // namespace
    namespace App\Api;

    // use app files
    use App\Core\BaseController;
    use App\Models\Mailer;
    use App\Middleware\JWTToken;
    use App\Core\Helpers;


    /**
     * Settings class
     */
    class Settings extends BaseController { 

        protected $token;


        /**
         * Constructor function
         */
        public function __construct() {

            // parent constructor
            parent::__construct();

            // new objects
            $this->token = new JWTToken(); // create new token object

        }



        /**
         * Function to change profile
         */
        public function changeProfile() {

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


            // get the input data
            $profilePicture = $_FILES['profile_picture'] ?? '';

            // check if file exists and uploading error
            if (empty($profilePicture) || !isset($profilePicture) || $profilePicture['error'] !== UPLOAD_ERR_OK) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'User Side', 
                    'message' => 'Failed to upload profile picture, please try again.'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // set file paths
            $targetDir = 'profiles/';
            $uniquePath = uniqid() . '_' . basename($profilePicture['name']);
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/assets/' . $targetDir;

            // get upload directory path and relative path
            $moveDirectory = $targetPath . $uniquePath;
            $relativePath = $targetDir . $uniquePath;

            // file max size and allowed mime types
            $maxSize = 2 * 1024 * 1024; // 2MB maximum
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $profilePicture['tmp_name']);
            finfo_close($finfo);

            // check allowed types
            if (!in_array($mimeType, $allowedTypes)) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'User Side',
                    'message' => 'Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // check file size limit
            if ($profilePicture['size'] > $maxSize) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'User Side',
                    'message' => 'Image size must be less than 2MB'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // check if file is real image
            $imageInfo = getimagesize($profilePicture['tmp_name']);
            if ($imageInfo === false)  {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'User Side',
                    'message' => 'Uploaded file is not valid image.'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // block extensions
            $extension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg','jpeg','png','gif', 'webp'];

            if (!in_array($extension, $allowedExtensions)) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'User Side',
                    'message' => 'File extension is not allowed.'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }


            // check if folder exists to upload image
            if (!file_exists($targetPath)) {

                // create a new file
                mkdir($targetPath,0755, true);

                // notify admin that a new file has been created 
                // because it was not found when user uploaded a file
            }

            // check uploading
            if (!move_uploaded_file($profilePicture['tmp_name'], $moveDirectory)) {

                // json response
                $jsonData = [
                    'status'=> 0,
                    'error'=> true,
                    'error_status' => 'Server Side',
                    'message'=> 'Failed to upload the Image'
                ];
                // send json response
                $this->response->json($jsonData, 400);

            }

            // get user id from access token
            $user_id = $accessTokenData['data']['id'];

            // update user profile picture in database
            $updateProfileStatus = $this->user->updateProfilePicture($user_id, $relativePath);
            if (!$updateProfileStatus) {

                // json response
                $jsonData = [
                    'status' => 0,
                    'error' => true,
                    'error_status' => 'Server Side',
                    'message' => 'Failed to update profile picture in database.'
                ];
                // send json response
                $this->response->json($jsonData, 500);

            }


            // profile picture updated successfully
            if ($newTokenStatus === true) {

                // json response
                $jsonData = [
                    'status' => 1,
                    'error' => false,
                    'message' => 'Profile picture updated successfully.',
                    'new_access_token_status' => $newTokenStatus,
                    'data' => [
                        'access_token' => $accessToken,
                        'profile_picture' => $relativePath
                    ]
                ];
                // send json response
                $this->response->json($jsonData, 200);

            } else {

                // json response
                $jsonData = [
                    'status' => 1,
                    'error' => false,
                    'message' => 'Profile picture updated successfully.',
                    'new_access_token_status' => $newTokenStatus,
                    'data' => [
                        'profile_picture' => $relativePath
                    ]
                ];
                // send json response
                $this->response->json($jsonData, 200);

            }

        }
    }