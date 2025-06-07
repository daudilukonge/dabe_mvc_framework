<?php

    /**
     * 
     * 
     * this is user model file
     * its going to represent user in mvc app
     * 
     * 
    */

    // namespace
    namespace App\Models;

    // use app files
    use App\Config\Database;
    use App\Core\Request;

    // user class
    class User {

        private $conn;
        private $table = 'users';
        private $otp_table = 'otp_tokens';

        /**
         * Construct function
         */
        public function __construct() {

            // create new database object
            $db = new Database();

            $this->conn = $db->connect(); 

        }



        /** 
         * Function to register user
         */
        public function register($name, $email, $password) {

            // chceck if email is registered
            if ($this->emailExists($email)) {
                return [false, 'Email already registered by another User.'];
            }

            // hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $profileImage = '/profiles/user.png'; // default profile image
            // database query
            $query = "INSERT INTO " . $this->table . " (name, email, profile_image, password) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $name, $email, $profileImage, $hashedPassword);

            if ($stmt->execute()) {
                return [true, 'You are registered successfully.'];
            }
            return [false, 'Internal server error.'];

        }



        /**
         * Function to login user
         */
        public function login($email, $password) {

            // database query
                $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();

                // get result
                $result = $stmt->get_result();

                // check if user exists
            if ($result->num_rows == 1) {

                // get user data
                $user = $result->fetch_assoc();

                // verify password
                if (password_verify($password, $user['password'])) {

                    // correct password
                    $userData = array_merge($user, ['message' => 'You have logged in successfully.']);
                    return [true, $userData];

                } else {

                    // incorrect password
                    return [false, 'Invalid email or password.'];

                }

            }

            // user not found
            return [false, 'Invalid email or password.'];

        }


        
        /**
         * Function to get total number of users
         */
        public function countUsers() {

            // query
            $query = 'SELECT COUNT(*) AS total FROM '. $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $row = $result->fetch_assoc();

            return (int)$row['total'];

        }



        /**
         * Function to get user by email
         */
        public function getUserData($email) {

            // query
            $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();

            // get result
            $result = $stmt->get_result();

            // check if user exists
           if ($result->num_rows == 1) {

               return [true, $result->fetch_assoc()];

           }

           return [false, 'User not found.'];

        }


        /**
         * Function to update user status 
         */
        public function updateUserStatus($email, $status) {

            $status ??= 'Verified';

            // query
            $query = 'UPDATE ' . $this->table . ' SET status = ? WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss", $status, $email);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        }



        /**
         * Function to update user active status
         */
        public function updateUserActiveStatus($email, $activeStatus) {

            $activeStatus ??= 0;

            // query
            $query = 'UPDATE ' . $this->table . ' SET active_status = ?, last_seen = NOW() WHERE email = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("is", $activeStatus, $email);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        }


        /**
         * Function to update user profile
         */
        public function updateProfilePicture($user_id, $profile_picture) {

            // query
            $query = 'UPDATE ' . $this->table . ' SET profile_image = ? WHERE id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $profile_picture, $user_id);
            $stmt->execute();

            return $stmt->affected_rows > 0;

        }


        /**
         * Function to check if email exists
         */
        public function emailExists($email) {

            // database query
            $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result->num_rows > 0;

        }


        /**
         * Function to validate login data
         */
        public function validateLoginData($email, $password) {

            // check if data is empty
            if (empty($email) || empty($password)) {
                return [false, 'All fields are required.'];
            }

            // validate user email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [false, 'Invalid email format.'];
            }

            return [true, 'User data is valid.'];

        }



        /**
         * Function to validate register data
         */
        public function validateRegisterData($name, $email, $password) {

            $name = trim($name);

            // check if data is empty
            if (empty($name) || empty($email) || empty($password)) {
                return [false, 'All fields are required.'];
            }

            // validate user email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [false, 'Invalid email format.'];
            }

            // validate user name
            if (!preg_match("/^[a-zA-Z ]*$/", $name) || strlen($name) < 4) {
                return [false, 'Username must be at least 4 characters long, contain only letters and spaces. ' . $name];
            }

            // validate password
            if (strlen($password) < 8) {
                return [false, 'Password must be at least 8 characters long.'];
            }

            return [true, 'User data is valid.'];   

        }


        /**
         * Function to validate otp data
         */
        public function validateOTPData($email, $otp, $reason) {

            if (empty($otp) || empty($email) || empty($reason)) {
                return [false, 'All fields are required.'];
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [false, 'Invalid email format.'];
            }

            if (!preg_match("/^[a-zA-Z ]*$/", $reason)) {
                return [false, 'Reason must contain only letters.'];
            }

            return [true, 'OTP data is valid.'];

        }



        /**
         * Function to store otp in database
         */
        public function storeOTP($email, $otp, $reason, $validMinutes = 5) {

            $expiresAt = date('Y-m-d H:i:s', strtotime("+$validMinutes minutes"));

            // start query
            $query = "INSERT INTO " . $this->otp_table. " (email, otp_code, reason, expires_at) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $email, $otp, $reason, $expiresAt);

            if ($stmt->execute()) {
                return [true, 'You are registered successfully.'];
            }
            return [false, 'Internal server error.'];

        }



        /**
         * Function to get otp
         */
        public function getOTP($email, $reason) {

            // start query
            $query = 'SELECT * FROM ' . $this->otp_table . ' WHERE email = ? AND reason = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $email, $reason);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {

                $otp_data = $result->fetch_assoc();

                return [ true, $otp_data ];

            }

            return [ false, 'OTP does not exists' ];

        }


        /**
         * Function to delete otp
         */
        public function deleteOTP($email, $reason) {

            // query
            $query = 'DELETE FROM '. $this->otp_table .' WHERE email = ? AND reason = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $email, $reason);
            $stmt->execute();

            return $stmt->affected_rows > 0;
        }


        /**
         * Function to select all users
         */
        public function all() {
            $query = "SELECT * FROM {$this->table}";
            $result = $this->conn->query($query);

            $users = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                $result->free();
            }


            return $users;
        }



        /**
         * Function to get user last active time
         */
        public function getTimeAgo($time) {

            $timeAgo = strtotime($time);
            $currentTime = time();
            $timeDifference = $currentTime - $timeAgo;

            if ($timeDifference < 60) {

                return 'Just now';

            } elseif ($timeDifference < 3600) {

                $minutes = floor($timeDifference /60);
                return $minutes .' minute' . ($minutes > 1 ? 's' : '') . ' ago';

            } elseif ($timeDifference < 86400) {

                $hours = floor($timeDifference / 3600);
                return $hours .' hour'. ($hours > 1? 's': '') . ' ago';

            } elseif ($timeDifference < 172800) {

                return 'Yesterday';

            } else {

                $days = floor($timeDifference / 86400);
                return $days .' day'. ($days > 1? 's': '') . ' ago';

            }

        }
        
    }