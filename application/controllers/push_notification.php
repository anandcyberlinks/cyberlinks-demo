<?php

class Push_notification extends My_Controller{


function index(){
    
    //Post message to GCM when submitted
	$pushStatus = "GCM Status Message will appear here";	
	if(!empty($_GET["push"])) {	
		//$gcmRegID  = file_get_contents("GCMRegId.txt");
        $gcmRegID = $_POST["regId"];
		$pushMessage = $_POST["message"];	
		if (isset($gcmRegID) && isset($pushMessage)) {		
			$gcmRegIds = array($gcmRegID);
          //  print_r($gcmRegIds);die;
			$message = array("m" => $pushMessage);	
			$pushStatus = $this->sendMessageThroughGCM($gcmRegIds, $message);
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