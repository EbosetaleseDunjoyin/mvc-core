<?php 

    namespace edj\mvcframecore;

    class Session 
    {
        protected const FLASH_KEY = 'flash_messages';
        public function __construct() {
            \session_start();
            $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

            foreach ($flashMessages as $key => &$flashMessage) {
                # mark to be removed
                $flashMessage['remove'] = true;
                

            }
            $_SESSION[self::FLASH_KEY]= $flashMessages ;
                
                
        }

        public function setFlash($key,$message) 
        {
            $_SESSION[self::FLASH_KEY][$key] =[
                'value'=> $message,
                'remove' => false    
            ];
        }



        public function getFlash($key)
        {
            return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
        }
        //Set Key 
        public function set($key , $value)
        {
            $_SESSION[$key] = $value;
        }
        
        // get  Key
        public function get($key )
        {
           return  $_SESSION[$key] ?? false;
        }

        public function remove($key)
        {
            # code...
            unset($_SESSION[$key]);
        }

        public function __destruct(){
            //Iterate over fllashmessages

            $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

            foreach ($flashMessages as $key => &$flashMessage) {
                # mark to be removed
                if ($flashMessage['remove']) {
                    # code...
                    unset($flashMessages[$key]);
                }
               // $flashMessage['remove'] = false;
                

            }
            
            $_SESSION[self::FLASH_KEY]= $flashMessages ;

        }

    }




?>