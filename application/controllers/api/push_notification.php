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

class Push_notification extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
        //-- validate token --//
      // $token = $this->get('token');
      // $this->owner_id = $this->validateToken($token);
    }
    
    function update_notification_get(){
        $this->db->set('push_id',$_GET['push_id']);
        $this->db->set('platform',$_GET['platform']);
        $this->db->set('open_datetime','NOW()',FALSE);
        $this->db->insert('open_push_notification',$data);
        $result = $this->db->insert_id();
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
}