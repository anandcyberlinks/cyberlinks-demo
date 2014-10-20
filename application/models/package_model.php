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
        $this->db->select('duration.*, package_price.price');
        $this->db->from('duration');
        $this->db->join('package_price', 'duration.id=package_price.duration_id', 'left');
        $this->db->where('duration.uid', $uid);
        $this->db->where('package_price.package_id', $pid);
        $query = $this->db->get();
        if(count($query->result())!='0'){
            return $query->result();
        }else{
           $query = $this->db->get('duration');
           return $query->result();
           
        }
    }
    function checkprice($package_id, $duration_id){
        $this->db->where('package_id', $package_id);
        $this->db->where('duration_id', $duration_id);
        $query = $this->db->get('package_price');
        return count($query->result());
    }
    function insertprice($data){
        $package_id = $data['package_id'];
        foreach($data['prive'] as $key=>$val){
            $this->db->set('package_id',$package_id);
            $this->db->set('duration_id',$key);
            $this->db->set('price',$val);
            
            if($this->checkprice($package_id, $key)=='0'){
                $this->db->insert('package_price');
            }else{
                $this->db->where('package_id',$package_id);
                $this->db->where('duration_id',$key);
                $this->db->update('package_price');
            }
        }
    }
    
    public function Checkemail($data) {
        $name = $data['package_name'];
        $this->db->select('*');
        $this->db->where('name', $name);
        $query = $this->db->get('package');
        return $query->result();
    }
}