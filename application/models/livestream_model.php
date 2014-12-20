<?php

class Livestream_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getList($limit, $start,$data)
    {
        if(isset($data['channel_name'])&& $data['channel_name']!='')
	{			
	    $this->db->like('channel_name',trim($data['channel_name']));
	}
        $this->db->limit($limit, $start);
        $this->db->select('*');
        $this->db->from('livestream');                
        $query = $this->db->get();
        echo $this->db->last_query();
        return $query->result();
    }
    
    function saveUrl($data,$type)
    {
        $this->db->set($data);
        if($type == 'update'){
            $this->db->set('modified','NOW()',FALSE);
            $this->db->where('user_id', $data['user_id']);
            $this->db->update('livestream', $data);
             
        }else{
        $this->db->set('created','NOW()',FALSE);
        $this->db->insert('livestream');
        return $id = $this->db->insert_id();
        }
    }
    
    function getStream($uid)
   {   
    $this->db->select('*');
    $this->db->from('livestream');
    $this->db->where('user_id',$uid);
    $query = $this->db->get();
    return $query->row();
   }
}
?>