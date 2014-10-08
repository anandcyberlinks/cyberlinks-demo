<?php

class Page_model extends CI_Model {
    
    function __construct()
	{
            

		parent::__construct();
                
                
		$this->load->database();
	}
        
        function savePage($data)
	{
              if(isset($data['id'])){
			$catId = $data['id'];
			$data = array(
				'page_title'=>$data['page_title'],
    				'page_description'=>$data['page_description']
			);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $catId);
			$this->db->update('pages', $data); 		
		} else {
			$catData = array(
				'page_title'=>$data['page_title'],
    				'page_description'=>$data['page_description'],
				'status'=>$data['status'],
				'user_id'=>$data['user_id']
			);
			$this->db->set($catData);
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('pages');
			$catId = $this->db->insert_id() ; 
		}
		return $catId;
	}
	function checkPage($page, $user_id)
	{
		$query = $this->db->get_where('pages', array('page_title' => $page, 'user_id'=>$user_id));		
		$cat=count($query->result());		
		return $cat ;
	}
        
        function getPagedata ()
        {
            $this->db->select('*');
            $this->db->from('pages');
            $query = $this->db->get();
            return $query->result();
        }
         function editPagedata($pageid)
        {
            $this->db->select('*');
            $this->db->from('pages');
            $this->db->where('id', $pageid);
            $query = $this->db->get();
            return $query->result();
        }
        
        public function updatestatus($data) {

        $id = $data['id'];
        $status = $data['status'];
        if ($status == 'active') {
            $data = array('status' => '0',);
        }
        if ($status == 'inactive') {
            $data = array('status' => '1',);
        }
        $this->db->where('id', $id);
        $this->db->update('pages', $data);
    }
    function deletePage($id)
	{
		$this->db->delete('pages', array('id' => $id)); 		
		//return 1; 
	}
    function udatepage($data){
	$id = $data['id'];
	$this->db->where('id', $id);
        $this->db->update('pages', $data);
	 
    }
    
}
    
    