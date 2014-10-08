<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MY_Controller {

	function __construct(){
		parent::__construct();
		$this->load->config('messages');
		$this->load->library('session');
	}
	
	function index(){
		//$data['layout']=$this; 
		$user_lang = $this->uri->segment(3); 				
		if(isset($user_lang)){
			$lan=$user_lang;
		} else {
			$lan='eng';								   
		}					
		$this->session->set_userdata('lan',$lan);
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$redirect_to = str_replace(base_url(),'',$_SERVER['HTTP_REFERER']);
			$this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_language_change'))));
		}
		else
		{
			$redirect_to = $this->uri->uri_string();
		}          
		redirect($redirect_to);  
	//$this->show_view('dashboard',$data);
	}
 }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */