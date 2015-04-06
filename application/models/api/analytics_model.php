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
    
    public function getReport($param=array())
    {     
        $group='';
        switch($param['type']){

        case 'content':
            $select = 'c.title,a.content_id,concat(u.first_name," ",u.last_name) as content_provider,count(content_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.pause =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
            $group = 'a.content_id';
            
            $join = "users u";
            $cond = "a.content_provider=u.id";
            
            //-- user contents --//
            if($param['id']>0){
                $this->db->where('a.user_id',$param['id']);
            }
            //--- search val --//
            if(@$param['search']){
                if($param['search']['title'] !=''){
                    $this->db->like('c.title',$param['search']['title']);
                }
                
                if($param['search']['contentprovider'] != ''){
                    $this->db->where('u.id',$param['search']['contentprovider']);
                }
                
                if($param['search']['startdate'] != '' && $param['search']['enddate']==''){
                    $this->db->where('DATE_FORMAT(a.created,"%Y-%m-%d") >',date('Y-m-d',strtotime($param['search']['startdate'])));
                }
                
                if($param['search']['startdate'] != '' && $param['search']['enddate']!=''){
                    $startdate = date('Y-m-d',strtotime($param['search']['startdate']));
                    $enddate = date('Y-m-d',strtotime($param['search']['enddate']));
                    
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
            }
            break;   
        case 'useragent':        
            $select = 'a.platform, a.browser, count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'a.platform, a.browser';
            break;
        case 'summary':
            $select = "count(distinct a.user_id) unique_hits,count(a.id) as total_hits,
            SUM(IF( a.complete =0 && a.pause =1, 1, 0 )) AS total_partial,
            SUM(IF(a.complete=1,1,0)) as total_complete,
            SUM(IF(a.replay=1,1,0)) as total_replay, sum( a.watched_time ) as total_watched_time";                    
            break;
        case 'map':
             $select = 'a.country_code as code,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'a.country_code';
            break;
        case 'country':
            $select = 'a.country_code as code,a.country,a.city,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'a.city';
            break;
        case 'content_provider':
            $select = 'concat(u.first_name," ",u.last_name) as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'a.content_provider';
            $join = "users u";
            $cond = "a.content_provider=u.id";          
            break;
        case 'customer':
            $select = 'u.id,concat(u.first_name," ",u.last_name) as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
            $group = 'u.id';           
            $join = "customers u";
            $cond = "a.user_id=u.id";
            break;
        }
        
        if($join !=''){
            
            $this->db->join($join,$cond );
        }
        if($type !='content_provider'){
            // $this->db->where('a.content_provider',$this->uid);
        }
        
        $this->db->select($select,false);
        $this->db->from('analytics a');
        $this->db->join('contents c','a.content_id=c.id');
       
        $this->db->group_by($group);
        $query = $this->db->get();
    //echo '<br>'.$this->db->last_query();
        return $query->result();
        
    }
    
    function getContentProvider()
    {
        $this->db->select('id,concat(first_name," ",last_name) as name',false);
        $this->db->from('users');
        $this->db->where('status','active');
        $this->db->where('role_id',1);
        $query = $this->db->get();
        return $query->result();
    }
}

?>