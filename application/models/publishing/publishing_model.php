<?php
ini_set('display_errors', 1);
class Publishing_Model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
       $this->load->database();
    }
    
    function getSkins($search,$count=0,$limit,$page)
    {
        if($count==1){
            $this->db->select('count(a.id) as total');
        }else{
        $this->db->select('a.*,u.skin_id,u.skin_banner,f.relative_path,f.absolute_path');
        }
       
        $this->db->from('skin_templates a');
        $this->db->join('users u','a.id=u.skin_id AND u.id='.$this->uid,'left');
        $this->db->join('files f','u.skin_banner=f.id','left');
        if($count==1){
               $query = $this->db->get();
            $result = $query->row();
            return $result->total;
        }else{
            $this->db->limit($limit,$page);
            $query = $this->db->get();
                return $query->result();
        }
    }
    function fetchskin($id){
        $this->db->select('*');
        $this->db->where(array('id'=>$id));
        $this->db->from('skin_templates');
        $query = $this->db->get();

        $result =  $query->result();
        return $result;
    }
    function _save($data)
    {
        if (isset($data['id'])) {
            $Id = $data['id'];
            $data = array(
                'title' => $data['title'],
                'image' => $data['image'],
                'path' => $data['path'],
                'description' => $data['description'],
                'dimension' => $data['dimension'],
                'status' => $data['status']
            );
            //$this->db->set('modified', 'NOW()', FALSE);
            $this->db->where('id', $Id);
            $this->db->update('skin_templates', $data);
            
        } else {
            
            $data = array(
                'title' => $data['title'],
                'description' => $data['description'],
                'image' => $data['image'],
                'path' => $data['path'],
                'dimension' => $data['dimension'],
                'status' => $data['status']
            );
            $this->db->set($data);
            $this->db->set('created', 'NOW()', FALSE);           
            $this->db->insert('skin_templates');
            $catId = $this->db->insert_id();
        }
    }
    function deleteskin($id){
        
        $this->db->where('id', $id);
        $this->db->delete('skin_templates');
        
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