<?php

class Push_notification extends My_Controller{


function index(){
    
    //Post message to GCM when submitted
	$pushStatus = "GCM Status Message will appear here";
	$regId = array('apns'=>'1461aa5ee0613a47a748deb1051c6b2d30378b634418ecf7729ac19f7d95d85e','gcm'=>'APA91bEfsmtpPO6VJBN5gxTxXJvKiUlnEtJG0H56RHf_JpUE63t-YZ2VX1PiJIuFrJBYCDtP_5GwSoBRzUfc0UbWaUR9M04kORnOtqR-r_3hubylp0MqZNhSy03h2lPnUUJCxU7z97_7Wct-168uzwCRv7CAJO5t9Q');
	
	if(!empty($_GET["push"])) {		
		//$gcmRegID  = file_get_contents("GCMRegId.txt");
        $gcmRegID = $_POST["regId"];
		$pushMessage = $_POST["message"];
		
		if(count($_POST['type']) > 1){
			//-- IOS APNS push notification --//
			$this->apns($regId['apns'],$pushMessage);
			//-- Android GCM Push notifictation 
				if (isset($deviceId) && isset($pushMessage)) {		
					$gcmRegIds = array($regId['gcm']);
					//  print_r($gcmRegIds);die;
					$message = array("m" => $pushMessage);	
					$pushStatus = $this->sendMessageThroughGCM($gcmRegIds, $message);
				}
			
		}elseif(count($_POST['type'] == '1')){
			
			$key = $_POST['type'][0];
			$deviceId = $regId[$key];
			if($key == 'apns'){
				$this->apns($deviceId,$pushMessage);
				
			}else if($key == 'gcm')
			{
				if (isset($deviceId) && isset($pushMessage)) {		
					$gcmRegIds = array($deviceId);
					//  print_r($gcmRegIds);die;
					$message = array("m" => $pushMessage);	
					$pushStatus = $this->sendMessageThroughGCM($gcmRegIds, $message);
				}
			}
		}
	}
	
	//Get Reg ID sent from Android App and store it in text file
	if(!empty($_GET["shareRegId"])) {
		$gcmRegID  = $_POST["regId"]; 
		file_put_contents("GCMRegId.txt",$gcmRegID);
		echo "Done!";
		exit;
	}
    $this->load->view('gcm');
    }
    
	function apns($deviceToken,$message)
	{		
		// Put your device token here (without spaces):
		//$deviceToken = '0f744707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bbad78';
		//$deviceToken = $_POST["regId"];
		// Put your private key's passphrase here:
		$passphrase = '9540105334';		
		// Put your alert message here:
		//$message = $_POST["message"];
		// Put your device token here (without spaces):
		
		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'assets/pushnotification/ckCyberlinks.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		
		echo 'Connected to APNS' . PHP_EOL;
		
		// Create the payload body
		$body['aps'] = array(
			'alert' => $message,
			'sound' => 'default'
			);
		
		// Encode the payload as JSON
		$payload = json_encode($body);
		
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		
		if (!$result)
			echo 'Message not delivered' . PHP_EOL;
		else
			echo 'Message successfully delivered' . PHP_EOL;
		
		// Close the connection to the server
		fclose($fp);
		
		//$this->load->view('apns');
	}
	
    //Generic php function to send GCM push notification
   function sendMessageThroughGCM($registatoin_ids, $message) {
		//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
      
		// Update your Google Cloud Messaging API Key
		define("GOOGLE_API_KEY", "AIzaSyAyyorAM4icS2agPLwmEWGJivCiXVfWvHQ"); 		
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
        $data = json_decode($result);
        if($data->success)
        {
            echo "Notification send successfully";
        }else{
            echo "Error send notification";
        }

   }
}