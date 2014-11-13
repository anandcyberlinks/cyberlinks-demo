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
      $this->load->model('api/Video_model');
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
      
      $i=0;
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
         $result['package']['v'.$i]['info'] = (object) $info;
         $i++;
         $info = array();
        }
         $info['i'.$j]['title'] = $row->title;       
        $info['i'.$j]['content_id'] = $row->content_id;        
        $result['package']['v'.$i]['title'] = $row->package_name;
        $result['package']['v'.$i]['subscription'] = $subscription;
        
        //-- video thumbnail --//
        if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
        }
         $info['i'.$j]['image'] = (object) $thumbArray;         
         }else{
            $info['i'.$j]['content_id'] = $row->content_id; 
          $info['i'.$j]['title'] = $row->title;
          //-- video thumbnail --//
          if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
          }
         $info['i'.$j]['image'] = (object) $thumbArray; 
         }
         $total++;
         $j++;
         if(count($package) == $total){
             $result['package']['v'.$i]['info'] = (object) $info;
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
      $video = $this->subscription_model->get_video_susbscriptions($data);
      array_walk ( $video, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);                
                //-- get rating --//
                $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                //-- get pakcage durations --//
                  $subscription['subscription_name'] = $key->subscription_name;
                  $subscription['subscription_id'] = $key->subscription_id;
                  $subscription['days'] = $key->days;
                  $subscription['content_id'] = $key->content_id;
                  $subscription['type'] = $key->type;
                  $subscription['amount'] = $key->amount;
               //------------------------------------------//
               $key->subscription = (object)$subscription;
               
                if($key->thumbnail_path !=''){   
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
                }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
      
      //echo '<pre>';print_r($video);
      
      //-- My Package subscription --//         
         $result_pkg = $this->subscription_model->get_package_susbscriptions($data);
         
         array_walk ( $result_pkg, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);                
                //-- get rating --//
                $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                if($key->thumbnail_path !=''){   
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
                }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
         $i=0;
         $result = array();
        //  echo '<pre>';print_r($result_pkg);
         
         $result = $this->package_array($result_pkg);
         $result['video'] = $video;
         //echo '<pre>';print_r($result);die;
      //-----------------------------//
      
       if($result)
        {         
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
   }
  
  function package_array($package)
  {
   $i=0;
   $total=0; 
    //----- Package data ---//
        foreach($package as $row)
        {
         if($row->package_id != @$package_id){
         $j=0;
         $i++;
         $thumbArray = array('small'=>'','medium'=>'','large'=>'');
       
         $package_id = $row->package_id;
         
        if(@$info){
         $result['package']['v'.$i]['info'] = (object) $info;
         $i++;
         $info = array();
        }
         $info['i'.$j]['title'] = $row->title;       
         $info['i'.$j]['content_id'] = $row->content_id;
         $info['i'.$j]['description'] = $row->description;       
         $info['i'.$j]['type'] = $row->type;
         $info['i'.$j]['content_type'] = $row->content_type;       
         $info['i'.$j]['video_path'] = $row->video_path;
         $info['i'.$j]['category_id'] = $row->category_id;       
         $info['i'.$j]['category_name'] = $row->category_name;
         $info['i'.$j]['duration'] = $row->duration;       
         $info['i'.$j]['total_view'] = $row->total_view;
         $info['i'.$j]['thumbnail_path'] = $row->thumbnail_path;       
         $info['i'.$j]['url'] = $row->url;
         $info['i'.$j]['url'] = $row->url;       
         $info['i'.$j]['rating'] = $row->rating;        
        
        //-- package detail --//
        $result['package']['v'.$i]['title'] = $row->package_name;
        $result['package']['v'.$i]['package_id'] = $row->package_id;
        
        //-- subscription array --//
        $result['package']['v'.$i]['subscription']['subscription_name'] = $row->subscription_name;
        $result['package']['v'.$i]['subscription']['days'] = $row->days;
        $result['package']['v'.$i]['subscription']['subscription_id'] = $row->subscription_id;
        $result['package']['v'.$i]['subscription']['type'] = $row->type;
        $result['package']['v'.$i]['subscription']['amount'] = $row->amount;
        
        //-- video thumbnail --//
        if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
        }
         $info['i'.$j]['image'] = (object) $thumbArray;         
         }else{
            $info['i'.$j]['content_id'] = $row->content_id; 
            $info['i'.$j]['title'] = $row->title;
            $info['i'.$j]['description'] = $row->description;       
            $info['i'.$j]['type'] = $row->type;
            $info['i'.$j]['content_type'] = $row->content_type;       
            $info['i'.$j]['video_path'] = $row->video_path;
            $info['i'.$j]['category_id'] = $row->category_id;       
            $info['i'.$j]['category_name'] = $row->category_name;
            $info['i'.$j]['duration'] = $row->duration;       
            $info['i'.$j]['total_view'] = $row->total_view;
            $info['i'.$j]['thumbnail_path'] = $row->thumbnail_path;       
            $info['i'.$j]['url'] = $row->url;
            $info['i'.$j]['url'] = $row->url;       
            $info['i'.$j]['rating'] = $row->rating;
         
          //-- video thumbnail --//
          if($row->thumbnail_path !=''){
         $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail_path;
         $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail_path;
         $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail_path;
          }
         $info['i'.$j]['image'] = (object) $thumbArray; 
         }
         $total++;
         $j++;
         if(count($package) == $total){
             $result['package']['v'.$i]['info'] = (object) $info;
         }
        }
   return @$result;
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
      //$this->response($cart);
      
      $cart = array($cart);
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
        }else{
            $this->response(array('output'=>0,'error'=>'Checkout failed.'), 404);
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