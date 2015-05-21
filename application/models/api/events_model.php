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
        $this->db->select('a.*,b.title as category_name');
        $this->db->from('events a');
        $this->db->join('event_category b','a.category=b.id');
        if($cid!=''){
            $this->db->where('category',$cid);
        }
        if($userid !=''){
            $this->db->where('a.uid',$userid);
        }       
       $query = $this->db->get();       
      // echo $this->db->last_query();die;
        return $query->result();
    }
    
    public function saveEvents($data)
    {
        $this->db->set($data);
        $this->db->set('created', 'NOW()', FALSE); 
        $this->db->insert('events', $data);
        $last_insert_id =  $this->db->insert_id();
        if($last_insert_id)
        {
            $data['id'] = $last_insert_id;
            unset($data['event_id']);
            return $data;
        }
    }
}
    ?>