<?php

class Events_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getCategories(){
        $query = $this->db->get('event_category');
        return $query->result();
    }
    
    function categoryEvents($cid='', $userid=''){
        $this->db->select("a.*,b.title as category_name,c.id as user_id,c.first_name AS user_name",FALSE);
        $this->db->from('events a');
        $this->db->join('event_category b','a.category=b.id','RIGHT');
        $this->db->join('customers c','a.uid=c.id','LEFT');
        if($cid!=''){
            $this->db->where('category',$cid);
        }
        if($userid !=''){
            $this->db->where('a.uid',$userid);
        }
        $this->db->order_by('a.id','desc');
       $query = $this->db->get();       
      // echo $this->db->last_query();die;
        return $query->result();
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
                          'event_id' => $data['event_id'],
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
                         'channel_name' => $data['name'],
                             'date' => $data['start_date'],
                             'show_time' => $data['start_time'],
                             'end_date' => $data['end_date'],
                             'end_time' => $data['end_time'],
                             'show_title' => $data['name'],
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
            $user_ids_arr = json_decode($user_ids);
            $data = array();
            for($i=0;$i<count($user_ids_arr);$i++){
                $data[$i]['user_id'] = $user_ids_arr[$i];
                $data[$i]['channel_id'] = $event_id;
            }
            $this->db->insert_batch('subscribe_event', $data);         
        }
    }
    
    function publishEvent($data)
    {
        $this->db->set('status',0);		
		$this->db->insert('event_otp',$data);
        return $this->db->last_query();
    }
}
    ?>