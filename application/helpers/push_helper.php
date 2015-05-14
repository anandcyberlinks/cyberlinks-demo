<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function sendMessageThroughGCM($deviceToken, $message,$uniquid) {
		//Google cloud messaging GCM-API url		
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
           // die('Curl failed: ' . curl_error($ch));
		  return false;
          // $this->session->set_flashdata('message', $this->_errormsg("Failed to connect GCM"));
			//exit("Failed to connect: $err $errstr" . PHP_EOL);
			//redirect('push_notification');
        }
        curl_close($ch);
        return $result = json_decode($result);
       /* if($result->success)
        {
			//-- insert data --//
			//$this->Push_notification_model->save($data);
			//----------------//
           // echo "Notification send successfully";
        }else{
            //echo "Error send notification";
        }*/
   }
   
   
   function apns($deviceToken,$message,$uniquid)
	{		
		////////////////////////////////////////////////////////////////////////////////		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', APNS_CERT);
		stream_context_set_option($ctx, 'ssl', 'passphrase', APNS_PASSPHRASE);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client(
			'ssl://gateway.sandbox.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp){
            return false;
			//$this->session->set_flashdata('message', $this->_errormsg("Failed to connect APNS"));
			//exit("Failed to connect: $err $errstr" . PHP_EOL);
			//redirect('push_notification');
		}
		//echo 'Connected to APNS' . PHP_EOL;
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
		return $result = fwrite($fp, $msg, strlen($msg));        
		}
	
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
   ?>