<?php defined('BASEPATH') OR exit('No direct script access allowed');
//ini_set('display_errors',1);
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

class Stream extends REST_Controller
{
   function __construct()
   {
       parent::__construct();	
       $this->load->model('api/livestream_model');
       $this->load->helper('url');       
   }   
   
   public function live_get()
    {
        $device = $this->get('device');
        $channels = $this->livestream_model->getStream($device);
        //$channels[] =  reset($this->livestream_model->getPlayList());
        if($channels)
        {
            $this->response($channels, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }        
    }    
}

?>