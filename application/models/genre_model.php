<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Genre_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	/* Get Record Count */
	function getRecord_count()
	{
		return $this->db->count_all("genres");
	}
	
	function addgenre($data){
        $this->db->insert('genres', $data);
    }
	
	
        
    function getparent()
	{
		$this->db->select('genre_name');
		
                $query = $this->db->get('genres');
		//$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result();
	}
        
    function fetch_video($id){
            $this->db->where('category', $id);
            $query = $this->db->get('contents');
            return count($query->result());
        }
	
	/*	Get Genre  */	
	function getGenre($limit, $start, $sort='', $sort_by='')
	{
		$this->db->select('*');
		$this->db->from('genres');
		
		$this->db->order_by($sort, $sort_by);
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result() ;
	}
	/*	Get Search Genre */
	function getSearchGenre($limit, $start, $data)
	{
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
	
	/*	Get Search Category Count */
	function getSearchCount($data)
	{
		$this->db->select('*');
		$this->db->from('genres');  
		
		if(isset($data['genre_name'])&& $data['genre_name']!='')
		{			
			$this->db->like('genre_name',trim($data['genre_name']));
		}
		
		$query = $this->db->get();  
		return $query->result();
	}
	
	
	
}
?>
