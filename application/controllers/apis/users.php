<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Users extends Apis{
    
    //Application user
    function applogin_post(){
        $data = $this->post();
        $response = array();
        if(isset($data['username']) && $data['username'] != '' && isset($data['password']) && $data['password'] != ''){
            $query = sprintf('select * from users where username  = "%s" AND password = "%s" ',$data['username'],md5($data['password']));
            $dataset = $this->db->query($query)->result();
            $dataset = reset($dataset);
            if(isset($dataset->id)){
                $response['token'] = $dataset->token;
            }else{
                $response['error'] = 'Username and password not valid';
            }
        }else{
            $response['error'] = 'Username and password not valid';
        }
        $this->response($response);
        exit;
    }
}