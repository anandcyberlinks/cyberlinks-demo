<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Crons extends REST_Controller
{   
    
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Video_model');
   }
   
public function transcode_get()
    {       
       $result = $this->Video_model->video_flavors();
       
        if($result)
        {
            array_walk ( $result, function (&$key) { 
                    //-- get total likes --//
                    $key->upload_path = VIDEO_UPLOAD_PATH;
            });
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function transcodeupdate_post()
    {
        $id = $this->post('id');
        $status = $this->post('status');
        if($status !=''){
            $data = array('status'=>$status);
            $result = $this->Video_model->update_video_flavors($id,$data);
            
            if($result)
            {           
                $this->response($result, 200); // 200 being the HTTP response code
            }else{
                $this->response(array('error' => 'No record found'), 404);
            }
        }
    }
    
    public function tanscoded_url_post(){
        $id = $this->post('id');
        $content_id = $this->post('content_id');
        $path = $this->post('path');
        $length = $this->post('duration');
        $data = array('content_id'=>$content_id,'flavor_id'=>$id,'path'=>$path,'status'=>1);
        $result = $this->Video_model->save_flavored_video($data);
        
        //--- update video duration --//
        if($length){
            $duration =  $this->seconds_from_time($length);
            $upddata = array('duration'=>$duration);
            $this->Video_model->update_video($content_id,$upddata);
        }
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function tanscoded_wowza_url_post(){
        $id = $this->post('id');
        $content_id = $this->post('content_id');
        $path = $this->post('path');
        $device = $this->post('device');
        
        $data = array('content_id'=>$content_id,'flavor_id'=>$id,'path'=>$path,'device_type'=>$device,'status'=>1);
        $result = $this->Video_model->save_wowza_video($data);
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
}