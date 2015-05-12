<?php

class Push_notification extends My_Controller{

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        //$this->load->model('help_model');
         $this->load->model('push_notification/Push_notification_model');
         $this->load->helper('common');
        $this->load->library('session');
      //  $this->load->library('form_validation');
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->userdetail=(array)$s[0];
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
        $this->allowedVideoExt = array('mp4', 'mpg', 'mpeg', 'flv', 'wmv', 'avi');
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
    }

	function index(){
    $data['welcome'] = $this;
	$data['time_zones'] = unserialize(TIME_ZONE_LIST);
	
    //Post message to GCM when submitted
	$pushStatus = "GCM Status Message will appear here";
	//$regId = array('apns'=>'1461aa5ee0613a47a748deb1051c6b2d30378b634418ecf7729ac19f7d95d85e','gcm'=>'APA91bEfsmtpPO6VJBN5gxTxXJvKiUlnEtJG0H56RHf_JpUE63t-YZ2VX1PiJIuFrJBYCDtP_5GwSoBRzUfc0UbWaUR9M04kORnOtqR-r_3hubylp0MqZNhSy03h2lPnUUJCxU7z97_7Wct-168uzwCRv7CAJO5t9Q');
	  
	if($_POST) {
		
		//$gcmRegID  = file_get_contents("GCMRegId.txt");
       // $gcmRegID = $_POST["regId"];
	   $timestamp = strtotime("now");
	   $uniquid = uniqid($timestamp);
		
		$pushMessage = $_POST["message"];		
		$result = $this->Push_notification_model->push_notification_data($_POST);		
		//-- schedule notification ---//
			if($_POST['delivery_time'] ==2){										
				//example usage
				$date = $_POST['date'];//'2015-05-11 21:53:01';
				$time = $_POST['time'];
				$user_timezone = $_POST['timezone'];
				$scheduleDate = $date.' '.$time;
				//-- convert user time into server time ---//
				$servertime = $this->convert_to_server_date($scheduleDate,'Y-m-d H:i:s',$user_timezone);
				$schedule_data['device_ids'] = serialize($result);
				$schedule_data['message'] = $pushMessage;
				$schedule_data['schedule_time'] = $servertime;				
				$schedule_data['status'] = 'pending';				
				$id = $this->Push_notification_model->schedule_notification($schedule_data);
				if($id){
					$this->session->set_flashdata('message', $this->_successmsg('Notification schedule successfully.'));
					//redirect('push_notification');
				}else{
					$this->session->set_flashdata('message', $this->_errormsg('Error scheduling notification'));
				}
			}
		//--------------------------//		
		foreach($result as $key=>$value)
		{
			if($key=='ios'){
				//for($i=0;$i<count($value);$i++){		
					$deviceToken = $value;
					$this->apns($deviceToken,$pushMessage,$uniquid);
				//}
			}
			if($key=='android'){
				$gcmRegIds = $value;
				//  print_r($gcmRegIds);die;
				$message = array("m" => $pushMessage);	
				$pushStatus = $this->sendMessageThroughGCM($gcmRegIds, $message,$uniquid);
			}
		}		
		$this->session->set_flashdata('message', $this->_successmsg('Notification send successfully.'));
		redirect('push_notification');
	}	
	
	//Get Reg ID sent from Android App and store it in text file
	/*if(!empty($_GET["shareRegId"])) {
		$gcmRegID  = $_POST["regId"]; 
		file_put_contents("GCMRegId.txt",$gcmRegID);
		echo "Done!";
		exit;
	}    */
	$this->show_view('pushnotification/send',$data);
    }
    
	function apns($deviceToken,$message,$uniquid)
	{
		// Put your device token here (without spaces):
		//$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
		//$deviceToken = $_POST["regId"];
		// Put your private key's passphrase here:
		//$passphrase = APNS_PASSPHRASE;		
		// Put your alert message here:
		//$message = $_POST["message"];
		// Put your device token here (without spaces):
		
		////////////////////////////////////////////////////////////////////////////////		
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', APNS_CERT);
		stream_context_set_option($ctx, 'ssl', 'passphrase', APNS_PASSPHRASE);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		
		echo 'Connected to APNS' . PHP_EOL;
		//print_r($deviceToken);die;
		//- for loop to send multiple device --//
		for($i=0;$i<count($deviceToken);$i++){
			$token = $deviceToken[$i];
		// Create the payload body
		$body['aps'] = array(
			'id' => $uniquid,
			'alert' => $message,
			'sound' => 'default'
			);		
		// Encode the payload as JSON
		$payload = json_encode($body);
		
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
		//$msg = chr(1) . pack("N", $msg_id) . pack("N", $expiry) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		}
	
		//--- insert in history databse ---//
		$data['push_id'] = $uniquid;
		$data['type'] = 'Push';
		$data['message'] = $_POST["message"];
		$data['platform'] = 'ios';
		$data['audience'] = $_POST['notification_type'];
		$data['sent_count'] = count($deviceToken);		
		$this->Push_notification_model->save($data);		
		//------------------------//
	
	
		/*$apple_error_response = fread($fp, 6); //byte1=always 8, byte2=StatusCode, bytes3,4,5,6=identifier(rowID). Should return nothing if OK.
        //NOTE: Make sure you set stream_set_blocking($fp, 0) or else fread will pause your script and wait forever when there is no response to be sent.

        if ($apple_error_response) {

            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response); //unpack the error response (first byte 'command" should always be 8)
print_r($error_response);
		}*/
			
		//print_r($result);die;
		//if (!$result)
		//	echo 'Message not delivered' . PHP_EOL;
		//else
			//echo 'Message successfully delivered' . PHP_EOL;
		
		// Close the connection to the server
		fclose($fp);
		
		//$this->load->view('apns');
	}
	
    //Generic php function to send GCM push notification
   function sendMessageThroughGCM($deviceToken, $message,$uniquid) {
		//Google cloud messaging GCM-API url
		//--- insert in history databse ---//
		$data['push_id'] = $uniquid;
		$data['type'] = 'Push';
		$data['message'] = $_POST["message"];
		$data['platform'] = 'android';
		$data['audience'] = $_POST['notification_type'];
		$data['sent_count'] = count($deviceToken);	
		//------------------------//	
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
			'id' => $uniquid,
            'registration_ids' => $deviceToken,
            'data' => $message,
        );
      
		// Update your Google Cloud Messaging API Key
		//define("GOOGLE_API_KEY", GOOGLE_API_KEY); 		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);				
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        $result = json_decode($result);
        if($result->success)
        {
			//-- insert data --//
			$this->Push_notification_model->save($data);
			//----------------//
           // echo "Notification send successfully";
        }else{
            //echo "Error send notification";
        }
   }
   
   function convert_to_server_date($date, $format = 'Y-m-d H:i:s', $userTimeZone = SERVER_TIME_ZONE, $serverTimeZone = SERVER_TIME_ZONE)
	{		
		try {
			$dateTime = new DateTime ($date, new DateTimeZone($userTimeZone));
			$dateTime->setTimezone(new DateTimeZone($serverTimeZone));
			return $dateTime->format($format);
		} catch (Exception $e) {
			return '';
		}
	}
}