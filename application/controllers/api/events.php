<?php defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Origin: *");
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

class Events extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Events_model');
       $this->load->model('api/User_model');
        //-- validate token --//
        $token = $this->get('token');
        $owner_id =  $this->User_model->checkAdminToken($token);	                
        if($owner_id <= 0 || $token=='')
        {                
            $response_arr = array('code'=>0,'error' => "Invalid Token");               
            $this->response($response_arr, 404);
        }
    }
    
    function base64_to_jpeg($base64_string, $output_file,$extension) {
        
	$img = $base64_string;
	//$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);        
        $file = uniqid() . '.'.$extension;
	$data = base64_decode($img);
        $srctmp = $output_file.'.tmp'.'.'.$extension;
	 $output_file .= $file;
	$success = file_put_contents($srctmp, $data);
        
        //-- create thumbnail --//
        
        if($success){
            $this->create_thumbnail($file,$srctmp,$output_file,'100','100');
            return $file;
        }else{
            return 0;	    
        }
    }
       
    function categories_get()
    {
        $result = $this->Events_model->getCategories();
        if(isset($result) && count($result) > 0)
        {
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
    function list_get()
    {
        $cid = $this->get('id');
        $userid = $this->get('userid');
        $result = $this->Events_model->categoryEvents($cid,$userid);
        if(isset($result) && count($result) > 0)
        {
            $newresult = array();
            foreach($result as $key => $val){
				
                //error_reporting(E_ALL);
                    $val->url_mobile = preg_replace("/^rtsp:/i", "http:", $val->url,1).'/playlist.m3u8';
                    $val->url_web = preg_replace("/^rtsp:/i", "rtmp:", $val->url,1);
                    unset($val->event_id);
                    unset($val->url);
					//if($result->id !=''){
                    $newresult[$val->category_name][] = $val;
					//}else{
					//	$newresult[$val->category_name][] ='';
					//}
			}
            $this->response(array('code'=>1,'result'=>$newresult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'result'=>'No record found'), 404);
        }
    }
    
    function add_post(){
        
        $ext = $this->post('ext');
        $my_base64_string = $this->post('pic');
        //---- Upload logo image for user --//
        if($ext !=''){
	    if($my_base64_string !=''){
		$pic = $this->base64_to_jpeg( $my_base64_string, EVENTPIC_PATH,$ext );
		if($pic == 0){
		    $this->response(array('code'=>0,'error' => "Error uploading image."), 404);
		}
	    }
        }else{
                $incoming_tmp = @$_FILES['pic']['tmp_name'];
                $incoming_original = @$_FILES["pic"]["name"];
                if ($incoming_original != '') {                    
                    $path = EVENTPIC_PATH;
                    $allowed = array('jpg', 'jpeg', 'png', 'gif');
                    $output = $this->uploadfile($incoming_tmp, $incoming_original, $path, $allowed);  //-- upload file --//

                    if ($output['error']) {
                        $this->response(array('code'=>0,'error' => $output['error']), 404);
                    } else {
                        $pic = $output['path'];
                    }
                } else {
                    //$_POST['file'] = $_POST['logo'];
                }
        }
        $characters = 'ab0c1d2ef3gh4i5jkl7mn8opqrs9tuvw6xyz';
        $random_key = '';
        for ($i = 0; $i < 4; $i++) {
             $random_key .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        $data = array(
            'event_id' =>$random_key,
            'name' => $this->post('name'), 
            'description' => $this->post('description'), 
            'category' => $this->post('category'),
            'url' => EVENT_URL.$this->post('u_token').$random_key,
            'start_date' => $this->post('start_date'), 
            'end_date' => $this->post('end_date'),
            'event_type' => $this->post('event_type'),                     
            'uid' => $this->post('userid'),
            'status' => '1'
            );              
	    if($pic !='' && $pic != 0){
               $data['thumbnail']=base_url().EVENTPIC_PATH.$pic;
           }
           //echo '<pre>'; print_r($data); exit;
           $result = $this->Events_model->saveEvents($data);
           $result['url_mobile'] = EVENT_URL_MOBILE.$this->post('u_token').$random_key.'/playlist.m3u8';
           $result['url_web'] = EVENT_URL_WEB.$this->post('u_token').$random_key;
           
           if(isset($result) && $result!=''){
               $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
           }else{
               $this->response(array('code'=>0,'result'=>'Error'), 404);
           }
    }
	
	function publish_post()
	{		
		$id = $this->post('id');
		$status = $this->post('status');
		$this->db->set('status',$status);
		$this->db->set('modified', 'NOW()',false);
		$this->db->where('id',$id);
		$this->db->update('events');
		//echo $this->db->last_query();
        $this->response(array('code'=>1), 200); // 200 being the HTTP response code        
	}
}