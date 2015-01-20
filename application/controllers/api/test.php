<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Test extends REST_Controller
{   
    
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');       
   }
   
   public function o_get()
   {        
      $p = $this->get('p');
       if($p)
        {           
            $this->response($p, 200); // 200 being the HTTP response code
        }else{
            $this->response('No Input enter', 404);
        }
   }   
}