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
         //-- validate token --//
      /*  $token = $this->get('token');
        // $action = $this->get('action');
        $action = $this->uri->segment(3);        
        $this->owner_id = $this->validateToken($token);
        
        */
   }   
   
   public function live_get()
    {
        $id = $this->get('id');
        $channels = $this->livestream_model->getStream($id);        
        $total = count($channels)-1;
        $playlist =  ($this->livestream_model->getPlayList($id));
      // echo '<pre>';print_r($playlist);
      
      array_walk($channels,function(&$key){
          $key->epg =array();            
        
      });
      
        //-- get epg details ---//        
         array_walk($playlist, function (&$key) {
            //-- get epg  --//
            $key->thumbnail_url ='';
            $key->youtube ='';
            $key->epg = $this->livestream_model->getEPG($key->id);
            
         });
        
        if($playlist)
        {
            $i=1;
            foreach($playlist as $row){
               $channels[$total+$i] = $row;
               $i++;
            }            
        }        
        if($channels)
        {
            $this->response($channels, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }        
    }
    
    public function category_get()
    {
     $result =  $this->livestream_model->getCategory();
      if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }      
    }
}

?>