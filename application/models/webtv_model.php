<?php
class Webtv_model extends CI_Model{
        
    function __construct() {
        parent::__construct();
    }
    
    function changeStatus($data){
        $this->db->where('content_id', $data['id']);
        $this->db->where('playlist_id', $data['p_id']);
        $this->db->set('status', $data['status']);
        $this->db->update('playlist_video');
        return TRUE;
    }
    
    function fetchplaylists($uid, $start, $limit, $data, $channel_id) {
        $this->db->select('p.*, SUM(if(pv.id is not null,1,0)) as `total` ',false);
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";

        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('p.name', $data['name']);
        }
        if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $datestart . $timeEnd;
            $this->db->where("p.start_date > '$dateTimeStart'");
        }
        if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $dateend . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("p.end_date < '$dateTimeEnd'");
        }

        $this->db->where('p.uid', $uid);
        $this->db->where('p.id > 0');
        $this->db->where('p.channel_id', $channel_id);
        $this->db->from('playlists p');
        $this->db->join('playlist_video pv', 'pv.playlist_id = p.id', 'left');
        $this->db->group_by('p.id');
        $this->db->limit($start, $limit);
        $result = $this->db->get()->result();
        return $result;
    }
    
        function fetchchannels($uid, $start, $limit, $data) {
        $this->db->select('ch.*, cc.category');
        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('name', $data['name']);
        }
        $this->db->where('ch.uid', $uid);
        $this->db->from('channels ch');
        $this->db->join('channel_categories cc', 'ch.category_id = cc.id', 'left');
        $this->db->limit($start, $limit);
        $result = $this->db->get()->result();
        //echo $this->db->last_query();
        //print_r($result);
        return $result;
    }
    
    function countAll($uid, $data, $channel_id) {
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
    
        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('name', $data['name']);
        }
        if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $datestart . $timeEnd;
            $this->db->where("start_date > '$dateTimeStart'");
        }
        if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $dateend . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("end_date < '$dateTimeEnd'");
        }

        $this->db->where('uid', $uid);
        $this->db->where('channel_id', $channel_id);
        $this->db->from('playlists');
        return $this->db->count_all_results();
    }
    
    function countAll_channels($uid, $data) {
        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('name', $data['name']);
        }
        $this->db->where('uid', $uid);
        $this->db->from('channels');
        return $this->db->count_all_results();
    }
    
    function insert($post) {
        if (isset($post['id'])) {
            $this->db->where('id', $post['id']);
            unset($post['id']);
            $this->db->update('playlists', $post);
        } else {
            $this->db->set('created', 'NOW()', FALSE);
            $this->db->insert('playlists', $post);
        }
    }
    function get_category($uid){
        $this->db->select('id, category');
        $this->db->where('u_id', $uid);
        $this->db->from('channel_categories');
        $result = $this->db->get()->result();
        //print_r($result); die;
        return $result;
        
    }
            function insert_channels($post) {
        if (isset($post['id'])) {
            $this->db->where('id', $post['id']);
            unset($post['id']);
            $this->db->update('channels', $post);
        } else {
            $this->db->set('created', 'NOW()', FALSE);
            $this->db->insert('channels', $post);
        }
    }
    function fetchEventbyId($id) {
        $this->db->where('id', $id);
        return $this->db->get('playlists')->result();
    }
    
    function fetchChannelsbyId($id) {
        $this->db->where('id', $id);
        return $this->db->get('channels')->result();
    }
    
    function delete($id){
        echo 'anand';
        exit;
        //$this->db->delete('playlists', array('id'=>$id));
        //return TRUE;
    }
    
    function delete_channels($id){
        $this->db->select('id,type');
        $this->db->from('channels');
        $this->db->where('id',$id);
        $data = $this->db->get()->result();
        if(isset($data[0]->type) && $data[0]->type != ''){
            switch(strtolower($data[0]->type)){
                case 'loop':
                    //Get all playlist of channels
                    $this->db->select('id');
                    $this->db->from('playlists');
                    $this->db->where('channel_id',$id);
                    $tmp = $this->db->get()->result();
                    $playlist_ids = array();
                    
                    //Default value
                    $playlist_ids[] = 0;
                    
                    foreach($tmp as $key=>$val){
                        $playlist_ids[] = $val->id;
                    }
                    
                    //Delete from playlist_epg
                    $this->db->where_in('playlist_id',$playlist_ids);
                    $this->db->delete('playlist_epg');
                    
                    //Delete from playlist_video
                    $this->db->where_in('playlist_id',$playlist_ids);
                    $this->db->delete('playlist_video');
                    
                    //Delete from playlists
                    $this->db->where_in('id',$playlist_ids);
                    $this->db->delete('playlists');
                    
                    //Delete from channels list
                    $this->db->delete('channels', array('id'=>$id));
                    break;
                case 'live' :
                case 'youtube' :
                    //Detele data from livestream table
                    $this->db->where('channel_id',$id);
                    $this->db->delete('livestream');
                    
                    //Delete from channels list
                    $this->db->delete('channels', array('id'=>$id));
                    
                    break;
            }
        }
        return TRUE;
    }
    function get_videoid($id, $ids){
        $this->db->select('a.*, b.category , e.name as file,e.minetype, c.id as vpid,c.color,c.playlist_id');
        $this->db->from('contents a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('playlist_video c', 'a.id = c.content_id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        $this->db->group_by('a.id');
        $this->db->where('c.playlist_id', $id); 
        $this->db->where('c.status', '1');
        $this->db->where_not_in('a.id', $ids);
        $query = $this->db->get();
       //echo $this->db->last_query();
        $data = $query->result();
       //echo "<pre>"; print_r($data); exit;
        return $data;
    }
    
    function get_video($id){
        $this->db->select('a.*, b.category , e.name as file,e.minetype, c.id as vpid,c.color,c.playlist_id, c.status,c.index');
        $this->db->from('contents a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('playlist_video c', 'a.id = c.content_id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        $this->db->order_by('c.index');
        $this->db->group_by('a.id');
        $this->db->where('c.playlist_id', $id); 
        $query = $this->db->get();
       //echo $this->db->last_query();
        $data = $query->result();
       //echo "<pre>"; print_r($data); exit;
        return $data;
    }
    
    function getemgVideo($id){
        $this->db->select('content_id as id');
        $this->db->where('playlist_id', $id);
        $this->db->from('playlist_epg');
        $result = $this->db->get()->result();
        //print_r($result); die;
        return $result;
    }
            
    
    function get_epgvideo($id, $ids){
        $this->db->select('a.*, b.category , e.name as file,e.minetype, c.id as vpid,c.color');
        $this->db->from('contents a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('playlist_video c', 'a.id = c.content_id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        $this->db->group_by('a.id');
        $this->db->where('c.playlist_id', $id); 
        $this->db->where_not_in('a.id', $ids);
        $query = $this->db->get();
       echo $this->db->last_query();
        $data = $query->result();
       //echo "<pre>"; print_r($data); exit;
        return $data;
    }
    
    
    function getPack($id){
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get('playlists');
        return $query->result();
    }
    
    function get_videocount($uid, $data='', $ids){
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('contents.*');
        $this->db->from('contents');
        $this->db->join('categories', 'contents.category = categories.id', 'left');
        $this->db->where_in('contents.uid', $id);
        $this->db->where_not_in('contents.id', $ids);
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('contents.category', $data['category']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where(".created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        return count($query->result());
    }
    function get_allvideo($ids, $uid, $limit, $start, $data){
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('a.*, b.category , c.username, e.name as file,e.minetype');        
        $this->db->from('contents a');
        $this->db->where_in('a.uid', $id); 
        $this->db->where_not_in('a.id', $ids);
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('a.category', $data['category']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $this->db->group_by('a.id');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        
        $data = $query->result();
        return $data;
    }
    function get_ownerid($uid){
        $this->db->select('id');
        $this->db->where('owner_id', $uid);
        $query = $this->db->get('users');
        //return $query->result();
            $data = array();
        $i =1;
        foreach($query->result() as $value){
            //print_r($value);
            $data[$i] =  $value->id;
            $i++;
        }
        return  $data;
    }
    /*  function get_category($uid,$relation = false) {
        $this->db->select('child.id,child.category,child.parent_id,parent.category as parent');
        $this->db->from('categories child');
        $this->db->join('categories parent', 'child.parent_id = parent.id', 'left');
        $this->db->where('child.u_id', $uid);
        $this->db->order_by('child.category', 'asc');
        $query = $this->db->get();
        $result = $query->result();
        
        $category = array();
        if($relation === false){
            foreach($result as $key=>$val){
                $category[$val->id] = ucfirst(strtolower($val->category));
            }
        }else{
            foreach($result as $key=>$val){
                if($val->parent_id > 0){
                    $category[$val->parent][$val->id] = ucfirst(strtolower($val->category));
                }else{
                    $category[$val->id] = ucfirst(strtolower($val->category));
                }
            }
        }
        return $category;
    } */
    function add_video($pid, $cid){
        $data = array('playlist_id'=>$pid, 'content_id'=>$cid, 'created'=>'NOW()','color'=>sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
        $this->db->insert('playlist_video', $data);
    }
    function delete_vid($id){
        $this->db->delete('playlist_video', array('id'=>$id));
        $this->db->delete('playlist_epg', array('content_id'=>$id));
    }
}