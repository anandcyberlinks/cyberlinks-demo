<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Genre_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/* Get Record Count */
	function getRecordCount($data=''){
		$this->db->select('*');
		$this->db->from('genres');  
		if(isset($data['genre_name'])&& $data['genre_name']!='')
		{			
			$this->db->like('genre_name',trim($data['genre_name']));
		}
		$query = $this->db->get();  
		return count($query->result());	
	}
	
	/*	Get Genre  */	
	function getGenre($limit, $start, $sort='', $sort_by='', $data){
		$this->db->select('*');
		$this->db->from('genres');  
		if(isset($data['genre_name'])&& $data['genre_name']!='')
		{			
			$this->db->like('genre_name',trim($data['genre_name']));
		}
		$this->db->limit($limit, $start);
		$query = $this->db->get(); 		
		return $query->result();
	}

	function saveGenre($data){
		if(isset($data['id'])){
			$catId = $data['id'];
			$data = $_POST;
			$this->db->set('modified','NOW()',FALSE);
			$this->db->where('id', $catId);
			$this->db->update('genres', $data); 		
		} 
		else
		{
			$this->db->insert('genres', $data);
		}
    }
    
    function genreName($id){
	$this->db->select('genre_name');
	$this->db->where('id', $id);
	$query = $this->db->get('genres');
	return $query->result();
    }	
    
    function delete_genre($id)
	{
		$this->db->delete('genres', array('id' => $id)); 	
		return 1;
	}
	
	function checkIfGenreExists($genre){
		$this->db->select('id');
		$this->db->where('genre_name', $genre);
		$query = $this->db->get('genres');
		$result = $query->result();
		return count($result); 		
	}
	
		/*	Get All Category  */	
	function getAllGenre()
	{
		$this->db->select('*');
		$this->db->from('genres');  
		$this->db->order_by('id', 'asc');		
		$query = $this->db->get();
		return $query->result() ;
	}
	
	
	
}
?>
