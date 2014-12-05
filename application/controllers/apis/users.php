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
    
    /*** User registration ****/
    function register_post(){
        $user = array_merge(array('first_name'=>'','last_name'=>'','gender'=>'','email'=>'','password'=>'',
                                  'contact_no'=>'','image'=>'','image_type'=>'','status'=>'inactive',
                                  'language'=>'english','role_id'=>'NULL','token'=>md5(uniqid()),'owner_id'=>$this->app->id,
                                  'created'=>date("Y-m-d H:i:s"),'modified'=>date("Y-m-d H:i:s")),$this->post());
        $validation = array('first_name','last_name','gender','email','password','image','image_type');
        $response = array();
        
        //check validation
        foreach($validation as $key=>$val){
            if(empty($user[$val])){
                $response['error'][] = sprintf('%s field is required',ucwords($val));
            }    
        }
        
        if(!isset($response['error'])){
            //check user already register with US
            $query = sprintf('select count(*) as tot from customers c where c.email = "%s" and c.owner_id = %d',$user['email'],$this->app->id);
            $total = reset($this->db->query($query)->result());
            if($total->tot > 0){
                $response['error'][] = sprintf('%s is already registered with %s',$user['email'],ucwords($this->app->username));
            }else{
                //Insert new user
                $filepath = sprintf('assets/upload/profilepic/%s.%s',uniqid(),'png');
                if(!empty($user['image'])){
                    try{
                        $tmp = base64_decode($user['image']);
                        $im = @imagecreatefromstring($tmp);
                        if ($im !== false){
                            if(imagepng($im, $filepath)){
                                            
                            }else{
                                throw new Exception("Image not valid");
                            }
                        }else{
                            throw new Exception("Image string not valid");
                        }
                    }catch(Exception $e){
                        $response['error'][] = $e->getMessage();
                    }
                }
                
                $user['username'] = $user['email'];
                $user['password'] = md5($user['password']);
                $user['image'] = file_exists($filepath) ? $filepath : '';
                unset($user['image_type']);
                if($this->db->insert('customers',$user)){
                    
                    $subject = '[I Am Punjabi]Confirm your email address';
                    $message = '<p>You recently register in our service</p>';
                    $message .= '<p>Please confirm your email by clicking link below.</p>';
                    $message .= '<p><a href="'.site_url('confirmation').'?t='.$user['token'].'">Confirm your email address</a></p>';
                    $message .= "<br><br> Kind Regards,<br><br>";
                    $message .= "I Am Punjabi Team";
                    $to = $user['email'];
                    $from = 'info@cyberlinks.in';
                    $this->sendmail(array('from'=>$from,'body'=>$message,'subject'=>$subject,'to'=>$to));
                    
                    $response = array('code'=>true,'result' => sprintf("You are successfully registered. Please check you confirmation mail in your email id: %s",$user['email']));
                }
            }
        }
        $this->response($response);
        exit;
    }
}