<?php defined('BASEPATH') OR exit('No direct script access allowed');

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

class Ads extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Ads_model');
        //-- validate token --//
      // $token = $this->get('token');
      // $this->owner_id = $this->validateToken($token);
    }
       
    function list_get()
    {
       $result = $this->Ads_model->getAds();
       array_walk($result,function(&$key){
        $key->url = base_url().$key->url;
        });
       
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
    function revive_ads_get()
    {
        $result = $this->Ads_model->getReviveAds();
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
}