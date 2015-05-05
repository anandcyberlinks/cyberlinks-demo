<?php
ini_set('display_errors', 1);
class Help_Model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
       $this->load->database();
    }
    
    function getpages($search,$count=0,$limit,$page)
    {
    
        if($count==1){
            $this->db->select('count(a.id) as total');
        }else{
        $this->db->select('a.*');
        }
        $this->db->where(array('user_id'=>$search['user_id']));
        $this->db->from('pages a');
        
        if($count==1){
            $query = $this->db->get();
            $this->db->order_by($search['sort'], $search['sortby']);
            $result = $query->row();
            return $result->total;
        }else{
            $this->db->limit($limit,$page);
            $this->db->order_by($search['sort'], $search['sortby']);
            $query = $this->db->get();
            return $query->result();
        }
    }
    function fetchpage($id){
        $this->db->select('*');
        $this->db->where(array('id'=>$id));
        $this->db->from('pages');
        $query = $this->db->get();
        $result =  $query->result();
        return $result;
    }
    function _save($data)
    {
        if (isset($data['id'])) {
            $Id = $data['id'];
            unset($data['_method']);
            unset($data['data']);
            unset($data['submit']);
            $this->db->where('id', $Id);
            $catId=$Id;
            $this->db->update('pages', $data);
            
        } else {
            
            unset($data['_method']);
            unset($data['data']);
            unset($data['submit']);
            
            $this->db->set($data);
            $this->db->set('created', 'NOW()', FALSE);           
            $this->db->insert('pages');
            $catId = $this->db->insert_id();
        }
        return $catId;
    }
    function deletepage($id){
        
        $this->db->where('id', $id);
        $this->db->delete('pages');
        
    }
     function savefile($file){
        if(isset($file['id'])&&(($file['id']!=''))){
            $this->db->where('id', $file['id']);
            $fid=$file['id'];
            //echopre($file);
            unset($file['id']);
           // echopre($file);
            $this->db->update('files', $file);
        }else{
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $fid = $this->db->insert_id();
        }
            return $fid;
    }
    function selectfile($id){
        $this->db->select('*');
        $this->db->where(array('id'=>$id));
        $this->db->from('files');
        $query = $this->db->get();
        $result =  $query->result();
        return $result;
    }
}

?>