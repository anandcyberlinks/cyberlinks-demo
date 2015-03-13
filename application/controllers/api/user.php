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
       $this->admin_token = $this->get('token');
      //$this->admin_token = '54d46a72bab49';
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
    
    function checkdevice_get()
    {
	 $id = $this->get('uniqueID');
	 $result = $this->User_model->validate_device($id);
	if($result >0){
            $this->response(array('code'=>1, 'id' => $result), 200);
        }  else {
            $this->response(array('code'=>0,'error' => "No record found"), 404);
        }
    }
    
    function add_post()
    {
       $token = sha1($this->post('email').$this->post('password').rand());
       
       //-- check if Admin token is valid --//
	    $owner_id =  $this->User_model->checkAdminToken($this->admin_token);
	    if($owner_id <= 0){
		$this->response(array('code'=>0,'error' => "Invalid Token"), 404);
	    }
       //-----------------------------------//
       
       $ext = $this->post('ext');
       $my_base64_string = $this->post('pic');
        //---- Upload logo image for user --//
        if($ext !=''){
	    if($my_base64_string !=''){
		$pic = $this->base64_to_jpeg( $my_base64_string, PROFILEPIC_PATH,$ext );
		if($pic == 0){
		    $this->response(array('code'=>0,'error' => "Error uploading image."), 404);
		}
	    }
        }else{
                $incoming_tmp = @$_FILES['pic']['tmp_name'];
                $incoming_original = @$_FILES["pic"]["name"];
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
	    'owner_id' => $owner_id,
	    'username' => $this->post('email'), 
            'first_name' => $this->post('firstname'), 
            'last_name' => $this->post('lastname'),
            'gender' => $this->post('gender'),
            'email' => $this->post('email'), 
            'password' => $this->post('password'),
            'contact_no' => $this->post('phone'),                     
            'status' => 'inactive'           
            );
                
	    if($pic !='' && $pic != 0){
               $data['image']=base_url().PROFILEPIC_PATH.$pic;
           }
	   
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
    function detail_get()
    {
        //--- validate token ---//        
            //$this->validateToken();
        //------------------------//
        $id = $this->get('id');
                if(!$id)
                    {
                    
                     $this->response(array('code'=>0,'error' => "Record Not Found."), 404);
                    }
                else{
                   $userProfile = $this->User_model->userprofile($id);
                   //$socialKeywords = json_encode(unserialize($userProfile->keywords));
                    //$userProfile->keywords = $socialKeywords;
                   
                   if($userProfile){
                    $this->response(array('code'=>1,'result'=>$userProfile), 200); // 200 being the HTTP response code
                   }else{
                        $this->response(array('code'=>0,'error' => "Record Not Found."), 404);
                   }
                    
                   }

                
            
    }   
    
    function edit_post()
    {
        //--- validate token ---//        
            //$this->validateToken();
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

                    if (@$output['error']) {
                        $this->response(array('code'=>0,'error' => $output['error']), 404);
                    } else {
                        $pic = $output['path'];
                    }
                } else {
                    //$_POST['file'] = $_POST['logo'];
                }
        }
	//$keywordData = array('keywords'   => $this->post('keywords'));
	$keywordData =  $this->post('keywords');
        $data = array( 
            'first_name' => $this->post('first_name'), 
            'last_name' => $this->post('last_name'),
            'gender' => $this->post('gender'),
	    'dob' => $this->post('dob'),
            'contact_no' => $this->post('contact_no'),
            'location'=>$this->post('location'),
	    'keywords' => trim($keywordData)
            );
                
           if($pic !='' && $pic != 0){
               $data['image']=base_url().PROFILEPIC_PATH.$pic;
           }
	
            $result = $this->User_model->update_user($data,$id);
         //   $result_social = $this->User_model->update_usersocial($keywordData,$id);
            if($result){
		$output = $this->User_model->getuser($id);
		/*if($output->image !=""){
                 //   $output->image = base_url().PROFILEPIC_PATH.$output->image;
     		}*/
		
                $this->response(array('code'=>1,'result'=>$output), 200); // 200 being the HTTP response code
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
        //$token = $this->get('token');
	$token = $this->get('uniqueID');
	$id = $this->get('id');
        $result = $this->User_model->logout_social($token,$id);
	if($result>0)
	    $this->response(array('code'=>1,'result'=>'Logout successfully'), 200); // 200 being the HTTP response code
	else
	    $this->response(array('code'=>0,'result'=>'Logout failed'), 404); // 200 being the HTTP response code
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
	$access_key = $this->post('access_key');
	$uniqueId = $this->post('uniqueID');
        $userdetails = json_decode($this->post('social'));
    //echo $this->post('social');
       //-- check if Admin token is valid --//
	   $owner_id =  $this->User_model->checkAdminToken($this->admin_token);
	  // $owner_id =  $this->User_model->checkAdminToken('54d46a72bab49');
	    if($owner_id <= 0){
		$this->response(array('code'=>0,'error' => "Invalid Token"), 404);
	    }
       //-----------------------------------//
       
       //print_r($userdetails);die;
        if(strtolower($provider)=='facebook'){
            
            $imageUrl = $this->social_data_image($access_key);
            
            $firstname = $userdetails->first_name;
            $lastname = $userdetails->last_name;
            $email = $userdetails->email;
            $gender = $userdetails->gender;
            $socialid = $userdetails->id;
            $password = md5($socialid);
	    $image = $imageUrl;
            $token = sha1($socialid.rand());
	    $age = $userdetails->dob;
	    
	    //-- get user keywords --//
	    $social_keywords = $this->social_data($id,$socialid,$access_key);
        }
       
        if(strtolower($provider)=='google')
        {
	    $social_keywords='';
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
	    $image = $userdetails->image;
	    $age = $userdetails->dob;
            //echo '<pre>'; print_r($userdetails);die;
        }
	//$deviceIdArr = array();
                	//echo $email;;die;
        //-- check if social account is already exist --//
           // $uid = $this->User_model->loginuser($email, $password);
	   if($email !=''){
            $userdata = $this->User_model->loginsocial($email, $provider,$uniqueId);
	    
	    $uid = $userdata->id;
	    $deviceid = $userdata->device_unique_id;
	    //$deviceIdArr = unserialize($userdata->device_unique_id);
	    //print_r(unserialize($deviceIdArr));
	   }	   
	   
            if($uid>0){		
		//if(!in_array($uniqueId,$deviceIdArr)){
		//    array_push($deviceIdArr,$uniqueId);
		//}
		//--- insert device unique id ---//
		if($deviceid != $uniqueId){
		 $uniqueData = array('device_unique_id'=>$uniqueId,'user_id'=>$uid);
		 $this->User_model->userDeviceID($uniqueData);
		}
		//---------------------//
		
                //$this->User_model->userDeviceID($uniqueId,$uid);
		//$this->User_model->userDeviceID(serialize($deviceIdArr),$uid);
              //-- api token --//
               $this->generateApiToken($uid,$email,$socialid);
               $result = $this->User_model->getuser($uid);	       
               $this->response(array('code'=>1,'result'=>$result), 200); 
            }else{
			    
            //-----------Register user-----------------//
            $userdata = array(
	    'owner_id' => $owner_id,
            'first_name' => $firstname, 
            'last_name' => $lastname,
            'gender' => $gender,
            'email' => $email, 
            'password' => $password,
	    'image' => $image,
	    'age' => $age,
	    //'device_unique_id' => $uniqueId,
	    'device_unique_id' => serialize($deviceIdArr),
	    'keywords'=>$social_keywords,
            //'token' => $token,
            'status' => 'active'
            //'created'=>date('Y-m-d h:i:s')
                );
           
             $id = $this->User_model->adduser($userdata);
            
             if($id){		
		//--- insert device unique id ---//
		 $uniqueData = array('device_unique_id'=>$uniqueId,'user_id'=>$id);
		 $this->User_model->userDeviceID($uniqueData);
		//---------------------//
		
		/*
                 if($provider=='facebook'){
                 //-- social Data Keywords 
                $social_keywords = $this->social_data($id,$socialid,$access_key);
                 }else
                     {
                        $social_keywords = '';
                     }*/
               $socialdata = array('social_id' => $socialid, 
               'from' => $provider,            
               'user_id' => $id,  
               'info' => serialize($userdetails),
               'status' => 1,
               // 'keywords'=>$social_keywords,
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
    
    function social_data($id,$userFacebookId,$access_key)
    {
      //  $userFacebookId = "799074976840826";
         $facebookUrl = "https://graph.facebook.com/".$userFacebookId."/likes?limit=10000&access_token=";
        // $facebookUrl = "https://graph.facebook.com/709713455756798/likes?access_token=CAACEdEose0cBAKicFwxfQWp6JGnIrRP6BCkBn8xxKgqsWtpwTEKNZCQUZCOt8vtfcTb3uEkmeZCl4Ib52RrN5vRyHPEYGYIbMwLDVZCoVaXRbBZAEUFdtmCZBgsVaiVOOMtwfZAdy1s2fWiBSe2HqkhKOn3sbg0kXXhkGNoRjTCUJgAdedJ0wgS4IoIcIVcfrGyGjtEbOUjopOc2Bl21U1e";
        // $access_key   =  $this->get('access_key');
        // $access_key   =  "CAANjUhhnAOABABSo1ZAbRqPxySiJHyxDXZAhXGcFMCkxQvkspM6ACZADFSiUcA2CzQZAvJXuINUrc6GpzU2948ynZBnjPVZCMFj2hMPXGCbKj8jmgl8ZCqhMqKfdkd3VbaV0ZAZBXL2OwrglIJ48jnIRLMavZArmgiCTIFckC6gK12S6vWdRvjR2jMPfiI4B4hZClSmv7HP48vJWFZCtzvgNQZBpCJFKUjjZAZCB5KTH2PSYyIWXzs7ZB6wnvig9";
         $facebookJsonData = $facebookUrl.$access_key;
          $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $facebookJsonData
                ));  
                // Send the request & save response to $resp
               $resp = curl_exec($curl);
              // echo $resp;
              // die();
                // Close request to clear up some resources
                curl_close($curl);
         //echo $facebookJsonData;
                //echo "<pre>";
        $facebookDataArr = json_decode($resp);
        $outputArray = array(); 
        foreach($facebookDataArr as $k=>$v )
            {
                foreach($v as $key=>$val)
                    {
                        if(isset($val->name)){
                            array_push($outputArray, $val->name);   
                        }
                    }
            }
	    
	    $outputArray = implode(",",$outputArray);	   
            return $insertArray = $outputArray;          
    }
    function social_data_image($access_key)
    {
        //$userFacebookId = "709713455756798";
         $facebookUrl = "https://graph.facebook.com/me/picture?&redirect=false&width=480&height=480&access_token=";
         $facebookJsonData = $facebookUrl.$access_key;
          $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $facebookJsonData
                ));  
                // Send the request & save response to $resp
               $resp = curl_exec($curl);
              // echo $resp;
              // die();
                // Close request to clear up some resources
                curl_close($curl);
         //echo $facebookJsonData;
                //echo "<pre>";
        $facebookDataArr = json_decode($resp);
        //print_r($facebookDataArr);
        //die();
        foreach($facebookDataArr as $k=>$v)
            {
            $imageUrlFetch = $v->url;
            }
           // echo $imageUrlFetch;
          //  die();
        //echo $facebookDataArr->url;
        ///die();
         return $imageUrl = $imageUrlFetch; 
        
            
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
            $error ="First name cannot be blank";             
        }
        if($data['email']==''){
            $error ="Email cannot be blank";             
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "This email address is invalid.";
        }
        
        if($data['password']==''){
            $error ="Password cannot be blank";             
        }
        if(!empty($error)){
            $this->response(array('code'=>0,'error' => $error), 404);
        }
        return 1;
    }
    
    public function validatelogin($data)
    {
        $error=array();       
        if($data['email']==''){
            $error ="Email cannot be blank";             
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error = "This email address is invalid.";
        }        
        if($data['password']==''){
            $error ="Password cannot be blank";             
        }
        if(!empty($error)){
           $this->response(array('code'=>0,'error' => $error), 404);
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
            $data = array('token'=>$token,'user_id'=>$id);
            $this->User_model->add_apikey($data);
            //$this->User_model->update_user($token,$id);
        //-------------------//
    }
    
    function add_switch_user_post()
    {
        $data   =  array(
            "username" =>$this->post('login'),
            "password"=>  md5($this->post('passwd')),
            "adserver_user_id"=>$this->post('userid'),
            'first_name'=>$this->post('contact_name'),
            'email'=>$this->post('email_address'),
        );
        
        echo '<pre>';        print_r($data); exit;
        $id = $this->User_model->addSwitchUser($data);
        if($id>0){
            $this->response(array('success' => "successfully added."), 200);
        }  else {
            $this->response(array('error' => "error."), 404);
        }

    }
}
