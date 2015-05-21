<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_analytics extends MY_Controller {
	function index(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}
        
        function Users(){
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