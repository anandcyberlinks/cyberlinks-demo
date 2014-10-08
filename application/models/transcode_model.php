<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transcode_model extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	/* Get Record Count */
	function getRecordCount($data=''){
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
		return count($query->result());
	}
	
	/*	Get Transcode  */	
	function getTranscode($limit, $start, $sort='', $sort_by='', $data){
		$this->db->select('*');
		$this->db->from('flavors');
		if(isset($data['flavor_name'])&& $data['flavor_name']!=''){			
			$this->db->like('flavor_name',trim($data['flavor_name']));
		}
		if(isset($data['device_name'])&& $data['device_name']!=''){
			$this->db->where('device_name',$data['device_name']);
		}
		$this->db->order_by($sort, $sort_by);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result() ;
	}
	
	
	/*	Add Transcode  */
	function _saveTranscode($post)
	{		
		if($post['id']){
			$transcodeId = $post['id'];
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
				'keyframe_rate' => $post['keyframe_rate']
			);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $transcodeId);
			$this->db->update('flavors', $data); 		
		} else {
			$this->db->set($post);			
			$this->db->set('created','NOW()',FALSE);
			$this->db->set('modified','NOW()',FALSE);
			$this->db->insert('flavors');
			$transcodeId = $this->db->insert_id() ; 
		}
		return $transcodeId ;

	}
	
	/*	Get All Transcode  */	
	
	function getAllTranscode($id=''){
		$this->db->select('*');
		$this->db->from('flavors');
		if($id !=''){ 
			$this->db->where('id',$id);			
		}
		$this->db->order_by('device_name', 'asc');	
		$this->db->group_by('device_name');
		$query = $this->db->get();
		return $query->result() ;
	}
	
	/*	Delete Transcode  */
	function delete_transcode($id)
	{
		$this->db->delete('flavors', array('id' => $id)); 	
		return true;
	}
	
}
?>
