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

class Video extends REST_Controller
{       
   function __construct()
   {
       parent::__construct();	
       $this->load->model('api/Video_model');
       $this->load->helper('url');
   }
   
    public function category_get()
    {        
       $result = $this->Video_model->categorylist();
       /*array_walk ( $result, function (&$key) {           
           $key->thumbnail_path = base_url().$key->thumbnail_path;           
       } ); */
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
   public function list_get()
    {
       $id=$this->get('id');
       $device = $this->get('device');
       
       $result = $this->Video_model->videolist($id,$device);
      // echo '<pre>';print_r($result);
       array_walk ( $result, function (&$key) { 
           //$key->video_path = base_url().$key->video_path;
		   if($key->thumbnail_path!=''){
			$key->thumbnail_path = base_url().$key->thumbnail_path;
		   }
           //$key->url = base_url().'index.php/details?id='.$key->content_id;
       } );
       $contentid='';
       $i=0;
       $flag=0;
       foreach($result as $row){
           if($row->content_id != $contentid){
               
               if($flag == 1){
                   $i++;
               }
               $contentid = $row->content_id;
               $output[$i]['content_id'] = $row->content_id;
               $output[$i]['title'] = $row->title;
               $output[$i]['description'] = $row->description;
               $output[$i]['type'] = $row->type;
               $output[$i]['thumbnail'] = $row->thumbnail;
               $output[$i]['thumbnail_path'] = $row->thumbnail_path;
               $output[$i]['thumb_mimetype'] = $row->thumb_mimetype;
               $output[$i]['category_name'] = $row->category_name;
               $output[$i]['category_desc'] = $row->category_desc;
              
              if(strtolower($row->flavor_name) == 'low'){
                   $output[$i]['video_url']['2g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'medium'){
                   $output[$i]['video_url']['3g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'high'){
                   $output[$i]['video_url']['normal'] = $row->video_path;
               }
               $flag=1;
           }else{
               if(strtolower($row->flavor_name) == 'low'){
                   $output[$i]['video_url']['2g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'medium'){
                   $output[$i]['video_url']['3g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'high'){
                   $output[$i]['video_url']['normal'] = $row->video_path;
               }
           }           
       }
         
        if($output)
        {           
            $this->response($output, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function search_post()
    {
       $data['title'] = $this->post('title');
       $data['category'] = $this->post('category');
       $data['startdate'] = ($this->post('startdate')!='' ? date('Y-m-d',strtotime($this->post('startdate'))):'');
       $data['enddate'] = ($this->post('enddate')!='' ? date('Y-m-d',strtotime($this->post('enddate'))):'');

       $result = $this->Video_model->searchvideo($data);

        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function detail_get()
    {
       $id = $this->get('id');       
       $result = $this->Video_model->videodetails($id);
       //$result->video_path = base_url().$result->video_path;
      if($result->thumbnail_path!=''){
            $result->thumbnail_path = base_url().$result->thumbnail_path;
        }
       //$result->url = base_url().'details?id='.$result->content_id;
       
        if($result)
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function latest_get()
    {       
       $device = $this->get('device');
       $result = $this->Video_model->videolatest($device);
       
    //-- make full path for video and thumbnail --//
       array_walk ( $result, function (&$key) { 
           //$key->video_path = base_url().$key->video_path;
          if($key->thumbnail_path!=''){
			$key->thumbnail_path = base_url().$key->thumbnail_path;
		   }
           //$key->url = base_url().'index.php/details?id='.$key->content_id;
       } );
          
        $contentid='';
       $i=0;
       $flag=0;
       foreach($result as $row){
           if($row->content_id != $contentid){
               
               if($flag == 1){
                   $i++;
               }
               $contentid = $row->content_id;
               $output[$i]['content_id'] = $row->content_id;
               $output[$i]['title'] = $row->title;
               $output[$i]['description'] = $row->description;
               $output[$i]['type'] = $row->type;
               $output[$i]['thumbnail'] = $row->thumbnail;
               $output[$i]['thumbnail_path'] = $row->thumbnail_path;
               $output[$i]['thumb_mimetype'] = $row->thumb_mimetype;
               $output[$i]['category_name'] = $row->category_name;
               $output[$i]['category_desc'] = $row->category_desc;
              
              if(strtolower($row->flavor_name) == 'low'){
                   $output[$i]['video_url']['2g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'medium'){
                   $output[$i]['video_url']['3g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'high'){
                   $output[$i]['video_url']['normal'] = $row->video_path;
               }
               $flag=1;
           }else{
               if(strtolower($row->flavor_name) == 'low'){
                   $output[$i]['video_url']['2g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'medium'){
                   $output[$i]['video_url']['3g'] = $row->video_path;
               }
               if(strtolower($row->flavor_name) == 'high'){
                   $output[$i]['video_url']['normal'] = $row->video_path;
               }
           }           
       }
        if($output)
        {           
            $this->response($output, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }
    }
    
    public function livetv_get()
    {
        $device = $this->get('device');
        $stream = array('ios'=>'http://54.255.176.172:1935/live/smil:mystream.smil/playlist.m3u8',
            'android'=>'http://54.255.176.172:1935/live/smil:mystream.smil/playlist.m3u8',
            'windows'=>'http://54.255.176.172:1935/live/smil:mystream.smil/Manifest');
        
        if(array_key_exists($device,$stream))
        {
            $result = array('url'=>array('Wi-fi'=>$stream[$device],'3G'=>$stream[$device],'2G'=>$stream[$device]));
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }        
    }
    
    public function transcode_get()
    {       
       $result = $this->Video_model->video_flavors();

        if($result)
        {           
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
    
    public function tanscoded_amazon_url_post(){
        $id = $this->post('id');
        $content_id = $this->post('content_id');
        $path = $this->post('path');
        $data = array('content_id'=>$content_id,'flavor_id'=>$id,'path'=>$path,'status'=>1);
        $result = $this->Video_model->save_flavored_video($data);
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
    
    public function send_post()
    {
        var_dump($this->request->body);
    }


	public function send_put()
	{
		var_dump($this->put('foo'));
	}
}