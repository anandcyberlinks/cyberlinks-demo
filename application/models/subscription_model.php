<?php

class Subscription_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
	
    /********  function for Subscription section starts  ****************/

	/*Get Search Subscription */
    function getSearchSubscription($data, $uid, $sort = '', $sort_by = ''){
	$this->db->select('*');
        $this->db->from('duration');
	$this->db->where('uid', $uid);
	if(isset($data['title'])&& $data['title']!=''){			
	    $this->db->like('name',trim($data['title']));
	}
	$this->db->order_by($sort, $sort_by);
	$query = $this->db->get(); 		
	return $query->result();
    }
	
    function checkSubscription($name, $uid){
	$query = $this->db->get_where('duration', array('name' => $name, 'uid'=>$uid));		
	$subscription=count($query->result());		
	return $subscription ;
    }
	
    function saveSubscription($post){
	$this->db->insert('duration', $post);
    }
	/*	Edit Subscription	*/
    function editSubscription($id){
	$this->db->select('*');
	$this->db->from('duration');  
	$this->db->where('id',$id);	
	$query = $this->db->get();		
	return $query->result(); 
    }
	
    function update_Subscription($post){
	$data = array(
	'name'=>$post['name'],
	'days'=>$post['days'],
	'status'=>$post['status'],
	'modified'=>$post['modified'],	
	);		
	$this->db->where('id', $post['id']);
	$this->db->update('duration', $data); 		
	return true; 		
    }

	/*Delete Subscription  */
    function deleteSubscription($id){
	if($id){
	    $this->db->delete('duration', array('id' => $id)); 	
	    return 1;
	} else {
	    return 0;
	}
    }
    function changestatus($data){
	$this->db->where('id', $data['id']);
	$this->db->update('duration', array('status'=> $data['status']));
	
    }
    
    public function Checkusername($data) {
        $name = $data['name'];
	$id = $data['id'];
        $this->db->select('*');
        $this->db->where('name', $name);
	$this->db->where_not_in('id', $id);
        $query = $this->db->get('duration');
        return count($query->result());
    }
}
