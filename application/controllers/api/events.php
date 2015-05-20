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

class Events extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Events_model');
       $this->load->model('api/User_model');
        //-- validate token --//
        $this->admin_token = $this->get('token');
        $owner_id =  $this->User_model->checkAdminToken($this->admin_token);	                
        if($owner_id <= 0)
        {                
            $response_arr = array('code'=>0,'error' => "Invalid Token");               
            $this->response($response_arr, 404);
        }        
    }
       
    function categories_get()
    {
        $result = $this->Events_model->getCategories();
        if(isset($result) && count($result) > 0)
        {
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
    function list_get()
    {
        $cid = $this->get('id');
        $result = $this->Events_model->categoryEvents($cid);
        if(isset($result) && count($result) > 0)
        {
            $newresult = array();
            foreach($result as $key => $val){
                    $newresult[$val->category_name][] = $val;
            }
            $this->response(array('code'=>1,'result'=>$newresult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
    function add_events_post(){
        $data = $this->post();
    }
    
}