<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
		//echo '<pre>';print_r($user_data);die;
		//-- get radius for user location --//
		if($id !=38){  //- check if newsnation no ads display --//
		$adsAlloc = $this->Ads_model->getUserLocationWiseAds($lat,$lng,$_GET['user_id'],$user_data,$limit);
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
		}
		}
		//----------------------------------//
		//echo '<pre>';print_r($adsFinal);die;
		//echo '<pre>';print_r($keywords);die;
		$this->load->helper('url');
                
                $device = $_GET['device'];
		$this->data['user_id'] = $_GET['user_id'];
		
		
		if($type=='live'){
			$this->data['result'] =  $this->Video_model->livestream_play($id,$device);	
		}else{
			$this->data['result'] = $result = $this->Video_model->channel_play($id);
			//$result = $this->Video_model->video_play($id,$device);			
		/*	if($result){
				$this->data['result'] =$result;	
			}else{
				$this->data['result'] = $this->Video_model->video_play_youtube($id,'youtube');
			}
			*/
		}
		$this->data['scheduleBreaks'] = $adsFinal;       
                $this->load->view('details',$this->data);
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
