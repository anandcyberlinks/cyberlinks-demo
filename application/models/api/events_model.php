<?php

class Events_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getCategories(){
        $this->db->where('u_id',$this->owner_id);
        $this->db->where('status',1);
        $query = $this->db->get('channel_categories');
        
        //echo $this->db->last_query();die;
        return $query->result();
    }
    
    function categoryEvents($cid='', $userid='', $param=array()){
        if($param){
            $this->db->limit($param['limit'],$param['offset']);            
        }
        
        $this->db->select("a.id as channel_id,a.name,a.keywords as tags,b.category as category_name,a.customer_id,d.first_name AS user_name,d.image as user_thumbnail,c.thumbnail_url as thumbnail,c.ios,c.android,c.windows,c.web,a.status",FALSE);
        $this->db->from('channels a');
        $this->db->join('channel_categories b','a.category_id=b.id','RIGHT');
        $this->db->join('livestream c','c.channel_id=a.id');
        //$this->db->join('livechannel_epg e','e.channel_id=a.id','left');
        $this->db->join('customers d','a.customer_id=d.id','LEFT');
        if($cid!=''){
            $this->db->where('a.category_id',$cid);
        }
        if($userid !=''){
            $this->db->where('a.customer_id',$userid);
        }
        $this->db->where('uid',$this->owner_id);
        $this->db->order_by('a.id','desc');
       $query = $this->db->get();       
     //  echo $this->db->last_query();die;
       $result = $query->result();
       
       if($result){
       array_walk ( $result, function (&$key) {                
                //-- epg ---//
               $key->epg = $this->getLivechannelEpg($key->channel_id);               
               //-- Vast file --//
              $key->vast = $this->getAdsRevive($lat,$lng,$age,$key->keywords,$gender);             
            } );
       }       
       return $result;
    }
    
    function subscribeEventsList($userid='', $param=array()){
        if($param){
            $this->db->limit($param['limit'],$param['offset']);            
        }
        
        $this->db->select("a.id as channel_id,a.name,a.keywords as tags,b.category as category_name,a.customer_id,d.first_name AS user_name,d.image as user_thumbnail,c.thumbnail_url as thumbnail,c.ios,c.android,c.windows,c.web,a.status",FALSE);
        $this->db->from('channels a');
        $this->db->join('channel_categories b','a.category_id=b.id','RIGHT');
        $this->db->join('livestream c','c.channel_id=a.id');
        $this->db->join('customers d','a.customer_id=d.id','LEFT');
        $this->db->join('subscribe_event e','e.channel_id=a.id');
                       
        if($userid !=''){
            $this->db->where('e.user_id',$userid);
        }
        $this->db->where('uid',$this->owner_id);
        $this->db->order_by('a.id','desc');
       $query = $this->db->get();       
      // echo $this->db->last_query();die;
       $result = $query->result();
       
       if($result){
       array_walk ( $result, function (&$key) {                
                //-- epg ---//
               $key->epg = $this->getLivechannelEpg($key->channel_id);               
               //-- Vast file --//
              $key->vast = $this->getAdsRevive($lat='',$lng='',$age='',$key->tags,$gender);             
            } );
       }       
       return $result;
    }
    
    
    function watchedEventsList($userid='', $param=array()){
        if($param){
            $this->db->limit($param['limit'],$param['offset']);            
        }
        
        $this->db->select("a.id as channel_id,a.name,a.keywords as tags,b.category as category_name,a.customer_id,d.first_name AS user_name,d.image as user_thumbnail,c.thumbnail_url as thumbnail,c.ios,c.android,c.windows,c.web,a.status",FALSE);
        $this->db->from('channels a');
        $this->db->join('channel_categories b','a.category_id=b.id','RIGHT');
        $this->db->join('livestream c','c.channel_id=a.id');
        $this->db->join('customers d','a.customer_id=d.id','LEFT');
        $this->db->join('event_watched e','e.channel_id=a.id');
                       
        if($userid !=''){
            $this->db->where('e.user_id',$userid);
        }
        $this->db->where('uid',$this->owner_id);
        $this->db->order_by('a.id','desc');
       $query = $this->db->get();       
      // echo $this->db->last_query();die;
       $result = $query->result();
       
       if($result){
       array_walk ( $result, function (&$key) {                
                //-- epg ---//
               $key->epg = $this->getLivechannelEpg($key->channel_id);               
               //-- Vast file --//
              $key->vast = $this->getAdsRevive($lat='',$lng='',$age='',$key->tags,$gender='');             
            } );
       }       
       return $result;
    }
    
    function getLivechannelEpg($channel_id){        
         $query = sprintf('select id,show_title,date as show_date,show_time,end_date,end_time,show_duration,show_thumb,show_language,show_description,show_type,media_type
                              from livechannel_epg where channel_id = %d order by show_time ',$channel_id);
        $dataset = $this->db->query($query)->result();
        if(count($dataset) > 0){
            return $dataset;
        }else{
            return array();
        }
    }
    
    function getEventDetail($id){
        $this->db->select("a.id,a.name,a.keywords as tags,b.category,a.customer_id,d.first_name AS user_name,d.image as user_thumbnail,c.thumbnail_url as thumbnail,c.ios,c.android,c.windows,c.web,e.show_title,e.date as start_date,e.show_time,e.end_date,e.end_time,e.show_duration,a.status",FALSE);
        $this->db->from('channels a');
        $this->db->join('channel_categories b','a.category_id=b.id');
        $this->db->join('livestream c','c.channel_id=a.id');
        $this->db->join('livechannel_epg e','e.channel_id=a.id','left');
        $this->db->join('customers d','a.customer_id=d.id');
        $this->db->where('a.id',$id);
        $this->db->limit(1);
        $query = $this->db->get();        
        return $query->row();        
    }
    
    public function saveEvents($data)
    {
        $channels = array('name' => $data['name'],
                          'event_code' => $data['event_code'],
                          'description'=>$data['description'],
                          'category_id'=> $data['category_id'],
                          'type' => $data['type'],
                          'keywords' => $data['keywords'],
                          'access_control' => $data['access'],
                          'status' => $data['status'],
                          'uid' => $data['uid'],
                          'customer_id' => $data['customer_id']                        
        );
        
        $this->db->set($channels);
        $this->db->set('created', 'NOW()', FALSE); 
        $this->db->insert('channels');
        $id =  $this->db->insert_id();
        if($id){
            //-- live stream data --//
                $stream = array('channel_id' => $id,
                            'user_id' => $data['uid'],
                            'thumbnail_url' => $data['thumbnail'],
                            'rtsp' => $data['rtsp'],
                            'ios' =>  $data['ios'],
                            'android' =>  $data['android'],
                            'windows' =>  $data['windows'],
                            'web' =>  $data['web'],
                            'source' => $data['source'],
                            'status' => 1
                            );                
                $this->db->set($stream);
                $this->db->set('created','NOW()', false);
                $this->db->insert('livestream');
            
            //--- epg data --//
            $epg = array('channel_id' => $id,
                         'channel_name' => ($data['name'] !='' ? $data['name']:""),
                             'date' => $data['start_date'],
                             'show_time' => $data['start_time'],
                             'end_date' => $data['end_date'],
                             'end_time' => $data['end_time'],
                             'show_title' => ($data['name'] !='' ? $data['name']:""),
                             'show_duration' => $data['show_duration']
                            );
                $this->db->set($epg);                
                $this->db->insert('livechannel_epg');
                
                //--- subscribe event ---//
                
            if($data['access'] =='private' && $data['susbcribe_ids'] !=''){
                $this->subscribe($data['susbcribe_ids'],$id);
            }
            return $id;            
        }else{
            return 0;
        }
    }
    
    function subscribe($user_ids='',$event_id='')
    {
        if($user_ids !='' && $event_id!=''){
            $data = array();
            $user_ids_arr = json_decode($user_ids);             
            if (is_array($user_ids_arr))
            {                
                for($i=0;$i<count($user_ids_arr);$i++){
                    
                    if( $this->db->where('channel_id', $event_id)->where('user_id', $user_ids_arr[$i])
                    ->limit(1)->get('subscribe_event')->num_rows())
                    {
                        //-- prevent duplicate entry --/                     
                    }else{
                        $data[$i]['user_id'] = $user_ids_arr[$i];
                        $data[$i]['channel_id'] = $event_id;
                    }
                }
            }else{
                 if( $this->db->where('channel_id', $event_id)->where('user_id', $user_ids)
                    ->limit(1)->get('subscribe_event')->num_rows())
                    {
                        //-- prevent duplicate entry --//                        
                    }else{
                        $data[0]['user_id'] = $user_ids;
                        $data[0]['channel_id'] = $event_id;
                    }                
            }
            //print_r($data);die;
            if(count($data) >0){
            $this->db->insert_batch('subscribe_event', $data);
            return $this->db->insert_id();
            }
        }
    }
    
    function publishEvent($data)
    {
        $this->db->set('status',0);		
		$this->db->insert('event_otp',$data);
        return $this->db->insert_id();
    }
    
    function checkOtp($data)
    {
        $this->db->select('o.id,c.name as channel_name,e.date,e.show_time');
        $this->db->from('event_otp o');
        $this->db->join('channels c','c.id=o.channel_id');
        $this->db->join('livechannel_epg e','e.channel_id=c.id','left');
        $this->db->where('o.channel_id',$data['event_id']);
        $this->db->where('o.user_id',$data['user_id']);
        $this->db->where('o.otp',$data['otp']);
        $this->db->limit(1);
        $query = $this->db->get();
       $result = $query->row();
        if($result){
        //-- delete otp once verified --//
      //  $this->db->where('id',$result->id);
       // $this->db->delete('event_otp');
        //---------//
        return $result;
        } else
        return 0;
    }
    
    function like($data)
    {
        if($this->db->where('content_id', $data['content_id'])->where('user_id', $data['user_id'])
                    ->limit(1)->get('likes')->num_rows()){
            //-- if already exist update data --//
            $this->db->set('modified','NOW()',false);
            $this->db->where('content_id',$data['content_id']);
            $this->db->where('user_id',$data['user_id']);
            $this->db->update('likes',$data);
            return 1;
        }else{
            $this->db->set('created','NOW()',false);
            $this->db->insert('likes',$data);
            return 1;
        }
        return 0;
    }
    
    function watched($data)
    {
		$this->db->insert('event_watched',$data);
        return $this->db->insert_id();
    }
        
    function getAdsRevive($lat='',$lng='',$age='',$keywords='',$gender='',$l='')
	{		
		$this->load->helper('url');		
                //$url = CAMPAIGN_URL."?zone=".$this->zone_id."&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$l";
				$url ="http://multitvsolution.com/multitv/Api/public/index.php/ads/getvast?zone=11&keyword=$keywords&age=$age&gender=$gender&lat=$lat&lng=$lng&limit=$l&apikey=b3639adf52880e2d1ba1accb5d8875fdfb0a536b2b657a4c0c3d361061ca017b";
			//$url ="http://multitvsolution.com/multitv/Api/public/index.php/ads/getvast?zone=$this->zone_id&keyword=$keywords&lat=$lat&lng=$lng&limit=$limit&apikey=b3639adf52880e2d1ba1accb5d8875fdfb0a536b2b657a4c0c3d361061ca017b";
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
    ?>