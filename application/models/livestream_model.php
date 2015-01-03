<?php

class Livestream_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getList($param =array())
    {	
	if(@$param['search']){
		   if($param['search']['searchuser'] !=''){
		       $this->db->where('u.id',$param['search']['searchuser']);
		   }
	}
	if($param['count']==1){
	    $this->db->select('count(l.id) as total');   
	}else{
	    $this->db->select('l.*,CONCAT(u.first_name ," ",u.last_name) as content_provider',false);
	}
	if($param['l'] > 0){
            $this->db->limit($param['l'],$param['start']);            
        }
	$this->db->from('livestream l');
	$this->db->join('users u','u.id=l.user_id');
	$query = $this->db->get();
	//echo $this->db->last_query();
        return $query->result();
    }
    
    function saveUrl($data,$type)
    {	
        $this->db->set($data);
        if($type == 'update'){
            $this->db->set('modified','NOW()',FALSE);
            $this->db->where('channel_id', $data['channel_id']);
            $this->db->update('livestream', $data);
             
        }else{
        $this->db->set('created','NOW()',FALSE);
        $this->db->insert('livestream');
        return $id = $this->db->insert_id();
        }
    }
    
    function getStream($cid)
   {
    $this->db->select('l.*,CONCAT(u.first_name ," ",u.last_name) as content_provider',false);
    $this->db->from('livestream l');
    $this->db->join('users u','u.id=l.user_id');
    //$this->db->where('user_id',$uid);
    $this->db->where('l.channel_id',$cid);
    $query = $this->db->get();
    //echo $this->db->last_query();
    return $query->row();
   }
   
   function getContentProvider($bool =false)
   {    
     
    if($bool){
	$this->db->where('l.user_id IS NULL');
    }
	$this->db->select('u.id,l.user_id,CONCAT(u.first_name," ", u.last_name) as content_provider',false);
	$this->db->from('users u');
	$this->db->join('livestream l','u.id=l.user_id','left');	
	$this->db->where('u.role_id',1);
	$query = $this->db->get();	
	return $query->result();
   }
   
   function getChannel($id)
   {    
         
	$this->db->select('name');
	$this->db->from('channels');	
	$this->db->where('id',$id);
	$this->db->limit(1);
	$query = $this->db->get();	
	return $query->row();
   }
   
   function updatestatus($data)
   {
    $this->db->set($data);
    $this->db->where('id',$data['id']);
    $this->db->update('livestream',$data);
   }
}
?>