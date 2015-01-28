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
       $this->load->model('api/Ads_model');
       $this->load->helper('common');
   }
   
    public function transcode_get()
    {
        $type = $this->get('type');       
     
        $path = VIDEO_UPLOAD_PATH;
        $result = $this->Video_model->video_flavors();
       
        if($result)
        {
            array_walk ( $result, function (&$key) { 
                    //-- get total likes --//
                    $key->upload_path = @$path;
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
    
    public function transcode_ads_get()
    {
       $result = $this->Ads_model->getAdsFlavour();      
        if($result)
        {
            array_walk ( $result, function (&$key) { 
                    //-- get total likes --//
                    $key->upload_path = serverAdsRelPath;
                    $key->video_file_name = base_url().$key->video_file_name;
            });
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function transcode_ads_update_post()
    {
        $id = $this->post('id');
        $status = $this->post('status');
        if($status !=''){
            $data = array('transcode_status'=>$status);
            $result = $this->Ads_model->update_ads($data,$id);
            
            if($result)
            {
                $this->response($result, 200); // 200 being the HTTP response code
            }else{
                $this->response(array('error' => 'No record found'), 404);
            }
        }
    }
    
    public function tanscoded_ads_url_post(){
        $content_id = $this->post('id');
        $path = $this->post('path');
        $length = $this->post('duration');
        $type = $this->post('type');
      //  print_r($this->get());die;
        $data = array('ads_id'=>$content_id,'type'=>$type,'path'=>$path,'status'=>1);
        $result = $this->Ads_model->save_flavored_ads($data);
        
        //--- update video duration --//
        if($length){
            $duration =  $this->seconds_from_time($length);
            //$duration = $length;
            $upddata = array('duration'=>$duration);
            $this->Ads_model->update_ads($upddata,$content_id);
        }
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
        
    public function create_vast_post()
    {
        $id = $this->post('id');
        $result =  $this->Ads_model->getTranscodedAds($id);     
   
        if($result){
        foreach($result as $row){
        $title = $row->ad_title;
        $duration = $row->duration;
       }
        //-- generate Vast file ---//
            $vast_file_path = $this->createVideoXml($title,$result,$duration);
            //--- insert xml in files table ----//
            $data = array('name'=>'',
                'minetype'=>'application/xml',
                'type' => 'xml',
                'relative_path' => $vast_file_path,
                'absolute_path' => '',
                'status' => '1'
                );
                        $file_id = $this->Ads_model->save_file($data);
                        //--------------------//
                        //-- update content ads with vast file id --//
                        $data = array('vast_file_id' => $file_id);
                        $this->Ads_model->update_ads($data, $id);
                        //-----------------------------------------//
            $this->response('Vast file created succesfully', 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
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