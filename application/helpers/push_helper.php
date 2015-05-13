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
		   $this->session->set_flashdata('message', $this->_errormsg("Failed to connect GCM"));
			//exit("Failed to connect: $err $errstr" . PHP_EOL);
			redirect('push_notification');
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
   ?>