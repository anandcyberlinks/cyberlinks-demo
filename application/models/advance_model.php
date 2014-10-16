<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advance_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function fetchForm($uid){
		$this->db->where('uid', $uid);
		$query = $this->db->get('forms');
		return $query->result();
	}
	
	function fetchFormName($id){
		$this->db->select('form_name');
		$this->db->where('id', $id);
		$query = $this->db->get('forms');
		return $query->result();
	}
	
	/* Get Record Count */
	function getRecordCount($data='')
	{
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');
		if(isset($data['category_name'])&& $data['category_name']!='')
		{			
			$this->db->like('a.category',trim($data['category_name']));
		}
		if(isset($data['parent_id'])&& $data['parent_id']!=''){
			$this->db->where('a.parent_id',$data['parent_id']);
		}
		$query = $this->db->get();  
		return count($query->result());	
	}
	
	/*	Get Catogory  */	
	function getfield($limit, $start, $sort='', $sort_by='', $data, $id){
		$this->db->where('form_id', $id);
		$this->db->limit($limit, $start);
		$query = $this->db->get('fields'); 		
		return $query->result();
	}
	
	/*	Save Category  */
	function _saveFields($postdata){
		if(isset($data['id'])){
			$catId = $data['id'];
			$data = array(
				'category'=>$data['category'],
				'parent_id'=>$data['parent_id'],
				'description'=>$data['description'],
				'status'=>$data['status']	
			);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $catId);
			$this->db->update('categories', $data); 		
		} else {
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('fields',$postdata);
			$catId = $this->db->insert_id();
		}
		return $catId;
	}

	
	/*	Save Form  */
	function _saveForm($postdata){
//		print_r($postdata);
			unset($postdata['submit']); 
		if(isset($postdata['id'])){
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $postdata['id']);
			$this->db->update('forms', $postdata); 		
		} else {
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('forms',$postdata);
			$catId = $this->db->insert_id();
		}
		return $catId;
	}
	
	function formdata($id){
		$this->db->where('id', $id);
		$query = $this->db->get('forms');
		return $query->result();
	}
	/*	Delete Category  */
	function delete_category($id)
	{
		$this->db->delete('categories', array('id' => $id)); 	
		return 1;
	}

}
