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
    
    function categoryEvents($cid=''){
        $this->db->select('a.*,b.title as category_name');
        $this->db->from('events a');
        $this->db->join('event_category b','a.category=b.id');
        if($cid!=''){
            $this->db->where('category',$cid);
        }
       // $sql = "SELECT a.*,b.title as category_name FROM `events` a JOIN `event_category` b ON a.category=b.id";
       $query = $this->db->get();       
       // $query = $this->db->query($sql);
        return $query->result();
    }
}
    ?>