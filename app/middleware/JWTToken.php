<?php

    /**
     * 
     * 
     * JWT access token file
     * generate jwt, validate token
     * handle jwt error
     * assign jwt secret key
     * 
     * 
     */

    // namespace
    namespace App\Middleware;

    // use app files
    use Exception;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Firebase\JWT\ExpiredException;
    use Firebase\JWT\SignatureInvalidException;

    
    /**
     * JWTToken class
     */
    class JWTToken {
        private $secretKey;
        private $refreshSecretKey;
        private $algo = 'HS256';

        /**
         * constructor
         */
        public function __construct() {

            // JWT secret
            $this->secretKey = $_ENV['JWT_SECRET'];
            $this->refreshSecretKey = $_ENV['JWT_REFRESH_SECRET'];

        }


        /**
         * Function to handle JWT error, if secret key is not found
         */
        public function handleJwtError($secretKey = null) {

            $secretKey = $secretKey ?? $this->secretKey;
            return !empty($secretKey);

        }


        /**
         * Function to generate JWT Token
         */
        public function generateJwtToken($user, $time, $secretKey = null) {

            $secretKey ??= $this->secretKey;

            // check the secret key
            if (!$this->handleJwtError($secretKey)) {

                return [
                    'success' => false,
                    'error' => 'Secret key missing'
                ];

            }

            // payload
            $payload = [
                'iss' => 'sharenami.great-site.net',
                'iat' => time(),
                'exp' => time() + $time,
                'data' => [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ]
            ];

            // return encoded jwt token
            return JWT::encode($payload, $secretKey, $this->algo);

        }


        /**
         * Function to generate refresh token
         */
        public function generateRefreshToken($user, $time) {

            return $this->generateJwtToken($user, $time, $this->refreshSecretKey);

        }



        /**
         * Function to validate jwt token
         */
        public function validateJwt($token, $secretKey = null) {

            $secretKey ??= $this->secretKey;

            if (empty($token)) {
                
                return [
                    'success'=> false,
                    'error'=> 'Token is missing'
                ];

            }

            try {

                $decodedToken = JWT::decode($token, new Key($secretKey, $this->algo));
                $jwtToken = json_decode(json_encode($decodedToken), true);

                return [
                    'success' => true,
                    'data' => $jwtToken
                ];

            } catch (ExpiredException $e)  {

                return [
                    'success' => false,
                    'error' => 'Token Expired'
                ];

            } catch (SignatureInvalidException $e) {

                return [
                    'success'=> false,
                    'error' => 'Invalid Signature'
                ];

            } catch (Exception $e) {

                return [
                    'success' => false,
                    'error' => $e->getMessage()
                ];

            }

        }


        /**
         * Function to exctract user data from token
         */
        public function getUserDataFromToken($token) {

            $result = $this->validateJwt($token);
            return $result['success'] ? $result['data'] : null;

        }

        public function getUserDataFromRefreshToken($refreshToken) {

            $result = $this->validateJwt($refreshToken, $this->refreshSecretKey);
            return $result['success'] ? $result['data'] : null;

        }
    }