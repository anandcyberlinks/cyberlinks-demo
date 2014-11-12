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
        
        //-- get available package --//
        if($data['content_id'] !=''){
            $package = $this->subscription_model->available_packages($data);            
        }
      $i=1;
      $total=0;
      
        $result['video'] = $video;
        foreach($package as $row)
        {
         if($row->package_id != @$package_id){
         $j=0;
        // echo '<pre>';print_r($info);
       //echo '<pre>';  print_r($info);
         //-- get pakcage durations --//
            $subscription = $this->subscription_model->susbcription_packages($row->package_id);
         //------------------------------------------//
         $package_id = $row->package_id;
        if(@$info){
         $result['package'][$i]['info'] = (object) $info;
         $i++;
         $info = array();
        }
                
        $info[$j]['content_id'] = $row->content_id;
        $info[$j]['path'] = base_url().$row->relative_path;
        $result['package'][$i]['title'] = $row->package_name;
        $result['package'][$i]['subscription'] = $subscription;
        
        // $result['package'][$i]['content_id'] = $row->content_id;
         //$result['package'][$i]['path'] = base_url().$row->relative_path;      
         }else{
           // $result['package'][$i]['content_id'] = $row->content_id;
          //  $result['package'][$i]['path'] = base_url().$row->relative_path;
          $info[$j]['content_id'] = $row->content_id;
          $info[$j]['path'] = base_url().$row->relative_path;
         }
         $total++;
         $j++; //echo '<pre>';print_r($info);
         if(count($package) == $total){
             $result['package'][$i]['info'] = (object) $info;
         }
        }
        
        //--------------------------//
        
        if($result)
        {         
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
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