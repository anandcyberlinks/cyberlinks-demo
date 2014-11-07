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
    
    function appdetail_get(){
        $qs = $this->get();
        $query = sprintf('select u.username,u.email,
                            u.first_name,u.last_name,
                            u.gender,u.language,
                            f.relative_path as "splash",
                            logo.relative_path as `logo`
                            from users u 
                            left join `files` logo on logo.id = u.image
                            left join `splash_screen` ss on ss.user_id = u.id
                            left join files f on f.id = ss.file_id
                            where u.token  = "%s" ',$qs['at']);
        
        $dataset = $this->db->query($query)->result();
        array_walk($dataset,function(&$dataset){
            if($dataset->logo!=''){
                $base_url = strpos('http://',$dataset->logo) > 0 ? '' : base_url();
                $dataset->logo = $base_url.$dataset->logo;
            }
            
            $base_url = strpos('http://',$dataset->splash) > 0 ? '' : base_url();
            $dataset->splash = $base_url.$dataset->splash;
        });

        $response = array('tr'=>count($dataset),'result'=>$dataset);
        $this->response($response);
        exit;
    }
    
}