<?php defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Origin: *");
/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Auth extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Auth_model');       
        //-- validate token --//
      // $token = $this->get('token');
      // $this->owner_id = $this->validateToken($token);
    }
       
    function generatecontenttoken_get()
    {
	$user = $this->get('user');
	//-- generate token --//
            $token = uniqid(); 
        //-- add api token ---//    
            $data = array('token'=>md5($token),'USER'=>'','IP'=>$_SERVER['REMOTE_ADDR']);
            $this->Auth_model->addcontent_token($data);            
	    $this->response(array('token' => $token), 200);
        //-------------------//
    }
    
}