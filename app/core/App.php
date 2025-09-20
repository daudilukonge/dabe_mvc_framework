<?php

    /**
     * 
     * 
     * this is app core file
     * it handles routing
     * and create new app
     * 
     * 
    */

    // namespace
    namespace App\Core;

    class App {

        private $uri;
        private $httpMethod;
        private $routes = [];
        // default allowed http methods
        private $allowedHTTPMethods = ['GET' => true, 'POST' => true];
        private $errorController;
        private $errorMethod;


        
        /**
         * 
         * 
         * constructor function
         * to initialize the app
         * 
         * 
        */
        public function __construct() {

            // define uri
            $this->uri = parse_url($_SERVER['REQUEST_URI'])['path'] ?? '';

            // define http request method
            $this->httpMethod = strtoupper($_SERVER['REQUEST_METHOD']);

        }



        /**
         * 
         * 
         * function to add/register routes
         * 
         * 
        */
        public function addRoute($httpMethod, $uri, $controller, $method) {

            // assign routes
            $httpMethod = strtoupper($httpMethod);

            // convert {param} into regex group
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri);
            $pattern = "#^" . $pattern . "$#"; // match the whole string

            $this->routes[$httpMethod][$uri] = [
                'controller' => $controller,
                'method' => $method,
                'uri' => $uri,
                'pattern' => $pattern
            ];

            // return instance for method chaining
            return $this;

        }




        /**
         * 
         * 
         * function to add allowed http methods dynamically
         * 
         * 
        */
        public function addAllowedHTTPMethods($httpMethod) {

            // if not an array, convert it to array
            $httpMethod = (array) $httpMethod; // force to array

            foreach ($httpMethod as $method) {

                $method = strtoupper($method); // force to uppercase

                // check if method is not already in allowed http methods
                if (!isset($this->allowedHTTPMethods[$method])) {
                    $this->allowedHTTPMethods[$method] = true; // add to allowed http methods
                }

            }

        }



        /**
         * 
         * 
         * function to set the error controller
         * 
         * 
        */
        public function setErrorController($errorController, $errorMethod) {

            $this->errorController = $errorController;
            $this->errorMethod = $errorMethod;

        }



        /**
         * 
         * 
         * function to run the app routing
         * 
         * 
         */
        public function run() {

            // Validate HTTP method
            if (!array_key_exists($this->httpMethod, $this->allowedHTTPMethods)) {

                // http method not allowed, abort
                $this->abort(405); // method not allowed

            }

            $matched = false;

            foreach ($this->routes[$this->httpMethod] ?? [] as $route) {

                if (preg_match($route['pattern'], $this->uri, $matches)) {

                    // get controller and method
                    $controller = $route['controller'];
                    $method = $route['method'];

                    if (class_exists($controller) && method_exists($controller, $method)) {
                        $controller = new $controller;

                        // Extract named params {id}, {name}, etc.
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                        // Reorder params to match method signature
                        $ref = new \ReflectionMethod($controller, $method);
                        $orderedParams = [];

                        foreach($ref->getParameters() as $param) {

                            $name = $param->getName();
                            $orderedParams[] = $params[$name] ?? null;

                        } 

                        call_user_func_array([$controller, $method], $orderedParams);
                        $matched = true;
                        break;
                    }
                }

            }

            if (!$matched) {
                $this->abort(404);
            }

        }

        private function getAllowedURIs() {

            // get allowed uri from routes
            return array_keys($this->routes[$this->httpMethod] ?? []);

        }

        public function abort($code) {
            
            http_response_code($code); // set HTTP response code
            
            $errorController = $this->errorController;
            $errorMethod = $this->errorMethod;
            
            // validate error controller
            if (class_exists($errorController)) {
                // create new error controller object
                $controller = new $errorController;

                // validate error method
                if (method_exists($controller, $errorMethod) || is_callable([$controller, $errorMethod])) {
                    // call method
                    $controller->$errorMethod($code);
                } else {
                    // method not collable or does not exists
                    echo 'Error: Method not found';
                }

            } else {
                echo 'Error: Controller not found!';
            }

            die();
        }

    }