<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_analytics extends MY_Controller {

	function __construct()
	{
        parent::__construct();
        $this->load->model('/api/Video_model');
	    $this->load->model('/ads/Ads_analytics_model');
		 $this->load->model('analytics/Newanalytics_model');
	    $this->load->library('User_Agent');
	    $this->load->helper('common');
	    $this->load->helper('pdf_helper');
	    $this->load->helper('csv_helper');
	    
	    $this->load->config('messages');
	    $this->data['welcome'] = $this;
	    
	    //$this->load->library('User_Agent');//--regex class to get user agent --//
	    //-- get browser http_user_agent info in array --//
                 //   $this->result = get_browser(null, true);
		    
		   $this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		 
                //---------------------//
		
		$this->load->library('session');
		$s = $this->session->all_userdata();
		$this->user = $s[0]->username;
		$this->uid = $s[0]->id;
		$this->role_id = $s[0]->role_id;
	}


	function index(){
	 	$prepareData = array();
	    $sqlData = array();
		$startDate = "2015-05-19 00:00:00";
		$endDate= "2015-05-19 23:59:59";
		$sqlData['startdate'] 	=	$startDate;
		$sqlData['enddate'] 	=	$endDate;
		
		
		$TotalSession 			=	$this->Newanalytics_model->getTotalSession($sqlData);
		$prepareData['totalsession'] = $TotalSession;
		$TotalUser =	$this->Newanalytics_model->getTotalUser($sqlData);
		$prepareData['totaluser'] = $TotalUser;	
		$NewUser =	$this->Newanalytics_model->getNewUser($sqlData);
		$prepareData['newuser'] = $NewUser;
		$Platform =	$this->Newanalytics_model->getPlatform($sqlData);
		$prepareData['platform'] = $Platform;
		$Resolution =	$this->Newanalytics_model->getResolution($sqlData);
		$prepareData['resolution'] = $Resolution;
		$Carrier =	$this->Newanalytics_model->getCarrier($sqlData);
		$prepareData['carrier'] = $Carrier;
		$TopUser =	$this->Newanalytics_model->getTopUser($sqlData);
		$prepareData['topuser'] = $TopUser;
		$CountryUser =	$this->Newanalytics_model->getCountryUser($sqlData);
		$prepareData['country'] = $CountryUser;
		$TotalSessionDayWise 	= 	$this->Newanalytics_model->getTotalSessionDayWise($sqlData);
		$prepareData['graph']['totalsession'] = $TotalSessionDayWise;
		$TotalUserDaysWaise 	=	$this->Newanalytics_model->getTotalUserDaysWaise($sqlData);
		$prepareData['graph']['totaluser'] = $TotalUserDaysWaise;
		$NewUserPerDay 			= 	$this->Newanalytics_model->getNewUserPerDay($sqlData);
		$prepareData['graph']['totalnewuser'] = $TotalUserDaysWaise;
		$ReturningUserPerDay 			= $this->Newanalytics_model->getReturningUserPerDay($sqlData);
		$prepareData['graph']['returninguser'] = $TotalUserDaysWaise;
		
		$data['welcome'] = $this;
		/* echo "<pre>";
				print_r($prepareData);
				die; */
	    $data['jsondata'] = json_encode($prepareData,true);
		
		$this->show_view('newdashboard',$data);
	}
        
        function Users(){
		
		{
		//$sqlData['startdate'] 	=	$startDate;
		//$sqlData['enddate'] 	=	$endData;
		$userList = array();
		for($startDate = strtotime($startDate); $startDate <= strtotime($endDate); $startDate = strtotime("+1 day", $startDate)) 
		{	$d = date('Y-m-d',$startDate); 
			$userlist[$d]['date'] = $d;
			//$userlist[$d]['month'] = $prepareData['graph']['totaluser']['month'];
			$userlist[$d]['totaluser'] = $prepareData['graph']['totaluser'][$d]['totaluser'];
			$userlist[$d]['totalnewuser'] = $prepareData['graph']['totalnewuser'][$d]['totaluser'];
			$userlist[$d]['returninguser'] = $prepareData['graph']['returninguser'][$d]['totaluser'];
			}
				/* echo "<pre>";
				print_r($userlist);
				die;
				return  $perDayData ; */
		}
		
		
		
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Loyalty(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Sessions(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Frequency(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Countries(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Devices(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Versions(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Carrier(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Platforms(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}

}