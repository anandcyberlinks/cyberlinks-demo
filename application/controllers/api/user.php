<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class User extends REST_Controller
{   
    
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/User_model');
   }
       
   function splash_get()
   {
       $project = $this->get('project');
       $result = $this->User_model->getsplash($project);
       if($result){
           $result->logo = base_url().PROFILEPIC_PATH.$result->logo;
           $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
       }
       
   }
   
   function base64_to_jpeg($base64_string, $output_file,$extension) {
        
	$img = $base64_string;
	//$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);        
        $file = uniqid() . '.'.$extension;
	$data = base64_decode($img);
        $srctmp = $output_file.'.tmp'.'.'.$extension;
	$output_file .= $file;
	$success = file_put_contents($srctmp, $data);
        
        //-- create thumbnail --//
        
        if($success){
            $this->create_thumbnail($file,$srctmp,$output_file,'100','100');
            return $file;
        }else{
            return 0;	    
        }
    }
    
    function add_post()
    {
       $token = sha1($this->post('email').$this->post('password').rand());
       $ext = 'jpg';//$this->post('ext');
        //---- Upload logo image for user --//
        if($ext !=''){
           $my_base64_string = $this->post('pic');
            $pic = $this->base64_to_jpeg( $my_base64_string, PROFILEPIC_PATH,$ext );
            if($pic == 0){
                $this->response(array('code'=>0,'error' => "Error uploading image."), 404);
            }
        }else{
                $incoming_tmp = $_FILES['pic']['tmp_name'];
                $incoming_original = $_FILES["pic"]["name"];
                if ($incoming_original != '') {                    
                    $path = PROFILEPIC_PATH;
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $output = $this->uploadfile($incoming_tmp, $incoming_original, $path, $allowed);  //-- upload file --//

                    if ($output['error']) {
                        $this->response(array('code'=>0,'error' => $output['error']), 404);
                    } else {
                        $pic = $output['path'];
                    }
                } else {
                    //$_POST['file'] = $_POST['logo'];
                }
        }
        $data = array( 
            'first_name' => $this->post('firstname'), 
            'last_name' => $this->post('lastname'),
            'gender' => $this->post('gender'),
            'email' => $this->post('email'), 
            'password' => $this->post('password'),
            'contact_no' => $this->post('phone'),
            'image' => $pic,           
            'status' => 'inactive'           
            );
                
        if($this->validateuser($data)){
            $data['password'] = md5($this->post('password'));
            //-- check if user already exist --//
               $user = $this->User_model->checkuser($this->post('email'));
               if($user>0){
                   $this->response(array('code'=>0,'error' => "Email is already exist."), 404);
               }
            //---------------------------------//
            $id = $this->User_model->adduser($data);
            if($id){
                
                //-- inser in user password --//
                    $datapass = array('user_id'=>$id,'u_password'=>$this->post('password'));
                    $this->User_model->addpassword($datapass);
                //----------------------------//
                
                //-- generate activation token --//
                    $tokendata = array('user_id'=>$id,'token'=>$token,'action'=>'activation');
                    $this->User_model->activationToken($tokendata);
                //---------------------------------//
                
                //-- send confirmation mail --//               
                $subject = '[I Am Punjabi]Confirm your email address';
                $message = '<p>You recently register in our service</p>';
                $message .= '<p>Please confirm your email by clicking link below.</p>';
                $message .= '<p><a href="'.site_url('confirmation').'?t='.$token.'">Confirm your email address</a></p>';
                $message .= "<br><br> Kind Regards,<br><br>";
                $message .= "I Am Punjabi Team";
                $to = $this->post('email');
                $from = 'info@cyberlinks.in';
                $this->sendmail($subject,$message,$from,$to);
                //---------------------------//
                $this->response(array('code'=>1,'result' => "You are successfully registered. Please check you confirmation mail in your email id: ".$this->post('email')), 200);
            }
        }
        //$this->response($message, 200); // 200 being the HTTP response code
    }
    
    
    function edit_post()
    {
        //--- validate token ---//        
            $this->validateToken();
        //------------------------//
        $id = $this->get('id');
         $ext = $this->post('ext');
        //---- Upload logo image for user --//
        if($ext !=''){
           $my_base64_string = $this->post('pic');
            $pic = $this->base64_to_jpeg( $my_base64_string, PROFILEPIC_PATH,$ext );
            if($pic == 0){
                $this->response(array('code'=>0,'error' => "Error uploading image."), 404);
            }
        }else{
        //---- Upload logo image for player --//
                $incoming_tmp = $_FILES['pic']['tmp_name'];
                $incoming_original = $_FILES["pic"]["name"];

                if ($incoming_original != '') {
                    $path = PROFILEPIC_PATH;
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $output = $this->uploadfile($incoming_tmp, $incoming_original, $path, $allowed);  //-- upload file --//

                    if ($output['error']) {
                        $this->response(array('code'=>0,'error' => $output['error']), 404);
                    } else {
                        $pic = $output['path'];
                    }
                } else {
                    //$_POST['file'] = $_POST['logo'];
                }
        }
        $data = array( 
            'first_name' => $this->post('firstname'), 
            'last_name' => $this->post('lastname'),
            'gender' => $this->post('gender'),            
            'contact_no' => $this->post('phone')            
            );
           if($pic !='' && $pic != 0){
               $data['image']=$pic;
           }
           
            $result = $this->User_model->update_user($data,$id);
            if($result){
                $this->response(array('code'=>1,'result'=>'Profile Updated Successfully'), 200); // 200 being the HTTP response code
            }
    }
    
    function delete_get()
    {
        //--- validate token ---//        
            $this->validateToken();
        //------------------------//
        $id = $this->get('id');
        $this->User_model->delete_user($id);
        $this->response(array('code'=>1,'success' => "User deleted successfully."), 200);
    }

    function confirm_post()
    {
        $token = $this->post('token');
        $id = $this->User_model->confirmRegistration($token);
        if($id>0){
            $this->response(array('success' => "Your email is confirm successfully."), 200);
        }  else {
            $this->response(array('error' => "Email confirmation failed. Your token is invalid/expired."), 404);
        }
    }
    
    function login_post()
    {
        $email = $this->post('email');
        $password = md5($this->post('password'));
        
        $data = array(
            'email' => $this->post('email'),
            'password' => $this->post('password'),
            );
        
         if($this->validatelogin($data)){
               $id = $this->User_model->loginuser($this->post('email'),md5($this->post('password')));
               if($id>0){
                //-- api token --//
                    $this->generateApiToken($id,$this->post('email'),$this->post('password'));
                    $result = $this->User_model->getuser($id);
                
                if($result->image !=""){
                    $result->image = base_url().PROFILEPIC_PATH.$result->image;
     		}
                       $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
               }else{
                   $this->response(array('code'=>0,'error' => "Login failed"), 404);
               }
         }
    }
    
    function logout_get()
    {
        $token = $this->get('token');
        $result = $this->User_model->logout($token);
        $this->response(array('code'=>1,'result'=>'Logout successfully'), 200); // 200 being the HTTP response code
    }
    
    function forgot_post()
    {
        //--- validate token ---//        
            //$this->validateToken();
        //------------------------//
        $email = $this->post('email');
        
        if($this->post('email')==''){
           $this->response(array('code'=>0,'error' => "Email cannot be blank"), 404);
        }
        //-- verify email --//
            $id = $this->User_model->checkuser($this->post('email'));
        //------------------//       
            
         if($id>0){
           // $result = $this->User_model->getforgotuser($id);
            
             $token = base64_encode($email);
              //-- send confirmation mail --//               
                $subject = '[I Am Punjabi]Forgot password';
                $message = '<p>You recently request for forgot password</p>';
                $message .= '<p>Please confirm your request by clicking link below.</p>';
                $message .= '<p><a href="'.site_url('forgot').'?t='.$token.'">Click here</a></p>';
                $message .= "<br><br> Kind Regards,<br><br>";
                $message .= "I Am Punjabi Team";
                $to = $this->post('email');
                $from = 'info@cyberlinks.in';
                $this->sendmail($subject,$message,$from,$to);
           $this->response(array('code'=>1,'result' => "You forgot password request is send. Please check your email"), 200);
        }else{
            $this->response(array('code'=>0,'error' => "Invalid email id"), 404);
        }
    }
        
    function sendpassword_post()
    {
        $token = $this->post('token');
        
        if($token == ''){
           $this->response(array('msg' => "Request can't be processed. Invalid request!"), 404);
        }
        $result = $this->User_model->getPassword(base64_decode($token));
        
           if($result){
               $password =$result->password;
               $email = $result->email;
              //-- send confirmation mail --//               
                $subject = '[I Am Punjabi]Forgot password';
                $message = '<p>Your request for password is sucessfull.</p>';
                $message .= '<p>Your password : '.$password.'</p>';               
                $message .= "<br><br> Kind Regards,<br><br>";
                $message .= "I Am Punjabi Team";
                $to = $email;
                $from = 'info@cyberlinks.in';
                $this->sendmail($subject,$message,$from,$to);
            $this->response(array('msg' => "Your password is send in your email."), 200);
        }else{
            $this->response(array('msg' => "Request can't be processed. Invalid request!"), 404);
        }
    }
    
    function reset_post()
    {
        //--- validate token ---//        
            $this->validateToken();
        //------------------------//
        $email = $this->post('email');
        $old_password = $this->post('old_password');
        $new_password = $this->post('new_password');
        $conf_password = $this->post('conf_password');
        
        if($old_password==''){
            $error = "Old password cannot be blank";             
        }
        if($new_password==''){
            $error = "New password cannot be blank";             
        }
        if($conf_password==''){
            $error = "Confirm password cannot be blank";             
        }
        if($new_password != $conf_password){
            $error = "New password and confirm password don't match";             
        }
   
        if(!empty($error)){
            $this->response(array('code'=>0,'error' => $error), 404);
        }
        
        $id = $this->User_model->validpassword($email,md5($old_password));
            if($id>0){
                //-- update password --//                
                $this->User_model->resetpassword($id,$new_password);               
                //---------------------//
                
                //-- send confirmation mail --//
                $subject = '[I Am Punjabi]Reset password';
                $message = '<p>Your password reset sucessfully.</p>';
                $message .= '<p>Please longin with your new password</p>';               
                $message .= "<br><br> Kind Regards,<br><br>";
                $message .= "I Am Punjabi Team";
                $to = $email;
                $from = 'info@cyberlinks.in';
                $this->sendmail($subject,$message,$from,$to);
                $this->response(array('code'=>1,'result' => "Your password is reset."), 200);               
            }else{
                $this->response(array('code'=>0,'error' => "Reset password failed."), 404);
            }
    }
    
    function social_post()
    {
        $provider = $this->post('provider');
        $userdetails = json_decode($this->post('social'));
        //print_r($userdetails);die;
        if($provider=='facebook'){
            $firstname = $userdetails->first_name;
            $lastname = $userdetails->last_name;
            $email = $userdetails->email;
            $gender = $userdetails->gender;
            $socialid = $userdetails->id;
            $password = md5($socialid);
           
            $token = sha1($socialid.rand());
        }
        
        if($provider=='google')
        {
            /*$firstname = $userdetails->profile->name->givenName;
            $lastname = $userdetails->profile->name->familyName;
            $email = $userdetails->profile->email;
            $gender = $userdetails->profile->gender;
            $socialid = $userdetails->profile->googleUserId;
            $password = md5($socialid);
            $token = sha1($email.$socialid.rand());
            */
            $firstname = $userdetails->first_name;
            $lastname = $userdetails->last_name;
            $email = $userdetails->email;
            $gender = $userdetails->gender;
            $socialid = $userdetails->id;
            $password = md5($socialid);
            $token = sha1($socialid.rand());
            //echo '<pre>'; print_r($userdetails);die;
        }
                
        //-- check if social account is already exist --//
            $uid = $this->User_model->loginuser($email, $password);
            
            if($uid>0){
              //-- api token --//
               $this->generateApiToken($uid,$email,$socialid);
               $result = $this->User_model->getuser($uid);
               $this->response(array('code'=>1,'result'=>$result), 200); 
            }else{              
            //-----------Register user-----------------//
            $userdata = array(
            'first_name' => $firstname, 
            'last_name' => $lastname,
            'gender' => $gender,
            'email' => $email, 
            'password' => $password,
            //'token' => $token,
            'status' => 'active'
            //'created'=>date('Y-m-d h:i:s')
                );
           
             $id = $this->User_model->adduser($userdata);
            
             if($id){
               $socialdata = array('social_id' => $socialid, 
               'from' => $provider,            
               'user_id' => $id,  
               'info' => serialize($userdetails),
               'status' => 1,
               'created'=>date('Y-m-d h:i:s'));
                $this->User_model->addsocial($socialdata);
            }
            if($id){
                 //-- api token --//
                $this->generateApiToken($id,$email,$socialid);
                $result = $this->User_model->getuser($id);
               $this->response(array('code'=>1,'result'=>$result), 200);
            }
        }
    }
    
    function social_old_post()
    {
        $token = sha1($this->post('firstname').$this->post('social_id').rand());
        
        //-- check if social account is already exist --//
            $uid = $this->User_model->loginuser($this->post('email'), md5($this->post('social_id')));
            
            if($uid>0){
               $result = $this->User_model->getuser($uid);
               $this->response($result, 200); 
            }else{
             
            //-----------Register user-----------------//
            $userdata = array(
            'first_name' => $this->post('firstname'), 
            'last_name' => $this->post('lastname'),
            'gender' => $this->post('gender'),
            'email' => $this->post('email'), 
            'password' => md5($this->post('social_id')),
            'token' => $token,
            'status' => 1,
            'created'=>date('Y-m-d h:i:s'));
             $id = $this->User_model->adduser($userdata);
            
             if($id){
               $socialdata = array('social_id' => $this->post('social_id'), 
               'from' => $this->post('social_site'),            
               'user_id' => $id,            
               'status' => 1,
               'created'=>date('Y-m-d h:i:s'));
                $this->User_model->addsocial($socialdata);
            }
            if($id){
                $result = $this->User_model->getuser($id);
               $this->response($result, 200); 
            }
        }
    }
    public function validateuser($data)
    {
        $error=array();
        if($data['first_name']==''){
            $error['first_name'] ="First name cannot be blank";             
        }
        if($data['email']==''){
            $error['email'] ="Email cannot be blank";             
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "This email address is invalid.";
        }
        
        if($data['password']==''){
            $error['password'] ="Password cannot be blank";             
        }
        if(!empty($error)){
            $this->response(array('error' => $error), 404);
        }
        return 1;
    }
    
    public function validatelogin($data)
    {
        $error=array();       
        if($data['email']==''){
            $error['email'] ="Email cannot be blank";             
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error['email'] = "This email address is invalid.";
        }        
        if($data['password']==''){
            $error['password'] ="Password cannot be blank";             
        }
        if(!empty($error)){
            $this->response(array('error' => $error), 404);
        }
        return 1;
    }
    
    function sendmail($subject='no reply',$body='Test',$from,$to)
    {
        $this->load->library('email');
       
        $result = $this->email
                ->from($from)
                ->reply_to($from) // Optional, an account where a human being reads.
                ->to($to)
                ->subject($subject)
                ->message($body)
                ->send();
        return $result;          
    }        
    
    function generateApiToken($id,$email,$password)
    {
        //-- generate token --//
            $token = sha1($email.$password.rand()); 
        //-- delete api token --//
            $this->User_model->delete_api($id);
        //-- add api token ---//    
            $data = array('token'=>$token,'owner_id'=>$id);
            $this->User_model->add_apikey($data);
            //$this->User_model->update_user($token,$id);
        //-------------------//
    }
}
