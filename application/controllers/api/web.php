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

class Web extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/user_model');       
        //-- validate token --//
      // $token = $this->get('token');
      // $this->owner_id = $this->validateToken($token);
    }
       
    function cp_get()
    {
	$token = $this->get('token');
	$domain = $this->get('domain');
	$this->db->select('u.id,l.web as url,l.channel_id,z.zone_id,u.domain');
	$this->db->from('users u');
	$this->db->join('livestream l','u.id=l.user_id','left');
	$this->db->join('user_zone z','u.id=z.user_id','left');
	$this->db->where('token',$token);
	$this->db->where('domain',$domain);
	$this->db->order_by('z.id', 'random');
	$query = $this->db->get();
	//echo $this->db->last_query();
	$result = $query->row();
	if($result)
	$this->response(array('result' => $result), 200);
	else
	$this->response(array('result' => 0), 404); 		
    //-------------------//
    }
    
}