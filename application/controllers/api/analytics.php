<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Analytics extends REST_Controller
{       
    function __construct()
   {
       parent::__construct();
       $this->load->helper('url');
       $this->load->model('ads/Ads_model');
       $this->load->model('/analytics/Analytics_model');
       $this->load->library('User_Agent');
       $this->useragent = User_Agent::getinfo();  //--regex class to get user agent --//
   }


    function play_post()
	{ 
        $post = $this->post();
		$post['browser'] = $this->useragent['browser'];
		$post['browser_version'] = $this->useragent['version'];
        $post['platform']  = $this->useragent['platform'];
        
        //---- IP details ---//
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $location = file_get_contents('http://freegeoip.net/json/'.$ip);
        $location = json_decode($location);
        $post['city']     = $location->city;
        $post['state']     = $location->region_name;
        $post['country']     = $location->country_name;
        $post['country_code']     = $location->country_code;
        $post['postal_code']     = $location->zip_code;
        $post['latitude']     = $location->latitude;
        $post['longitude']     = $location->longitude;
        $post['ip'] = $ip;        
        //--------------------------//        
               // $post['platform'] = $this->result['platform'];
	      // $post['platform'] = $this->post['device_type'];         
        echo $this->Analytics_model->save($post);
	}
    
    function pause_post()
	{
        $post = $this->post();	
        $where = array('id'=>$post['id']);
        echo $this->Analytics_model->save($post,$where);
	}
    
    
    function playads_post()
	{
		$post = $this->post();		
		if($post){                        
            $post['browser'] = $this->useragent['browser'];
            $post['browser_version'] = $this->useragent['version'];
            $post['platform']  = $this->useragent['platform'];
            
            //---- IP details ---//
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            $location = file_get_contents('http://freegeoip.net/json/'.$ip);
            $location = json_decode($location);
            $post['city']     = $location->city;
            $post['state']     = $location->region_name;
            $post['country']     = $location->country_name;
            $post['country_code']     = $location->country_code;
            $post['postal_code']     = $location->zip_code;
            $post['latitude']     = $location->latitude;
            $post['longitude']     = $location->longitude;
            $post['ip'] = $ip;
            
            //-- get camaign details --//
			$res = substr(strrchr($post['tag'],"?"),1);
			$arr = explode("/",$res);			
			//$post['banner_id'] = $arr[0];
			$post['campaign_id'] = $arr[1];
			$post['ads_id'] = $arr[3];
			$post['user_id'] = (@$arr[4] !='' ? @$arr[4]:0);					
			unset($post['tag']);
			unset($post['keywords']);
			//-- get campaign revenue ---//
				$campaignRevenue = $this->getCampaignRevenue($post['campaign_id']);
				if($campaignRevenue){
					$post['campaign_id'] = $campaignRevenue->result[0]->campaignid;
					if($campaignRevenue->result[0]->revenue_type==1){
						$revenue = $campaignRevenue->result[0]->revenue/100;
						$post['revenue'] = $revenue;
					}else{
						$post['revenue'] = 0;
					}					
				}				
				//$post['content_provider'] ='';
			//---------------------------//
			//--- get advertiser --//
			if($post['ads_id']){				
				$advertiser_id = $this->Ads_model->getAdvertiser($post['ads_id']);
				$post['content_provider'] = $advertiser_id = 21;
			}
			print_r($post);
			//-------------------//				
			echo $this->Analytics_model->save_ads($post);
		}
		//print_r($post);
		die;
	   }
	   
	function ads_skip()
        {
            $post = $_POST;           
            $where = array('id'=>$post['id']);
           echo $this->Analytics_model->save_ads($post,$where);
        }
        
        function ads_complete_post()
        {
            $post = $_POST;	    
	    if($post){			
			$where = array('id'=>$post['id']);
			echo $this->Analytics_model->save_ads($post,$where);
		}
         die;   
        }
		
	function getCampaignRevenue($id)
	{
		$this->load->helper('url');		
                $url = CAMPAIGN_REVENUE."&id=$id";
               // Get cURL resource
                $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                ));  
                // Send the request & save response to $resp
               $resp = curl_exec($curl);
               
                // Close request to clear up some resources
                curl_close($curl);
                return json_decode($resp);
	}
}