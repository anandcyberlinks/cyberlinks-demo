<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Users extends Apis{
    
    /*** Application user ***/
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
    
    /*** Application Detail ***/
    function appdetail_get(){
        $query = sprintf('select u.username,u.email,
                            u.first_name,u.last_name,
                            u.gender,u.language,
                            f.relative_path as "splash",
                            logo.relative_path as `logo`
                            from users u 
                            left join `files` logo on logo.id = u.image
                            left join `splash_screen` ss on ss.user_id = u.id
                            left join files f on f.id = ss.file_id
                            where u.id  = %d ',$this->app->id);
        
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
    
    /*** End Users login ***/
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
    
    /*** User registration ***/
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
                
                $tmp_userpassword = $user['password'];
                $user['username'] = $user['email'];
                $user['password'] = md5($user['password']);
                $user['image'] = file_exists($filepath) ? $filepath : '';
                unset($user['image_type']);
                if($this->db->insert('customers',$user)){
                    
                    $user_id = $this->db->insert_id();
                    $this->db->insert('user_password',array('user_id'=>$user_id,'u_password'=>$tmp_userpassword));
                    $this->db->insert('token',array('user_id'=>$user_id,'token'=>$user['token'],'action'=>'activation','expiry'=>date("Y-m-d H:i:s",strtotime('+7 day'))));
                    
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
    
    /*** Get user profile ***/
    function profile_get(){
        $response = array();
        if($this->user){
            $response = $this->user;
        }else{
            $response['error'][] = 'No user logged In';
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** edit user profile ***/
    function editprofile_post(){
        
        $response = array();
        $validation = array('id');
        
        if(isset($this->user->id) && $this->user->id > 0){
            $user = array_intersect_key(array('id'=>'','first_name'=>'','last_name'=>'','gender'=>'',
                                  'contact_no'=>'','image'=>'','image_type'=>'',
                                  'language'=>'english','modified'=>date("Y-m-d H:i:s")),$this->post());
            
            $validvalue = array();
            foreach($user as $key=>$val){
                $validvalue[$key] = $this->post($key);
            }
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($validvalue[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            if(!isset($response['error'])){
                //New image of user
                $filepath = sprintf('assets/upload/profilepic/%s.%s',uniqid(),'png');
                if(!empty($validvalue['image'])){
                    try{
                        $tmp = base64_decode($validvalue['image']);
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
                    
                    $validvalue['image'] = file_exists($filepath) ? $filepath : '';
                    unset($validvalue['image_type']);
                }
                
                $this->db->where('id', $validvalue['id']);
                if($this->db->update('customers', $validvalue)){
                    $response = array('code'=>true,'result' => sprintf("Successfully update user profile"));
                }
            }
            
        }else{
            $response['error'] = 'Invalid request';
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** User forget password ***/
    function forgot_post(){
        
        $response = array();
        $email = $this->post('email');
        
        if(isset($this->user->id) && $this->user->id > 0){
            //check Email is valid or not
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                //check in database
                $query = sprintf('select count(*) as tot from customers where email  = "%s" ',$email);
                $total = reset($this->db->query($query)->result());
                if($total->tot >0){
                    
                    $query = sprintf('select u_password as password from user_password where user_id = %d ',$this->user->id);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        
                        $dataset = reset($dataset);
                        $subject = '[I Am Punjabi]Forgot password';
                        $message = '<p>Your request for password is sucessfull.</p>';
                        $message .= '<p>Your password : '.$dataset->password.'</p>';               
                        $message .= "<br><br> Kind Regards,<br><br>";
                        $message .= "I Am Punjabi Team";
                        $to = $this->user->email;
                        $from = 'info@cyberlinks.in';
                        $this->sendmail(array('from'=>$from,'body'=>$message,'subject'=>$subject,'to'=>$to));
                        
                        $response = array('code'=>true,'result' => sprintf("Password sent on %s email address. Please check your mail box",$to));
                    }else{
                        $response['error'][] = sprintf('Password Not saved in database');
                    }
                }else{
                    $response['error'][] = sprintf('Email address not Valid');
                }
            }else{
                $response['error'][] = sprintf('Email address not Valid');
            }
        }else{
            $response['error'][] = 'Invalid Request';    
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** Change password ***/
    function changepassword_post(){
        
        $data = array_merge(array('old_password'=>'','new_password'=>'','confirm_password'=>''),$this->post());
        $validation = array('old_password','new_password','confirm_password');
        $response = array();
        
        if(isset($this->user->id) && $this->user->id > 0){
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            if($data['new_password'] != $data['confirm_password']){
                $response['error'][] = "New password and confirm password don't match";   
            }
            
            if(!isset($response['error'])){
                if(md5($data['old_password']) == $this->user->password){
                    
                    $tmp = array('password'=>md5($data['new_password']));
                    //change old password with new
                    $this->db->where('id',$this->user->id);
                    if($this->db->update('customers', $tmp)){
                        
                        $tmp = array('u_password'=>$data['new_password']);
                        //save password in user_password table
                        $this->db->where('user_id',$this->user->id);
                        if($this->db->update('user_password', $tmp)){
                            $response = array('code'=>true,'result' => sprintf("Password Successfully updated"));    
                        }
                    }
                }else{
                    $response['error'][] = "Old password is not match with current password";
                }
            }
        }else{
            $response['error'][] = 'Invalid Request';    
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** Comment Post ***/
    function comment_post(){
        
        $response = array();
        $validation = array('content_id','comment');
        
        if(isset($this->user->id) && $this->user->id > 0){
            $data = $this->array_cleanup(array('user_id'=>$this->user->id,'content_id'=>'','comment'=>'',
                                  'created_date'=>date("Y-m-d H:i:s"),'updated_date'=>date("Y-m-d H:i:s"),
                                  'moderator_id'=>$this->user->id,'user_ip'=>$_SERVER['REMOTE_ADDR'],
                                  'approved'=>'YES','status'=>'active'),$this->post());
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            //check content id is exist or not
            if(!isset($response['error'])){
                $query = sprintf('select count(*) as tot from contents where id = %d',$data['content_id']);
                $total = reset($this->db->query($query)->result());
                if($total->tot > 0){
                    if($this->db->insert('comment',$data)){
                        $response = array('code'=>true,'result' => sprintf("Comment Successfully inserted")); 
                    }
                }else{
                    $response['error'][] = sprintf('No content found on this %d',$data['content_id']);
                }
            }
            
        }else{
            $response['error'][] = 'Invalid Request';    
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** Liked Post ***/
    function like_post(){
        
        $response = array();
        $validation = array('content_id');
        
        if(isset($this->user->id) && $this->user->id > 0){
            $data = $this->array_cleanup(array('content_id'=>'','user_id'=>$this->user->id,'like'=>0),$this->post());
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            if(!isset($response['error'])){
                $query = sprintf('select count(*) as tot from user_favlikes where content_id = %d and user_id = %d',$data['content_id'],$this->user->id);
                $total = reset($this->db->query($query)->result());
                if($total->tot > 0){
                    //update
                    $this->db->where('user_id',$this->user->id);
                    $this->db->where('content_id',$data['content_id']);
                    if($this->db->update('user_favlikes', $data)){
                        
                        //get total likes
                        $query = sprintf('select SUM(uf.like) as tot from user_favlikes uf where uf.content_id = %d ',$data['content_id']);
                        $total = reset($this->db->query($query)->result());
                        $total = isset($total->tot) && $total->tot > 0 ? $total->tot : 0;
                        $response = array('code'=>true,'result' => array('total'=>$total,'msg'=>sprintf("Like Successfully updated")));    
                    }
                }else{
                    //insert
                    if($this->db->insert('user_favlikes',$data)){
                        //get total likes
                        $query = sprintf('select SUM(uf.like) as tot from user_favlikes uf where uf.content_id = %d ',$data['content_id']);
                        $total = reset($this->db->query($query)->result());
                        $total = isset($total->tot) && $total->tot > 0 ? $total->tot : 0;
                        $response = array('code'=>true,'result' => array('total'=>$total,'msg'=>sprintf("Like Successfully inserted")));    
                    }    
                }
            }
        }else{
            $response['error'][] = 'Invalid Request';
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    /*** favorite Post ***/
    function favorite_post(){
        
        $response = array();
        $validation = array('content_id');
        if(isset($this->user->id) && $this->user->id > 0){
            $data = $this->array_cleanup(array('content_id'=>'','user_id'=>$this->user->id,'favorite'=>0),$this->post());
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            if(!isset($response['error'])){
                $query = sprintf('select count(*) as tot from user_favlikes where content_id = %d and user_id = %d',$data['content_id'],$this->user->id);
                $total = reset($this->db->query($query)->result());
                if($total->tot > 0){
                    //update
                    $this->db->where('user_id',$this->user->id);
                    $this->db->where('content_id',$data['content_id']);
                    if($this->db->update('user_favlikes', $data)){
                        $response = array('code'=>true,'result' => sprintf("favorite Successfully updated"));    
                    }
                }else{
                    //insert
                    if($this->db->insert('user_favlikes',$data)){
                        $response = array('code'=>true,'result' => sprintf("favorite Successfully inserted")); 
                    }    
                }
            }
        }else{
            $response['error'][] = 'Invalid Request';
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
}