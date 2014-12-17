<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details extends MY_Controller {

	function __construct()
	{
            parent::__construct();
            $this->load->model('/api/Video_model');
	    $this->load->model('ads/Ads_model');
	  //  $this->load->model('/analytics/Analytics_model');
	    $this->load->library('User_Agent');
	    $this->load->helper('common');
	    
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
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$result = json_decode($data,true);
		//echo '<pre>';print_r($result);die;
		$this->data['geodata'] = $result['results'][0]['address_components'];
		//------------------------------//
		
		$this->load->helper('url');
                $id = $_GET['id'];
                $device = $_GET['device'];
		$this->data['user_id'] = $_GET['user_id'];
                $this->data['result'] = $this->Video_model->video_play($id,$device);         
		$this->data['scheduleBreaks'] = $this->Ads_model->getAdsScheduleBreaks();       
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
