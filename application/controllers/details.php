<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Details extends MY_Controller {

	function __construct()
	{
            parent::__construct();
            $this->load->model('/api/Video_model');
	    $this->load->model('ads/Ads_model');
	  //  $this->load->model('/analytics/Analytics_model');
	    $this->load->library('User_Agent');	    
	    
	//-- get browser http_user_agent info in array --//
                //   $this->result = get_browser(null, true);		
		$this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		// print_r($_SERVER[HTTP_USER_AGENT]);die;
        //---------------------//
	}

	function index()
	{
		//-- get geocoding google api --//
		$this->data['lat'] = $lat = $_GET['lat'];
		$this->data['long'] = $lng = $_GET['lng'];
		$this->data['platform'] = $_GET['platform'];
		$id = $_GET['id'];
		$type= $_GET['type'];
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$result = json_decode($data,true);
		//echo '<pre>';print_r($result);die;
		$this->data['geodata'] = $result['results'][0]['address_components'];
		//------------------------------//
		//-- get cue points for ads --//
		
		$limit = $this->Ads_model->getCuePoints($id,$type,1);
		$cuePoints = $this->Ads_model->getCuePoints($id,$type);
		//----------------------------//
		
		$user_data = $this->Ads_model->getUserKeywords($_GET['user_id']);
		
		//--- Access Revive web service ---//
		$gender = $user_data['gender'];
		$dob = $user_data['dob'];
		$from = new DateTime($dob);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$keywords = $user_data['keywords'];
		
		$adsAlloc = $this->getAdsRevive($lat,$lng,$age,$keywords,$gender,$limit);
		echo '<pre>';print_r($adsAlloc);
		//--------------------------------//
		
		//echo '<pre>';print_r($user_data);die;
		//-- get radius for user location --//
		if($id !=38){  //- check if newsnation no ads display --//
			
		//-- Revive ad assing cue points array ---//
		$i=0;
		foreach($adsAlloc->url as $key=>$val)
		{
			$adsFinal[$i]['vast_file'] = $val;
			$adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
			$i++;
		}
		echo '<pre>';print_r($adsFinal);die;
		//---------------------------------------//
		
		/*$adsAlloc = $this->Ads_model->getUserLocationWiseAds($lat,$lng,$_GET['user_id'],$user_data,$limit);
		//echo '<pre>';print_r($adsAlloc);die;
		$i=0;
		foreach($adsAlloc as $row){
			
			$adsFinal[$i]['file_name'] 	= $row->file_name;
			$adsFinal[$i]['vast_file'] 	= $row->vast_file;
			$adsFinal[$i]['ads_id'] 	= $row->ads_id;
			$adsFinal[$i]['uid'] 		= $row->uid;
			$adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
			$adsFinal[$i]['ad_type'] 	= $row->ad_type;
			$i++;
		}*/
		}
		//----------------------------------//
		//echo '<pre>';print_r($adsFinal);die;
		//echo '<pre>';print_r($keywords);die;
		$this->load->helper('url');
              
		 $device = $_GET['device'];
		 $network = $_GET['network'];
		 $platform =$_GET['platform'];
		$this->data['user_id'] = $_GET['user_id'];
			//echo '<pre>';print_r($_SERVER);die;
		$this->data['uri'] = "http://".$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
		if($type=='live'){
			$this->data['result'] =  $this->Video_model->livestream_play($id,$device);	
		}else{
			$result = $this->Video_model->channel_play($id);
			//print_r($result);
			$urlArray = json_decode($result->video_path);			
			$url =  $urlArray[0]->$platform->$network;
			//print_r($result);
			$result->video_path = $url;
			//echo '<pre>';print_r($result);die;
			//$result = $this->Video_model->video_play($id,$device);			
		/*	if($result){
				$this->data['result'] =$result;	
			}else{
				$this->data['result'] = $this->Video_model->video_play_youtube($id,'youtube');
			}
			*/
			$this->data['result'] = $result;
		}
		$this->data['scheduleBreaks'] = $adsFinal;       
                $this->load->view('details',$this->data);
	}
	
	function getAdsRevive($lat,$lng,$age,$keywords,$gender,$l)
	{
		$this->load->helper('url');		
                $url = "http://54.179.170.143/vast/getvast.php?keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$l";
                // Get cURL resource
                $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                ));  
                // Send the request & save response to $resp
               $resp = curl_exec($curl);
               
                // Close request to clear up some resources
                curl_close($curl);
                return json_decode($resp);
	}
	
        
        function addview()
        {
            $id = $_GET['id'];
            $this->Video_model->updateView($id);   
            echo 1;
        }
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
