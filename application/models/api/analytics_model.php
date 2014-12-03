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
    
    public function getReport($type='content')
    {
        $group='';
        switch($type){            

        case 'content':
            $select = 'c.title,a.content_id,count(content_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.pause =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
            $group = 'a.content_id';
            break;   
        case 'useragent':        
            $select = 'a.platform, a.browser, count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'a.platform, a.browser';
            break;
        case 'summary':
            $select = "count(a.id) as total_hits,
            SUM(IF( a.complete =0 && a.pause =1, 1, 0 )) AS total_partial,
            SUM(IF(a.complete=1,1,0)) as total_complete,
            SUM(IF(a.replay=1,1,0)) as total_replay, sum( a.watched_time ) as total_watched_time";            
            break;
        }
        
        $this->db->select($select,false);
        $this->db->from('analytics a');
        $this->db->join('contents c','a.content_id=c.id','inner');
        $this->db->where('a.content_provider',$this->uid);
        $this->db->group_by($group);
        $query = $this->db->get();
    //echo '<br>'.$this->db->last_query();
        return $query->result();
        
    }
}

?>