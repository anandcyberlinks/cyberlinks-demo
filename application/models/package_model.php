<?php
class Package_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    function get_video($uid){
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('a.*, b.category , c.username, e.name as file,e.minetype');
        $this->db->from('contents a');
        $this->db->where_in('a.uid', $id); 
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        $this->db->group_by('a.id');
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
    
    function get_dyration($uid, $pid){
        $this->db->select('duration.*, price.price');
        $this->db->from('duration');
        $this->db->join('price', 'duration.id=price.duration_id', 'left');
        $this->db->where('duration.uid', $uid);
        
        $this->db->where('price.content_id', $pid);
        $this->db->where('duration.status', '1');
        $query = $this->db->get();
        if(count($query->result())!='0'){
            return $query->result();
        }else{
           $this->db->where('duration.status', '1');
           $query = $this->db->get('duration');
           return $query->result();
           
        }
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
}