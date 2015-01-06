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
    
}
    ?>