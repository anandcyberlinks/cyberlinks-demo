<?php

class Comment_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	function getComment($limit, $start, $id, $data)
	{		
		$this->db->select('a.*,b.username,c.title,d.category');
		$this->db->from('comment a');
		$this->db->join('users b', 'a.user_id = b.id', 'left');
		$this->db->join('contents c', 'a.content_id = c.id', 'left');		
		$this->db->join('categories d', 'c.category = d.id', 'left');
        $this->db->where('b.id',$id);
		if(isset($data['content_title'])&& $data['content_title']!='')
		{
			$this->db->like('c.title',trim($data['content_title']));
		}
		if(isset($data['comment'])&& $data['comment']!='')
		{
			$this->db->where('a.comment',trim($data['comment']));
		}
		if(isset($data['user'])&& $data['user']!='')
		{
			$this->db->where('b.username',$data['user']);
		}
		if(isset($data['user_ip'])&& $data['user_ip']!='')
		{
			$this->db->where('a.user_ip',trim($data['user_ip']));
		}
		$this->db->order_by('a.id', 'asc');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result() ;
	}
	
	function getCommentCount($id, $data='') 
	{	
		$this->db->select('a.*,b.username,c.title,d.category');
		$this->db->from('comment a');
		$this->db->join('users b', 'a.user_id = b.id', 'left');
		$this->db->join('contents c', 'a.content_id = c.id', 'left');		
		$this->db->join('categories d', 'c.category = d.id', 'left');
        $this->db->where('b.id',$id);
		if(isset($data['content_title'])&& $data['content_title']!='')
		{
			$this->db->like('c.title',trim($data['content_title']));
		}
		if(isset($data['comment'])&& $data['comment']!='')
		{
			$this->db->where('a.comment',trim($data['comment']));
		}
		if(isset($data['user'])&& $data['user']!='')
		{
			$this->db->where('b.username',$data['user']);
		}
		if(isset($data['user_ip'])&& $data['user_ip']!='')
		{
			$this->db->where('a.user_ip',trim($data['user_ip']));
		}
        $query = $this->db->get();
		return count($query->result()) ;
		
	}
			
	function getCommentData($id)
	{		
		$this->db->select('*');
		$this->db->from('comment');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function updateComment($post)
	{
		$data = array(
			'comment' => $post['comment'],
		);
		$this->db->set('updated_date','NOW()',FALSE);
		$this->db->where('id', $post['id']);
		$this->db->update('comment', $data); 
		return true; 		
	}
	
	function deleteComment($id)
	{
		$this->db->delete('comment', array('id' => $id)); 		
		return 1; 
	} 
	
	function setStatus($post)
	{
		$data = array(
			'status' => $post['status']
		);
		$this->db->set('updated_date','NOW()',FALSE);
		$this->db->where('id', $post['id']);
		$this->db->update('comment', $data); 
		return true; 
	} 
	function setApprovedStatus($post)
	{
		$data = array(
			'approved' => $post['approved']
		);
		$this->db->set('updated_date','NOW()',FALSE);
		$this->db->where('id', $post['id']);
		$this->db->update('comment', $data); 
		return true; 
	} 

}
?>
