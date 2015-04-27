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

class Ads extends REST_Controller
{   
    
    function __construct()
    {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('api/Ads_model');       
        //-- validate token --//
      // $token = $this->get('token');
      // $this->owner_id = $this->validateToken($token);
    }
       
    function list_get()
    {
       $result = $this->Ads_model->getAds();
       array_walk($result,function(&$key){
        $key->url = base_url().$key->url;
        });
       
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
    function revive_ads_get()
    {
        $result = $this->Ads_model->getReviveAds($_GET['type'],$_GET['id']);
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
    function campaign_get()
    {
        $id = $this->get('id');
        $user_id = $this->get('user_id');
        $type = $this->get('type');
        $zone = $this->get('zone');
    
        //-- get content cuepoints ---//
        $limit = $this->Ads_model->getCuePoints($id,$type,1);
        $cuePoints = $this->Ads_model->getCuePoints($id,$type);
        //----------------------------//        
        $user_data = $this->Ads_model->getUserKeywords($user_id);        
        
        if(@$cuePoints['0']!=0){
            array_unshift($cuePoints, 0);
            $limit += 1;
        }                
        if(count($cuePoints) <= 0){
            $limit = 1;
        }
                 
        //--- Access Revive web service ---//
	/*$gender = $user_data['gender'];
	$dob = $user_data['dob'];
	$from = new DateTime($dob);
	$to   = new DateTime('today');
	$age = $from->diff($to)->y;
		*/
    //-- get location ---//
    //---- IP details ---//
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
     $location = file_get_contents('http://ip-api.com/json/'.$ip);
            $location = json_decode($location);
            $country     = ($location->countryCode =='IN' ? 'IN':'OTR');
    //--------------------------//
	$keywords = $user_data['keywords'];
                
        $this->load->helper('url');	
        $url = "http://54.179.170.143/vast/getvast.php?zone=$zone&country=$country&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$limit";
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));  
        // Send the request & save response to $resp
        $result = curl_exec($curl);
               
        // Close request to clear up some resources
        curl_close($curl);
        //$this->response(json_decode($result), 200);
        $adsAlloc = json_decode($result);
       //print_r($adsAlloc);
       //----Ad allocation with cuepoint array ---//
       $i=0;                
	    foreach($adsAlloc->url as $key=>$val)
	    {
                $adsFinal[$i]['vast_file'] = $val;
                if(count($cuePoints) > 0){
                $adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
                }else{
                $adsFinal[$i]['cue_points'] 	= 0;
                }
                $i++;
            }
    //----------------------------------------------//
    if($adsFinal){
        $this->response($adsFinal);
    }else{
        $this->response('No record found', 404);
    }
}

/*
 Created by Hitender, dated 20/04/2015
 Purpose: Used to serve ads to publishers
*/
    function serve_ad_get()
    {
        $token = $this->get('token');
        $limit = $this->get('limit');
        $mode = $this->get('mode');
        //--- validate token ---//
        $this->db->select('z.zone_id');
        $this->db->from('users u');
        $this->db->join('user_zone z','u.id=z.user_id','left');
        $this->db->where('token',$token);
        /*f($mode !='demo'){        
            $this->db->where('domain',$domain);
        }else{
             $limit =2;
        }*/
        $query = $this->db->get();
      // echo $this->db->last_query();
        $result = $query->row();
       
        if(!$result){
        $this->response('Invalid Token', 404);      
        }
      //---------------------//
       $zone = $result->zone_id;
       
       /*if($limit ==''){
        //-- get content cuepoints ---//
        $limit = $this->Ads_model->getCuePoints($id,$type,1);
        $cuePoints = $this->Ads_model->getCuePoints($id,$type);
        //----------------------------//        
        $user_data = $this->Ads_model->getUserKeywords($user_id);        
        
        if(@$cuePoints['0']!=0){
            array_unshift($cuePoints, 0);
            $limit += 1;
        }                
        if(count($cuePoints) <= 0){
            $limit = 1;
        }
       }*/
        //---- IP details ---//
       /* if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $location = file_get_contents('http://ip-api.com/json/'.$ip);
        $location = json_decode($location);
        $country     = ($location->countryCode =='IN' ? 'IN':'OTR');
        */
        //--------------------------//
       // $keywords = $user_data['keywords'];
                
        $this->load->helper('url');	
        $url = "http://multitvsolution.com/vast/getvast.php?zone=$zone&country=$country&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$limit";
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));  
        // Send the request & save response to $resp
        $result = curl_exec($curl);
          // $this->response($result);     
        // Close request to clear up some resources
        curl_close($curl);
       
        //$this->response(json_decode($result), 200);
        $adsAlloc = json_decode($result);
       //print_r($adsAlloc);
       //----Ad allocation with cuepoint array ---//
       $i=0;                
	    foreach($adsAlloc->url as $key=>$val)
	    {
                $adsFinal[$i]['vast_file'] = $val;
                if(count($cuePoints) > 0){
                $adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
                }else{
                $adsFinal[$i]['cue_points'] 	= 0;
                }
                $i++;
        }
    //----------------------------------------------//
    if($adsFinal){
        $this->response($adsFinal);
    }else{
        $this->response('No record found', 404);
    }
    }
    
    
    function vast_get()
    {      
        $token = $this->get('token');
        $domain = $_SERVER[HTTP_HOST];
        //--- validate token ---//
        $this->db->select('z.zone_id');
        $this->db->from('users u');
        $this->db->join('user_zone z','u.id=z.user_id','left');
        $this->db->where('token',$token);
      // $this->db->where('domain',$domain);
        /*f($mode !='demo'){        
            $this->db->where('domain',$domain);
        }else{
             $limit =2;
        }*/
        $query = $this->db->get();
      // echo $this->db->last_query();
        $result = $query->row();
       
        if(!$result){
        $this->response('Invalid Token', 404);      
        }
      //---------------------//
       $zone = $result->zone_id;
       
        $this->load->helper('url');	
        $url = "http://multitvsolution.com/vast/getvast.php?zone=$zone&country=$country&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$limit";
        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));  
        // Send the request & save response to $resp
         $result = curl_exec($curl);        
         $out = json_decode($result);
         if($out){
            echo $out->url[0];
         }else{        
            $this->response('No record found', 404); 
         }
        // Close request to clear up some resources
        curl_close($curl);
        die;
    //----------------------------------------------//
    }    
    
    function channels_get(){
        $result = $this->Ads_model->getChannels();
        if(isset($result))
        {
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response('No record found', 404);
        }
    }
    
}