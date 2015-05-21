<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_analytics extends MY_Controller {
	function index(){
		$data['welcome'] = $this;
		$this->show_view('newdashboard',$data);
	}

}