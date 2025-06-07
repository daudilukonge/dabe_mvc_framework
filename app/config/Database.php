<?php

    /**
     * 
     * 
     * database file for database connection
     * 
     * 
    */

    // namespace
    namespace App\Config;

    use mysqli;
    use App\Core\App;

    class Database {
        private $host;
        private $username;
        private $password;
        private $db_name;
        private $conn;

        public function __construct() {
            // load environemnt variables
            $this->host = $_ENV["DB_HOST"];
            $this->username = $_ENV["DB_USER"];
            $this->password = $_ENV["DB_PASS"];
            $this->db_name = $_ENV["DB_NAME"];

            // establish connection by calling connection function
            $this->connect();
        }

        // connection function
        public function connect() {
            
            // establish database connection
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            // check the connection
            if ($this->conn->connect_error) {
                // display the message
                $appE = new App();
                $appE->abort(500);
            }

            return $this->conn;

        }

        // close database connection
        public function closeConnection() {

            if ($this->conn) {
                $this->conn->close();
            }

        }
        
    }
