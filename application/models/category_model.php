<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/* Get Record Count */
	function getRecord_count()
	{
		return $this->db->count_all("categories");
	}
	function fetchChild($id){
		$this->db->where('parent_id', $id);
		$query = $this->db->get('categories');
		return count($query->result());
	}
	
	/*	Get All Parent Cetogory  */	
	function getAllParentCategory()
	{
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');		
		$this->db->order_by('b.category', 'asc');
                //$this->db->distinct();
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result() ;
	}
        
        function getparent()
	{
		$this->db->select('id, category');	
                $query = $this->db->get('categories');
		//$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
        
    function fetch_video($id){
            $this->db->where('category', $id);
            $query = $this->db->get('contents');
            return count($query->result());
        }
	/*	Get All Cetogory  */	
	function getAllCategory()
	{
		$this->db->select('*');
		$this->db->from('categories');  
		$this->db->order_by('category', 'asc');		
		$query = $this->db->get();
		return $query->result() ;
	}
	/*	Get Cetogory  */	
	function getCategory($limit, $start, $sort='', $sort_by='')
	{
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');
		$this->db->order_by($sort, $sort_by);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Get Search Category */
	function getSearchCategory($limit, $start, $data)
	{
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
		$this->db->limit($limit, $start);
		$query = $this->db->get(); 		
		return $query->result();
	}
	
	/*	Get Search Category Count */
	function getSearchCount($data)
	{
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
		$query = $this->db->get();  
		return $query->result();
	}
	
	/*	Check Category	*/
	function checkCategory($category)
	{
		$query = $this->db->get_where('categories', array('category' => $category));		
		$cat=count($query->result());		
		return $cat ;
	}
	
	/*	Add Category  */
	function saveCategory($data)
	{  
		$this->db->set($data);  
		$this->db->insert('categories');
		$this->result = $this->db->insert_id() ; 
		return $this->result ;
	}
	
	/*	Delete Transcode  */
	function delete_category($id)
	{
		$this->db->delete('categories', array('id' => $id)); 	
		return 1;
	}

	/*	Edit Category	*/
	function editCategory($id)
	{
		$this->db->select('a.*,b.category as parent');
		$this->db->from('categories a');  
		$this->db->join('categories b','a.parent_id = b.id','left');
		$this->db->where('a.id',$id);
		$this->db->order_by('b.category', 'asc');					
		$query = $this->db->get();		
		return $query->result(); 
	}
	function update_category($post)
	{
		$data = array(
			'category'=>$post['category'],
			'parent_id'=>$post['parent_id'],
			'description'=>$post['description'],
			'status'=>$post['status'],
			'modified'=>$post['modified'],	
		);		
		$this->db->where('id', $post['id']);
		$this->db->update('categories', $data); 		
		return true; 		
	}
	
	function getCatId($category)
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
			return 0;
		}
		
	}
	
}
?>
