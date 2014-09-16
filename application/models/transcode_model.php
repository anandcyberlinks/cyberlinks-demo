<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transcode_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/* Get Record Count */
	function getRecord_count(){
		return $this->db->count_all("flavors");
	}
	
	/*	Get All Transcode  */	
	function getAllTranscode(){
		$this->db->select('*');
		$this->db->from('flavors');  		
		$this->db->order_by('device_name', 'asc');	
		$this->db->group_by('device_name');
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Get Transcode  */	
	function getTranscode($limit, $start, $sort='', $sort_by=''){
		$this->db->select('*');
		$this->db->from('flavors');  
		$this->db->order_by($sort, $sort_by);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Get Search Transcode */
	function getSearchTranscode($limit, $start, $data){
		$this->db->select('*');
		$this->db->from('flavors');  		
		if(isset($data['flavor_name'])&& $data['flavor_name']!=''){			
			$this->db->like('flavor_name',trim($data['flavor_name']));
		}
		if(isset($data['device_name'])&& $data['device_name']!=''){
			$this->db->where('device_name',$data['device_name']);
		}
		
		/*if(!empty($flavor_name))
		{
			$this->db->like('flavor_name',$flavor_name);
		}
		if(!empty($device_name))
		{
			$this->db->like('device_name',$device_name);   
		}*/
		$this->db->limit($limit, $start);
		$query = $this->db->get(); 		
		return $query->result();
	}
	
	/*	Get Search Transcode Count */
	function getSearchCount($flavor_name='', $device_name=''){
		$this->db->select('*');
		$this->db->from('flavors');  		
		if(isset($data['flavor_name'])&& $data['flavor_name']!='')
		{			
			$this->db->like('flavor_name',trim($data['flavor_name']));
		}
		if(isset($data['device_name'])&& $data['device_name']!='')
		{
			$this->db->where('device_name',$data['device_name']);
		}
		$query = $this->db->get();  
		return $query->result();
	}
	/*	Add Transcode  */
	function saveTranscode($data)
	{  
		$this->db->set($data);  
		$this->db->insert('flavors');
		$this->result = $this->db->insert_id() ; 
		return $this->result ;
	}
	
	/*	Delete Transcode  */
	function delete_transcode($id)
	{
		$this->db->delete('flavors', array('id' => $id)); 	
		return true;
	}
	
	/*	Edit Transcode	*/
	function editTranscode($id)
	{
		$this->db->select('*');
		$this->db->from('flavors');  
		$this->db->where('id',$id);
		$this->db->order_by('device_name', 'asc');					
		$query = $this->db->get();		
		return $query->result(); 
	}
	function update_transcode($post)
	{
		$data = array(
			'flavor_name' => $post['flavor_name'],
			'device_name' => $post['device_name'],
			'bitrate_type' => $post['bitrate_type'],
			'bitrate' => $post['bitrate'],
			'video_bitrate' => $post['video_bitrate'],
			'audio_bitrate' => $post['audio_bitrate'],
			'width' => $post['width'],
			'height' => $post['height'],
			'frame_rate' => $post['frame_rate'],
			'keyframe_rate' => $post['keyframe_rate'],
			'modified'=>$post['modified']
		);		
		$this->db->where('id', $post['id']);
		$this->db->update('flavors', $data); 		
		return true; 		
	}
	
}
?>
