<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smart_analytics extends MY_Controller {
	function index(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
         
    function Users(){
			
		$data['welcome'] = $this;
		$this->show_view('analytics_user',$data);
	}
        
    function Loyalty(){
		$data['welcome'] = $this;
		$this->show_view('analytics_loyalty',$data);
	}
        
    function Sessions(){
		$data['welcome'] = $this;
		$this->show_view('analytics_session',$data);
	}
        
    function Frequency(){
		$data['welcome'] = $this;
		$this->show_view('analytics_frequency',$data);
	}
        
    function Countries(){
		$data['welcome'] = $this;
		$this->show_view('analytics_countries',$data);
	}
        
    function Devices(){
		$data['welcome'] = $this;
		$this->show_view('analytics_device',$data);
	}
        
    function Versions(){
		$data['welcome'] = $this;
		$this->show_view('analytics_version',$data);
	}
        
    function Carrier(){
		$data['welcome'] = $this;
		$this->show_view('analytics_carrier',$data);
	}
        
    function Platforms(){
		$data['welcome'] = $this;
		$this->show_view('analytics_platforms',$data);
	}

}