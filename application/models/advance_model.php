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
		//echo $this->db->last_query(); die;
		return $query->result();
	}
	
	/*	Save Category  */
	function _saveFields($postdata){
		if(isset($postdata['id'])){
			$fid = $postdata['id'];
			$data = array(
				'category'=>$postdata['category'],
				'parent_id'=>$postdata['parent_id'],
				'description'=>$postdata['description'],
				'status'=>$postdata['status']	
			);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $fid);
			$this->db->update('fields', $data); 		
		} else {
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('fields',$postdata);
			//echo $this->db->last_query(); die;
			$fid = $this->db->insert_id();
		}
		return $fid;
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
	/*	Delete Field  */
	function deleteField($id){
		$this->db->delete('fields', array('id' => $id)); 	
		return 1;
	}
	
	function deleteForm($id){
		$this->db->delete('forms', array('id' => $id));
		$this->db->delete('fields', array('form_id' => $id));
		return 1;
	}

}
