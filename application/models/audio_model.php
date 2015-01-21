<?php

class Audio_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    function getAudio($uid, $search=''){
        $this->db->select('audio.*, categories.category');
        $this->db->from('audio');
        $this->db->join('categories','categories.id = audio.category_id','left');
        if(isset($search['title'])){
            $this->db->where('audio.title', $search['title']);
        }
        if(isset($search['category'])){
            $this->db->where('audio.category_id', $search['category']);
        }
        $this->db->where('audio.u_id', $uid);
        return $this->db->get()->result();
        
    }
    
    function _saveAudio($data){
        if(isset($data['id'])){
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            $this->db->set($data);
            $this->db->update('audio');
        }else{
            $this->db->set($data);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('audio');
            $id = $this->db->insert_id();
            return $id;
        }
    }
    
    function audioProfile($id){
        $this->db->where('id', $id);
        $result = $this->db->get('audio')->result();
        return $result;
    }
    function fetchCat($uid){
       
        $this->db->select('id, category');
        $this->db->where('u_id', $uid);
        $this->db->where('type', 'audio');
        $result = $this->db->get('categories')->result();
        return $result;
    }
}