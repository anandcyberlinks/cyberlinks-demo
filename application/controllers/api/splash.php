<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Splash extends REST_Controller
{   
    
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Splash_model');
   }
     
   public function screen_get()
    {
       $id = $this->get('token');       
       $result = $this->Splash_model->getdetails($id);
       $userid='';
       if(count($result)>0){
       foreach($result as $key){
            
               if($key->dimension_name !=''){
                    $thumbArray[$key->dimension_name] = base_url().$key->thumbnail_path;              
                }else{
                    $thumbArray[$key->dimension_name]='';                    
                }
           //$key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
               if($key->user_id != $userid){
                   $userid = $key->user_id;
                   $finalResult['user_id'] = $userid;
                   $finalResult['token'] = $key->token;
                   $finalResult['logo'] = base_url().PROFILEPIC_PATH.$key->logo;
               }else{
                   $finalResult['image'] = $thumbArray;
               }
            }       
            $this->response(array('code'=>1,'result'=>$finalResult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
}