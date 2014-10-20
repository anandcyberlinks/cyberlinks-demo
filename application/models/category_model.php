<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	/* Get Record Count */
	function getRecordCount($uid, $data=''){
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');
		if(isset($data['category_name'])&& $data['category_name']!='')
		{			
			$this->db->like('a.category',trim($data['category_name']));
		}
		if(isset($data['parent_id'])&& $data['parent_id']!='')
		{
			$this->db->where('a.parent_id',$data['parent_id']);
		}
		$this->db->where('a.u_id', $uid);
		$query = $this->db->get();  
		return count($query->result());	
	}
	
	/*	Get Catogory  */	
	function getCategory($uid, $limit, $start, $sort='', $sort_by='', $data){
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');
		if(isset($data['category_name'])&& $data['category_name']!='')
		{			
			$this->db->like('a.category',trim($data['category_name']));
		}
		if(isset($data['parent_id'])&& $data['parent_id']!='')
		{
			$this->db->where('a.parent_id',$data['parent_id']);
		}
		$this->db->where('a.u_id', $uid);
		$this->db->limit($limit, $start);
		$query = $this->db->get(); 		
		return $query->result();
	}
	
	/*	Save Category  */
	function _saveCategory($data)
	{
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
			$catData = array(
				'category'=>$data['category'],
				'parent_id'=>$data['parent_id'],
				'description'=>$data['description'],
				'status'=>$data['status'],
				'u_id'=>$data['u_id']
			);
			$this->db->set($catData);
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('categories');
			$catId = $this->db->insert_id() ; 
		}
		return $catId;
	}
		
	/*	Get All Parent Cetogory  */	
	function getAllParentCategory($id='')
	{
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');		
		$this->db->order_by('b.category', 'asc');
		if($id !=''){ 
			$this->db->where('a.id',$id);
		}
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Delete Category  */
	function delete_category($id)
	{
		$this->db->delete('categories', array('id' => $id)); 	
		return 1;
	}
	
	function getCategoryChild($id){
		$this->db->where('parent_id', $id);
		$query = $this->db->get('categories');
		return count($query->result());
	}
        
    function getCategoryVideo($id){
		$this->db->where('category', $id);
		$query = $this->db->get('contents');
		return count($query->result());
    }
	
	/*	Get All Category  */	
	function getAllCategory()
	{
		$this->db->select('*');
		$this->db->from('categories');  
		$this->db->order_by('category', 'asc');		
		$query = $this->db->get();
		return $query->result() ;
	}

	/*	Check Category	*/
	function checkCategory($category, $uid)
	{
		$query = $this->db->get_where('categories', array('category' => $category, 'u_id'=>$uid));		
		$cat=count($query->result());		
		return $cat ;
	}
		
	function getCatId($category, $uid)
	{
		$this->db->select('id');
		$this->db->where('category',$category);
		$this->db->from('categories');
		$query = $this->db->get();		
		$result = $query->result();
		if(count($query->result()))
		{
			$catId = $result[0]->id;
			if($catId != "") {
				return $catId;
			} else {
				return 0;
			}
		} else {
			$catData = array(
				'category'=>$category,
				'parent_id'=>'',
				'description'=>'',
				'status'=>1,
				'u_id'=>$uid
			);
			$catId = $this->_saveCategory($catData);
			return $catId;
		}
		
	}
	
}
?>
