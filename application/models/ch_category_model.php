<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ch_category_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	/* Get Record Count */
	function getRecordCount($uid, $data=''){
		$this->db->select('a.*,b.category as parent');
		$this->db->from('channel_categories a');  
		$this->db->join('channel_categories b','a.parent_id = b.id','left');
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
	function getCategoryOld($uid, $limit, $start, $sort='', $sort_by='', $data){
		$this->db->select('a.*,b.category as parent');
		$this->db->from('channel_categories a');  
		$this->db->join('channel_categories b','a.parent_id = b.id','left');
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
        
        function getCategory($uid, $sort='', $sort_by='', $data){
		$this->db->select('a.*,b.category as parent');
		$this->db->from('channel_categories a');  
		$this->db->join('channel_categories b','a.parent_id = b.id','left');
                $this->db->order_by('a.index');
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
				'color'=>$data['color'],
				'file_id'=>$data['file_id'],
				'status'=>$data['status'],	
				'range_from'=>$data['range_from'],	
				'range_to'=>$data['range_to']	
			);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $catId);
			$this->db->update('channel_categories', $data); 		
		} else {
			$catData = array(
				'category'=>$data['category'],
				'parent_id'=>$data['parent_id'],
				'description'=>$data['description'],
				'color'=>$data['color'],
				'file_id'=>$data['file_id'],
				'status'=>$data['status'],
				'u_id'=>$data['u_id'],
                                'range_from'=>$data['range_from'],	
				'range_to'=>$data['range_to']
			);
			$this->db->set($catData);
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('channel_categories');
			$catId = $this->db->insert_id() ; 
		}
		return $catId;
	}
	
	function _saveFile($data){
        $filename = $data['filename'];
        if(isset($filename)){
            ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['status'] = $data['status'];
            $file['uid'] = $data['uid'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $fid = $this->db->insert_id();

        }
        return $fid;
    }
		
	/*	Get All Parent Cetogory  */	
	function getAllParentCategory($id='')
	{
		$this->db->select('a.*,b.category as parent, f.name as filename');
		$this->db->from('channel_categories a');  
		$this->db->join('channel_categories b','a.parent_id = b.id','left');
		$this->db->join('files f','a.file_id = f.id','left');	
		$this->db->order_by('b.category', 'asc');
		if($id !=''){ 
			$this->db->where('a.id',$id);
		}
		$query = $this->db->get();
		return $query->result() ;
	}
	
		/*	Get All Parent Cetogory  */	
	function getAllCatList($uid)
	{
		$this->db->select('a.*,b.category as parent, f.name as filename');
		$this->db->from('channel_categories a');  
		$this->db->join('channel_categories b','a.parent_id = b.id','left');
		$this->db->join('files f','a.file_id = f.id','left');	
		$this->db->order_by('b.category', 'asc');
		$this->db->where('a.u_id',$uid);
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Delete Category  */
	function delete_category($id)
	{
		$this->db->delete('channel_categories', array('id' => $id)); 	
		return 1;
	}
	
	function getCategoryChild($id){
		$this->db->where('parent_id', $id);
		$query = $this->db->get('channel_categories');
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
		$this->db->from('channel_categories');  
		$this->db->order_by('category', 'asc');		
		$query = $this->db->get();
		return $query->result() ;
	}

	/*	Check Category	*/
	function checkCategory($category, $uid)
	{
		$query = $this->db->get_where('channel_categories', array('category' => $category, 'u_id'=>$uid));		
		$cat=count($query->result());		
		return $cat ;
	}
		
	function getCatId($category, $uid)
	{
		$this->db->select('id');
		$this->db->where('category',$category);
		$this->db->from('channel_categories');
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
				'color'=>'',
				'file_id'=>'',
				'status'=>1,
				'u_id'=>$uid
			);
			$catId = $this->_saveCategory($catData);
			return $catId;
		}
		
	}
	
	function delCategoryImage($fileId){
		$this->db->delete('files', array('id' => $fileId)); 	
		return 1;
	}
	
	function checkToken($uid){
		$this->db->select('token');
		$this->db->where('user_id',$uid);
		$this->db->from('api_token');  	
		$query = $this->db->get();
		$result = $query->result();
		if(count($result)==0){
			$token = uniqid();
			$this->db->insert('api_token', array('owner_id'=>$uid, 'token'=> $token));
			return $token;
		}else{
			return $result[0]->token;
			
		}
	}
	
}
?>
