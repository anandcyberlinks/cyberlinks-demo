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

class Subscription extends REST_Controller
{
   function __construct()
   {
       parent::__construct();	
      $this->load->model('api/subscription_model');
      $this->load->helper('url');  
        
       //-- validate token --//
       $token = $this->get('token');
     // $action = $this->get('action');
      $action = $this->uri->segment(3);
      
      if($action != 'ipn' && $action != 'cancel'){
        $this->owner_id = $this->validateToken($token);
      }
       //--paging limit --//
          $this->param =  $this->paging($this->get('p'));
   }
   
   function list_get()
   {
         $data = $this->get();
        $video =  $this->subscription_model->getlist($data);
        $i=0;
        foreach($video as $row){
         $vid['title'] = $row->title;
         $thumbArray = array('small'=>'','medium'=>'','large'=>'');
         
         //-- video thumbnail --//
         if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
         }
         //---------------------//
         //-- subscription details --//
         $subscription['subscription_name'] = $row->subscription_name;
         $subscription['subscription_id'] = $row->subscription_id;
         $subscription['days'] = $row->days;
         $subscription['content_id'] = $row->content_id;
         $subscription['type'] = $row->type;
         $subscription['amount'] = $row->amount;
         
         $vid['subscription'][$i] = (object)$subscription;
         $vid['image'] = (object) $thumbArray;
         $i++;
        }
        //-- get available package --//
        if($data['content_id'] !=''){
            $package = $this->subscription_model->available_packages($data);            
        }
      
      $result['video'] = $vid;
      
      $i=1;
      $total=0;      
        
        //----- Package data ---//
        foreach($package as $row)
        {
         if($row->package_id != @$package_id){
         $j=0;
       $thumbArray = array('small'=>'','medium'=>'','large'=>'');
         //-- get pakcage durations --//
            $subscription = $this->subscription_model->susbcription_packages($row->package_id);
         //------------------------------------------//
         $package_id = $row->package_id;
         
        if(@$info){
         $result['package'][$i]['info'] = (object) $info;
         $i++;
         $info = array();
        }
         $info[$j]['title'] = $row->title;       
        $info[$j]['content_id'] = $row->content_id;        
        $result['package'][$i]['title'] = $row->package_name;
        $result['package'][$i]['subscription'] = $subscription;
        
        //-- video thumbnail --//
        if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
        }
         $info[$j]['image'] = (object) $thumbArray;         
         }else{
            $info[$j]['content_id'] = $row->content_id; 
          $info[$j]['title'] = $row->title;
          //-- video thumbnail --//
          if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
          }
         $info[$j]['image'] = (object) $thumbArray; 
         }
         $total++;
         $j++;
         if(count($package) == $total){
             $result['package'][$i]['info'] = (object) $info;
         }
        }
        
        //--------------------------//
        //echo '<pre>';print_r($result);
        if($result)
        {         
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
   }
   
   function mysusbscriptions_get()
   {
      $data = $this->get();
      $this->subscription_model->get_my_susbscriptions($data);
   }
   
   function validate_get()
   {
      $data = $this->get();
      $id = $this->subscription_model->validate_subscription($data);
      if($id > 0){
         $this->response(array('code'=>1), 200); // 200 being the HTTP response code
      }else{
         $this->response(array('code'=>0,'error' => 'Please subscribe to play the video'), 404);
      }
   }
     
   
   function checkout_post()
   {
     $post = $this->post();     
      //return print_r($data);die;
       /* $user_id = $this->post('user_id');
        $user_name = $this->post('user_name');
        $user_email = $this->post('user_email');
        $total_amt = $this->post('total_amount');
        */
        //$this->post('subscription_id');
        //$this->post('amount');
       
        $cart = json_decode($this->post('cart'),true);
      //$this->response($cart);die;
        if(count($cart)>0)
        {
         
         //-- insert in order table --//
            //$orderdata['user_id'] = $user_id;
            //$orderdata['total_amount'] = $total_amt;            
      
            $order_id = $this->subscription_model->saveOrder($post);
             
         //--------------------------//
         if($order_id > 0){
                     
            foreach($cart as $row){
                //-- insert cart item in order detail table --//
                $row['order_id'] = $order_id;
                 $id = $this->subscription_model->saveOrderDetails($row);               
                  if($id <= 0){
                     //-- deleta order --//
                        $this->subscription_model->delete_order($order_id);
                     //--------------//
                     $this->response(array('output'=>0,'error'=>'Checkout failed.'), 404);
                   }
            }            
            $this->response(array('output'=>1), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('output'=>0,'error'=>'Checkout failed.'), 404);
        }
        }
   }
   
   function cancel_post()
   {      
      $invoice_no = $this->post('invoiceno');      
      $result = $this->subscription_model->updateOrderStatus($invoice_no, 'cancelled');
      if($result)
         $this->response(array('output'=>1), 200); // 200 being the HTTP response code
      else
         $this->response(array('output'=>0,'error'=>'Checkout failed.'), 404);
   }
   
   function ipn_post()
   {
      $post = $this->post();      
      $result =  $this->subscription_model->saveOrder($post);
      $this->subscription_model->saveOrderDetails($post); //-- update order detail start and end time
      if($result)
         $this->response(array('output'=>1,'result'=>$result), 200); // 200 being the HTTP response code
      else
         $this->response(array('output'=>0,'error'=>'Checkout failed.'), 404);
   }
}