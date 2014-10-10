<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Cms extends REST_Controller
{   
    
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Cms_model');
   }
   
   public function links_get()
   {        
       $result = $this->Cms_model->getlist();
       if($result)
        {           
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
   }
   
   public function details_get()
    {
       $id = $this->get('id');       
       $result = $this->Cms_model->getdetails($id);
       //$result->video_path = base_url().$result->video_path;       
        if($result)
        {           
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    
}