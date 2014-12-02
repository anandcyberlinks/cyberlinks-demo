<?php

class Analytics_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    public function save($post,$where=null)
    {
        $this->db->set($post);
        if(@$post['pause'] || @$post['complete']){
            $this->db->set('modified','NOW()',false);
            $this->db->update('analytics',$post,$where);
            return $this->db->affected_rows();
        }else{
            $this->db->set('created','NOW()',false);
            $this->db->insert('analytics');
            return $this->db->insert_id();
        }        
    }
    
    public function getReport($type)
    {
        $this->db->select(' c.title,a.content_id,count(content_id) as total_hits,sum(watched_time) as total_watched_time,IF( a.complete =1, sum( a.complete ) , sum( a.complete ) ) AS complete, IF( a.complete =0
        AND a.pause =1, sum( a.pause ) , sum( a.pause ) ) AS partial, IF( a.replay =1, sum( a.replay ) , sum( a.replay ) ) AS replay');
        $this->db->from('analytics a');
        $this->db->join('contents c','a.content_id=c.id','inner');
        $this->db->where('a.content_provider',$this->uid);
        $this->db->group_by('a.content_id');
        $query = $this->db->get();
    //echo $this->db->last_query();die;
        return $query->result();

    }
    
    public function time_from_seconds($seconds) { 
        $h = floor($seconds / 3600); 
        $m = floor(($seconds % 3600) / 60); 
        $s = $seconds - ($h * 3600) - ($m * 60); 
        return sprintf('%02d:%02d:%02d', $h, $m, $s); 
	} 
}

?>