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
            if($dataset->logo != ''){
                $base_url = strpos('http://',$dataset->logo) > 0 ? '' : base_url();
                $dataset->logo = $base_url.$dataset->logo;
            }
            
            if($dataset->splash != ''){
                $base_url = strpos('http://',$dataset->splash) > 0 ? '' : base_url();
                $dataset->splash = $base_url.$dataset->splash;
            }
        });

        $response = array('tr'=>count($dataset),'result'=>$dataset);
        $this->response($response);
        exit;
    }
    
    /**** End Users login ****/
    function login_post(){
        $data = $this->post();
        $response = array();
        if(isset($data['username']) && $data['username'] != '' && isset($data['password']) && $data['password'] != ''){
            $query = sprintf('select * from customers where email  = "%s" AND password = "%s" AND owner_id = %d ',$data['username'],md5($data['password']),$this->app->id);
            $dataset = $this->db->query($query)->result();
            if(count($dataset) > 0){
                $user = reset($dataset);
                
                //Create/update token in api_token table
                $query = sprintf('select * from api_token where user_id = %d ',$user->id);
                $dataset = $this->db->query($query)->result();
                $token = md5(uniqid());
                if(count($dataset) <= 0){
                    //insert
                    $query = sprintf('insert into api_token values(null,"%s",%d,now(),now())',$token,$user->id);
                    $this->db->query($query);
                }else{
                    //update
                    $query = sprintf('update api_token set token = "%s",created_time = now(), hit_time = now() where user_id = %d',$token,$user->id);
                    $this->db->query($query);
                }
                $response['token'] = $token;
                
            }else{
                $response['error'] = 'Username and password not valid';
            }
        }else{
            $response['error'] = 'Username and password not valid';
        }
        $response = array('tr'=>1,'result'=>$response);
        $this->response($response);
        exit;
    }
    
}