<?php
    /*
     * App core class 
     * Creates URL & loads core controller 
     * URL format - /controller/method/params
     */

     class Core{
         protected $currentController = 'Pages';
         protected $currentMethod = 'index';
         protected $params = [];

         public function __construct()
         {
            // print_r($this->getURL());
            $url = $this->getURL();

            //Look in controllers for first value
            if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
                //if exist, set as the current controller 
                $this->currentController = ucwords($url[0]);
                //Unset zero index
                unset($url[0]);
            }

            //Require the controller
            require_once('../app/controllers/' . $this->currentController . '.php');

            //Instantiate controller class
            $this->currentController = new $this->currentController;

            //Check second part of url
            if(isset($url[1])){
                //check if method from url is existing in controller
                if(method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];

                    //Unset index 1
                    unset($url[1]);
                }
            }

            //Get params
            $this->params = $url ? array_values($url) : [];

            //Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

         }

         public function getURL(){
           if(isset($_GET['url'])){
               $url = rtrim($_GET['url'], '/');
               $url = filter_var($url, FILTER_SANITIZE_URL);
               $url = explode('/', $url);
               return $url;
           }
         }

     }