<?php
class Package_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    function get_video($id){
        $this->db->select('a.*, b.category , e.name as file,e.minetype, c.id as vpid');
        $this->db->from('contents a');
        
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('package_video c', 'a.id = c.content_id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        $this->db->group_by('a.id');
        $this->db->where('c.package_id', $id); 
        $query = $this->db->get();
       //echo $this->db->last_query();
        
        $data = $query->result();
        return $data;
    }
    function getPack($id){
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get('package');
        return $query->result();
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
    
    function fetchDuration(){
        $query = $this->db->get('duration');
        return $query->result();
    }
    
    function insertPackage($data){
        $this->db->set('created', 'NOW()', false);
        $this->db->insert('package', $data);
    }
    
    function getPackage($uid){
        $this->db->where('uid', $uid);
        $query = $this->db->get('package');
        return $query->result();
    }
    function changeStatus($data){
        $this->db->where('id', $data['id']);
        $this->db->set('status', $data['status']);
        $this->db->update('package');
    }
    function videoPackage($data){
        //echo '<pre>';print_r($data['content_id']);echo '</pre>';
        $package_id = $data['package_id'];
       // print_r($data); die;
        $count = count($data['content_id']);
        $this->db->where('package_id', $package_id);
        $this->db->delete('package_video');
        for($i = 0; $i < $count; $i++){
                $this->db->set('package_id', $package_id);
                $this->db->set('content_id', $data['content_id'][$i]);
                $this->db->insert('package_video');
        }
    }
    function deletePac($id){
        $this->db->delete('package', array('id'=>$id));

    }
    
    function get_dyration($uid, $pid, $type){
        $query = $this->db->query("SELECT * FROM `duration` d
        left join (select p.duration_id,p.content_id,p.price from price p where p.content_id = $pid AND p.content_type = '$type')
        as p on p.duration_id = d.id
        where d.uid = $uid ORDER BY d.days");
        return $query->result();
        
    }
    function checkprice($content_id, $content_type, $duration_id){
        $this->db->where('content_id', $content_id);
        $this->db->where('duration_id', $duration_id);
        $this->db->where('content_type', $content_type);
        $query = $this->db->get('price');
        return count($query->result());
    }
    function insertprice($data){
        if($data['package_type']=='free'){
            $this->db->delete('price', array('content_id'=>$data['content_id'], 'content_type'=>$data['content_type']));
            //echo $this->db->last_query(); die();
        }
        
        if($data['content_type']=='video'){
                $this->db->set('content_type', $data['package_type']);
                $this->db->where('id', $data['content_id']);
                $this->db->update('contents');
        }
        if($data['content_type']=='event'){
                $this->db->set('type', $data['package_type']);
                $this->db->where('id', $data['content_id']);
                $this->db->update('events');
        }
        
        if($data['content_type']=='package'){
            $this->db->set('package_type', $data['package_type']);
            $this->db->where('id', $data['content_id']);
            $this->db->update('package');
        }
        
        if($data['package_type']=='paid'){
        $content_id = $data['content_id'];
        
        foreach($data['prive'] as $key=>$val){
            $this->db->set('content_type',$data['content_type']);
            $this->db->set('content_id',$content_id);
            $this->db->set('duration_id',$key);
            $this->db->set('price',$val);
            
            if($this->checkprice($content_id, $data['content_type'],  $key)=='0'){
                $this->db->insert('price');
            }else{
                $this->db->where('content_id',$content_id);
                $this->db->where('duration_id',$key);
                $this->db->update('price');
            }
        }
        }
    }
    function getType($id, $type){
        if($type == 'video'){
            $this->db->select('content_type as type');
            $this->db->from('contents');
            $this->db->where('id', $id);
        }
        if($type == 'package'){
            $this->db->select('package_type as type');
            $this->db->from('package');
            $this->db->where('id', $id);
        }
        if($type == 'event'){
            $this->db->select('type as type');
            $this->db->from('events');
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return  $query->result();
    }
    public function Checkemail($data) {
        $name = $data['package_name'];
        $this->db->select('*');
        $this->db->where('name', $name);
        $query = $this->db->get('package');
        return $query->result();
    }
    
    
    function get_category($uid,$relation = false) {
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
    }
    
            
    function get_allvideo($ids, $uid, $limit, $start, $sort = '', $sort_by = '', $data){
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
        $this->db->order_by($sort, $sort_by);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        
        $data = $query->result();
        return $data;
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
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        return count($query->result());
    }
    function add_video($pid, $cid){
        $data = array('package_id'=>$pid, 'content_id'=>$cid);
        $this->db->insert('package_video', $data);
    }
    function delete_vid($id){
        $this->db->delete('package_video', array('id'=>$id));
    }
}