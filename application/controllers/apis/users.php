<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class Users extends REST_Controller{
    
    //Application user
    function applogin_post(){
        $data = $this->post();
        $response = array();
        if(isset($data['username']) && $data['username'] != '' && isset($data['password']) && $data['password'] != ''){
            
        }else{
            $response['error'] = 'Username and password not valid';
        }
        echo json_encode($response);
        exit;
    }
}