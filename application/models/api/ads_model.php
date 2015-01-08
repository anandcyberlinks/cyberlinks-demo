<?php

class Ads_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getAds()
    {
        $this->db->select('a.id, a.ad_title as title,f.relative_path as url');
        $this->db->from('ads a');
        $this->db->join('files f','a.file_id=f.id');
        $query = $this->db->get();
        return $query->result();
    }
    
    function getAdsFlavour()
    {     
       $this->db->select('a.id as map_id,a.flavor_id,d.flavor_name,a.ads_id,c.name,c.minetype as content_type,c.absolute_path as video_file_name');
       $this->db->from('ads_flavors a');      
       $this->db->join('files c','a.file_id=c.id','inner');
       $this->db->join('flavors d','a.flavor_id=d.id','inner');
       $this->db->where('a.status','pending');      
       $query = $this->db->get(); 
        //echo '<br>'.$this->db->last_query();die;
        return $query->result();
   
    }
}
    ?>