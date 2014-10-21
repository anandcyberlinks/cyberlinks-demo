<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Details extends MY_Controller {

	function __construct()
	{
            parent::__construct();
            $this->load->model('/api/Video_model');
	    $this->load->model('ads/Ads_model');
	}

	function index()
	{
		$this->load->helper('url');
                $id = $_GET['id'];
                $device = $_GET['device'];
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
