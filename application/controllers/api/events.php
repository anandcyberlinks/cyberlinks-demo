<?php defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Origin: *");
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

class Events extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Events_model');
       $this->load->model('api/User_model');
	   $this->load->helper('common');
        //-- validate token --//
        $token = $this->get('token');
        $this->owner_id =  $this->User_model->checkAdminToken($token);	                
        if($this->owner_id <= 0 || $token=='')
        {                
            $response_arr = array('code'=>0,'error' => "Invalid Token");               
            $this->response($response_arr, 404);
        }		
		 //--paging limit --//
          $this->param =  $this->paging($this->get('p'));
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
       
    function categories_get()
    {
        $result = $this->Events_model->getCategories();
        if(isset($result) && count($result) > 0)
        {
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
    function list_get()
    {
        $cid = $this->get('id');
        $userid = $this->get('user_id');
        $result = $this->Events_model->categoryEvents($cid,$userid,$this->param);		
		if(isset($result) && count($result) > 0)
        {
            $newresult = array();
            foreach($result as $key => $val){                
                if($val->thumbnail ==''){
                    $val->thumbnail = base_url().IMG_PATH.'no-image.jpg';    
                }
                //error_reporting(E_ALL);                   
                    unset($val->event_code);                    
                    if($val->channel_id !=''){
                    $newresult[$val->category_name][] = $val;
                    }else{
                        $newresult[$val->category_name] =array();
                    }
            }
		}
        if(isset($newresult) && count($newresult) > 0)
        {        
            $this->response(array('code'=>1,'result'=>$newresult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
	function subscription_list_get()
	{
        $userid = $this->get('user_id');
		$result = $this->Events_model->subscribeEventsList($userid,$this->param);
		if(isset($result) && count($result) > 0)
        {
            $newresult = array();
            foreach($result as $key => $val){                
                if($val->thumbnail ==''){
                    $val->thumbnail = base_url().IMG_PATH.'no-image.jpg';    
                }
                //error_reporting(E_ALL);                   
                    unset($val->event_code);                    
                    if($val->channel_id !=''){
                    $newresult[$val->category_name][] = $val;
                    }else{
                        $newresult[$val->category_name] =array();
                    }
            }
		}
        if(isset($newresult) && count($newresult) > 0)
        {        
            $this->response(array('code'=>1,'result'=>$newresult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
	}
		
	function watched_list_get()
	{
        $userid = $this->get('user_id');
		$result = $this->Events_model->watchedEventsList($userid,$this->param);
		if(isset($result) && count($result) > 0)
        {
            $newresult = array();
            foreach($result as $key => $val){                
                if($val->thumbnail ==''){
                    $val->thumbnail = base_url().IMG_PATH.'no-image.jpg';    
                }
                //error_reporting(E_ALL);                   
                    unset($val->event_code);                    
                    if($val->channel_id !=''){
                    $newresult[$val->category_name][] = $val;
                    }else{
                        $newresult[$val->category_name] =array();
                    }
            }
		}
        if(isset($newresult) && count($newresult) > 0)
        {        
            $this->response(array('code'=>1,'result'=>$newresult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
	}
	
    function add_post(){
		 //--- validate user token ---//        
            $this->validateToken();
        //------------------------//	
        $ext = $this->post('ext');
        $my_base64_string = $this->post('pic');				
        //---- Upload logo image for user --//
        if($ext !=''){
	    if($my_base64_string !=''){
		$pic = $this->base64_to_jpeg( $my_base64_string, EVENTPIC_PATH,$ext );
		if($pic == 0){
		    $this->response(array('code'=>0,'error' => "Error uploading image."), 404);
		}
	    }
        }else{
                $incoming_tmp = @$_FILES['pic']['tmp_name'];
                $incoming_original = @$_FILES["pic"]["name"];
                if ($incoming_original != '') {                    
                    $path = EVENTPIC_PATH;
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
        $characters = 'ab0c1d2ef3gh4i5jkl7mn8opqrs9tuvw6xyz';
        $random_key = '';
        for ($i = 0; $i < 4; $i++) {
             $random_key .= $characters[rand(0, strlen($characters) - 1)];
        }
		if($this->post('source')=='external'){
			$url = $this->post('url');
		}else{
			$url = EVENT_URL.$this->post('u_token').$random_key;
		}
		
		$data = array('event_code' =>$random_key,
				'source' => $this->post('source'),
				'name' => $this->post('name'),
				'description' => ($this->post('description') !='' ? $this->post('description'):""),
				'category_id' => $this->post('category'),
				'type' => 'Live',
				'keywords' => $this->post('tags'),
				'status'=>0,
				'access' => $this->post('access'),
				'uid' => $this->owner_id,
				'customer_id' => $this->post('user_id'),
				'rtsp' => $url,
				'thumbnail' => $pic,
				'start_date' => $this->post('start_date'),
				'start_time' => $this->post('start_time'),
				'end_date' => $this->post('end_date'),
				'end_time' => $this->post('end_time'),
				'show_duration' => $this->post('duration'),
				'susbcribe_ids' => $this->post('subscribe_ids')
				 );
		
	    if(isset($pic) && $pic !='' && $pic != 0){
               $data['thumbnail']=base_url().EVENTPIC_PATH.$pic;
        }
		
		if($this->post('source')=='external'){			
			 $data['ios'] =str_replace('rtsp://','http://',$this->post('url')).'/playlist.m3u8';
			 $data['android'] =str_replace('rtsp://','http://',$this->post('url')).'/playlist.m3u8';
			 $data['windows'] =str_replace('rtsp://','http://',$this->post('url')).'/Manifest';
			 $data['web'] =str_replace('rtsp://','rtmp://',$this->post('url'));
		}else{
		    $data['ios'] = EVENT_URL_MOBILE.$this->post('u_token').$data['event_code'].'/playlist.m3u8';
            $data['android'] = EVENT_URL_MOBILE.$this->post('u_token').$data['event_code'].'/playlist.m3u8';
            $data['windows'] = EVENT_URL_MOBILE.$this->post('u_token').$data['event_code'].'/Manifest';
            $data['web'] = EVENT_URL_WEB.$this->post('u_token').$data['event_code'];
           //echo '<pre>'; print_r($data); exit;
		}
           $id = $this->Events_model->saveEvents($data);          
           
           if(isset($id) && $id >0){
				//-- get event detail --//
				$result = $this->Events_model->getEventDetail($id);			
               $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
           }else{
               $this->response(array('code'=>0,'result'=>'Error creating event'), 201);
           }
    }
	
	function publish_post()
	{
		//--- validate user token ---//        
            $this->validateToken();
        //------------------------//
		//$id = $this->post('id');
		//$status = $this->post('status');
		$otp = mt_rand(100000, 999999);
		$data['user_id'] = $this->post('user_id');
		$data['channel_id'] = $this->post('event_id');		
		$data['otp'] = $otp;
		$email = $this->post('email');
		$id = $this->Events_model->publishEvent($data);
		
		if($id){
		 //-- send confirmation mail --//               
                $subject = 'Multitv Publish Evnet OTP';
                $message = '<p>'.$otp.' is Onetime password(OTP) for publish your event.</p>';
                $message .= '<p>This is usable once and valid for 10 minutes</p>';                
                $message .= "<br><br> Kind Regards,<br><br>";
                $message .= "MultiTv Team";
                $to = $email;
                $from = 'admin@multitv.in';
               
				if($this->sendmail($subject,$message,$from,$to))  //-- common helper function
				{
					$this->response(array('code'=>1), 200); // 200 being the HTTP response code  
				}else{
					$this->response(array('code'=>0,'result'=>'Sending mail failed'), 200); 
				}
				$this->response(array('code'=>1), 200); // 200 being the HTTP response code 
		}else{
				$this->response(array('code'=>0,'result'=>'Error publish event'), 200); 
		}
	}
	
	function verify_otp_post(){
		$post = $this->post();
		//$otp = $this->post('otp');
		//$id = $this->post('event_id');
		$result = $this->Events_model->checkOtp($post);
		if($result){
			//--- send push notification ---//
			$data['device_type']  = array('ios','android');
			$data['notification_type'] = 'broadcast';
			$data['message'] = $result->channel_name." Event is launching on ". $result->date." at ".$result->show_time.". Hurry!! to Subscribe for the event";			
			$this->push_notification($data);
			//-----------------------------//			
			$this->response(array('code'=>1), 200); // 200 being the HTTP response code 
		}else{
			$this->response(array('code'=>0,'result'=>'Incorrect OTP'), 201); 
		}
	}
	
	function push_notification($data)
	{
		$this->load->helper('push');
		$this->load->model('push_notification/Push_notification_model');		
		$result = $this->Push_notification_model->push_notification_data($data);		
		$timestamp = strtotime("now");
		$uniquid = uniqid($timestamp);		
		if($result){
		foreach($result as $key=>$value)
		{
			if($key=='ios'){
				//for($i=0;$i<count($value);$i++){		
					$deviceToken = $value;
					
					$result = apns($deviceToken,$data["message"],$uniquid); //-- helper function --//
					if (!$result)
					{
						//$this->response(array('code'=>0,'result'=>'Failed to connect APNS'), 201);						
					}else{
						//--- insert in history databse ---//
						$data_history['push_id'] = $uniquid;
						$data_history['type'] = 'Push';
						$data_history['message'] = $data["message"];
						$data_history['platform'] = 'ios';
						$data_history['audience'] = $data['notification_type'];
						$data_history['sent_count'] = count($deviceToken);		
						$this->Push_notification_model->save($data_history);	
						//------------------------//
					}
				//}
			}
			if($key=='android'){
				$gcmRegIds = $value;
				//echo '<pre>';  print_r($gcmRegIds); die;
				$message = array("m" => $pushMessage);
                                $new_gcmRegIds = array_chunk($gcmRegIds, NOTIFICATION_DEVICE_CHUNK);
                                foreach($new_gcmRegIds as $gcmIdArray){
                                    $pushStatus = sendMessageThroughGCM($gcmIdArray, $message,$uniquid);
                                } 
				//$pushStatus = sendMessageThroughGCM($gcmRegIds, $message,$uniquid);				
				if(!$pushStatus)
				{
					//$this->session->set_flashdata('message', $this->_errormsg("Failed to connect GCM"));													
				}else{
					//--- insert in history databse ---//
					$data_history['push_id'] = $uniquid;
					$data_history['type'] = 'Push';
					$data_history['message'] = $data["message"];
					$data_history['platform'] = 'android';
					$data_history['audience'] = $data['notification_type'];
					$data_history['sent_count'] = count($gcmRegIds);	
					//------------------------//
					//print_r($data_history);die;
					//-- insert data --//
					$this->Push_notification_model->save($data_history);
				}		
			}
		}
		}
	}
	function subscribe_post()
	{
		//--- validate user token ---//        
            $this->validateToken();
        //------------------------//
		$user_id = $this->post('user_id');
		$event_id = $this->post('event_id');		
		$result = $this->Events_model->subscribe($user_id,$event_id);
		if($result >0){
			$this->response(array('code'=>1), 200); // 200 being the HTTP response code 
		}else{
			$this->response(array('code'=>0,'result'=>'Error in Subscription'), 201); 
		}	
	}
	
	function like_post(){
		//--- validate user token ---//        
            $this->validateToken();
        //------------------------//
		$data['content_id'] = $this->post('event_id');
		$data['user_id'] = $this->post('user_id');
		$data['like'] =  $this->post('like');
		$data['type'] = 'Live';
		$result = $this->Events_model->like($data);
		if($result >0){
			$this->response(array('code'=>1), 200); // 200 being the HTTP response code 
		}else{
			$this->response(array('code'=>0,'result'=>'Error'), 201); 
		}	
	}
	
	function watch_post()
	{
		//--- validate user token ---//        
            $this->validateToken();
        //------------------------//
		$data['user_id'] = $this->post('user_id');
		$data['channel_id'] = $this->post('event_id');		
		$result = $this->Events_model->watched($data);
		if($result >0){
			$this->response(array('code'=>1), 200); // 200 being the HTTP response code 
		}else{
			$this->response(array('code'=>0,'result'=>'Error in Subscription'), 201); 
		}
	}
}