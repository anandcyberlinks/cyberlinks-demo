<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Details extends MY_Controller {
protected $zone_id = ''; //-- content provider id -- temporary use --//
	function __construct()
	{
            parent::__construct();
	   
            $this->load->model('/api/Video_model');
	    $this->load->model('ads/Ads_model');
	  //  $this->load->model('/analytics/Analytics_model');
	    $this->load->library('User_Agent');	    
	    
	//-- get browser http_user_agent info in array --//
                //   $this->result = get_browser(null, true);		
		$this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		// print_r($_SERVER[HTTP_USER_AGENT]);die;
        //---------------------//		
	}

	function index()
	{		
		//-- get geocoding google api --//
		$this->data['lat'] = $lat = $_GET['lat'];
		$this->data['long'] = $lng = $_GET['lng'];
		$platform = $this->data['platform'] = ($_GET['platform'] !='' ? $_GET['platform']:$_GET['device']);
		$id = $_GET['id'];
		$type= $_GET['type'];
				 
		//-- log start --//
			//$this->log_load('db load','start',$id,$platform);
		//--------------//
	    	    
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$result = json_decode($data,true);
		//echo '<pre>';print_r($result);die;
		$this->data['geodata'] = $result['results'][0]['address_components'];
		//echo '<pre>';print_r($this->data['geodata']);
		//------------------------------//
		//-- get cue points for ads --//
		
		$limit = $this->Ads_model->getCuePoints($id,$type,1);
		$cuePoints = $this->Ads_model->getCuePoints($id,$type);		
		//----------------------------//
                
                if(@$cuePoints['0']!=0){
                    array_unshift($cuePoints, 0);
                    $limit += 1;
                }
                
                 if(count($cuePoints) <= 0){
                     $limit = 1;
                 }
		
		
		$user_data = $this->Ads_model->getUserKeywords($_GET['user_id']);
		//print_r($user_data);
		//--- Access Revive web service ---//
		/*$gender = $user_data['gender'];
		$dob = $user_data['dob'];
		$from = new DateTime($dob);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		*/
		$keywords = $user_data['keywords'];
		if($id ==56 ){
			$keywords ='newsnation';
		}
		
                //-- check for Ads source --//                
                $adconfig = $this->Ads_model->getAdsConfiguration($id,$type);
                
                switch ($adconfig) {
                    case "Local":
                        /* Local Ads */
                        $adsAlloc = $this->getAdsLocal($lat,$lng,$_GET['user_id'],$user_data,$limit);
                        break;
                    case "Revive":
                        /* Revive Ads */
                        $adsAlloc = $this->getAdsRevive($lat,$lng,$age,$keywords,$gender,$limit);
                        break;
                    default:
                        /* Local Ads By Default */
                        //$adsAlloc = $this->getAdsLocal($lat,$lng,$_GET['user_id'],$user_data,$limit);
                        $adsAlloc = $this->getAdsRevive($lat,$lng,$age,$keywords,$gender,$limit);                        
                }
		
		//echo '<pre>';print_r($user_data);die;
		//-- Ads for newsnation --//
		if($id ==56 && $type =='live'){  //- check if newsnation no ads display --//
			//-- Revive ad assing cue points array newsnation---//
			$i=0;
			foreach($adsAlloc->url as $key=>$val)
			{				
				//-- get ad id from revive ---//
				$res = substr(strrchr($val,"?"),1);				
				$arr = explode("/",$res);								
				$ad_id = $arr[3];
				
				if($ad_id){
					//-- get advertiser --//
					$advertiser_id = $this->Ads_model->getAdvertiser($ad_id);
					$adsFinal[$i]['advertiser'] 	= $advertiser_id;
					//-------------------//
					$adsFinal[$i]['ads_id'] = $ad_id;
				}
				//---------------------------//
				
				$adsFinal[$i]['vast_file'] = $val;
				if($i==0){
				$adsFinal[$i]['cue_points'] 	= 0;
				}else{
				$adsFinal[$i]['cue_points'] 	= '';
				}
				$adsFinal[$i]['nn'] 	= 1;
				$i++;
			}					
		//echo '<pre>';print_r($adsFinal);die;
		//---------------------------------------//
				
		}else{
			//-- ad assigning cue points array ---//
			$i=0;
                
			foreach($adsAlloc->url as $key=>$val)
			{
				//-- get ad id from revive ---//
				$res = substr(strrchr($val,"?"),1);				
				$arr = explode("/",$res);								
				$ad_id = $arr[3];
				
				if($ad_id){
				  //-- get advertiser --//
					$advertiser_id = $this->Ads_model->getAdvertiser($ad_id);
					$adsFinal[$i]['advertiser'] 	= $advertiser_id;
					//-------------------//
					$adsFinal[$i]['ads_id'] = $ad_id;
				}
				//---------------------------//
				
				$adsFinal[$i]['vast_file'] = $val;
				if(count($cuePoints) > 0){
				    $adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
				}else{
				    $adsFinal[$i]['cue_points'] 	= 0;
				}
				$i++;
			}
		}
		//----------------------------------//
		//print_r($adsFinal);die;
		//---Reset Cue point for tracking before ad play --//
		/*if(in_array(0,$cuePoints)){
			unset($cuePoints[0]);
			$cuePoints = array_values($cuePoints);			
		}
		if(count($cuePoints) >0){
		array_walk($cuePoints,function(&$value){($value >5 ? $value=$value-5: $value);});
		}*/
		//------------------------------//
                
		//$this->data['cuePoints'] = json_encode($cuePoints);
		
		//echo '<pre>';print_r($adsFinal);die;
		//echo '<pre>';print_r($keywords);die;
		$this->load->helper('url');
              
	      //-- get content token Wrench---//
		$authToken = $this->getContentToken();		
	    //--------------------------//
	    
		 $device = $_GET['device'];
		 $network = $_GET['network'];
		 $platform =$_GET['platform'];
		$this->data['user_id'] = $_GET['user_id'];
			//echo '<pre>';print_r($_SERVER);die;
		$this->data['uri'] = "http://".$_SERVER[SERVER_NAME].$_SERVER[REQUEST_URI];
		if($type=='live'){
			$result =  $this->Video_model->livestream_play($id,$device);
			$result->video_path = $result->video_path."?token=".$authToken->token;
			$this->data['result'] = $result;
		}else if($type=='linear'){
			$result = $this->Video_model->channel_play($id);
			//print_r($result);die;
			$urlArray = json_decode($result->video_path);			
			$url =  $urlArray[0]->$platform->$network;
			//print_r($result);
			$result->video_path = $url."?token=".$authToken->token;
			//echo '<pre>';print_r($result);die;
			//$result = $this->Video_model->video_play($id,$device);			
		/*	if($result){
				$this->data['result'] =$result;	
			}else{
				$this->data['result'] = $this->Video_model->video_play_youtube($id,'youtube');
			}
			*/			
		}else{
			//-- if Vod ---//
			$result = $this->Video_model->video_play($id,$network);
			//print_r($result);die;			
		}
		if($result->content_provider=='59')
		{
				$this->zone_id =7; //--- temporary use --//		
		}
		
		$this->data['result'] = $result;
		print_r($result);die;
		$this->data['scheduleBreaks'] = $adsFinal;
		//--- End db loading log ----//
		//$this->log_load('db load','End',$id,$platform);
		//----------------------------//				
		$this->load->view('details',$this->data);		
	}
	
	function getAdsRevive($lat,$lng,$age,$keywords,$gender,$l)
	{
		$this->load->helper('url');		
              echo  $url = CAMPAIGN_URL."?zone=".$this->zone_id."&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$l";
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
        
        function getAdsLocal($lat,$lng,$user_id,$user_data,$limit)
        {
            /* Local Ads */ 
            $adsAlloc = $this->Ads_model->getUserLocationWiseAds($lat,$lng,$user_id,$user_data,$limit);
            //echo '<pre>';print_r($adsAlloc);die;
            $i=0;
            foreach($adsAlloc as $row){

                    $adsFinal[$i]['file_name'] 	= $row->file_name;
                    $adsFinal[$i]['vast_file'] 	= $row->vast_file;
                    $adsFinal[$i]['ads_id'] 	= $row->ads_id;
                    $adsFinal[$i]['uid'] 		= $row->uid;
                    $adsFinal[$i]['cue_points'] 	= @$cuePoints[$i];
                    $adsFinal[$i]['ad_type'] 	= $row->ad_type;
                    $i++;
            }
            return $adsAlloc;
        }
	
        
        function addview()
        {
            $id = $_GET['id'];
            $this->Video_model->updateView($id);   
            echo 1;
        }
	
	function switch_ad()
	{
		$result = $this->Ads_model->get_swtich();
		echo $result->status;
		die;
	}
	
	function log_load($title,$pos,$user,$device)
	{
		if($device =='web'){
		$title = ($title==''? $_POST['title']:$title);
		$pos = ($pos==''? $_POST['pos']:$pos);
		//$time = date('Y-m-d h:i:s:u');
		
		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
		$time = $d->format("Y-m-d H:i:s.u"); // note at point on "u"
		$separator =",";
		$file = "log/".$_GET['user_id']."_stream_log.csv";		
		$fileHandle = fopen($file, 'a+'); // Note that the mode has changed		
		fwrite($fileHandle, $data);
		$data = $title.' '.$pos.  $separator.$time.$separator; // set data we will be writing
		//$data = $time.';';
		if($pos=='Stop')
		{
			$data .= "\n";
		}
		fwrite($fileHandle, $data); // write data to file 
		fclose($fileHandle); // close the file since we're done
		}		
	}
	
	function getCampaign()
	{
		$this->load->helper('url');		
                $url = base_url()."api/ads/campaign/id/$id/user_id/$user_id/type/$type";
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
	
	function getContentToken()
	{
		$this->load->helper('url');		
                $url = base_url()."api/auth/generatecontenttoken";
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

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
