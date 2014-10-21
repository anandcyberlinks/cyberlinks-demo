<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirmation extends CI_Controller {

	function __construct()
	{
            parent::__construct();
            $this->load->model('User_model');
	}

	function index()
	{
		$this->load->helper('url');
                 $token = $_GET['t'];
               // echo base_url().'index.php/api/user/confirm';die;
                // Get cURL resource
                $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => base_url().'index.php/api/user/confirm',
                    CURLOPT_USERAGENT => 'Confirm password',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'token' => $token,                        
                    )
                ));  
                // Send the request & save response to $resp
               $resp = curl_exec($curl);
               
                // Close request to clear up some resources
                curl_close($curl);
                $this->data['result'] = json_decode($resp);  
                $this->load->view('confirmation',$this->data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */