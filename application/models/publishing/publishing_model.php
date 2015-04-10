<?php
class Publishing_Model extends CI_Model
{
    function __construct()
    {
       parent::__construct();
       $this->load->database();
    }
    
    function getSkins($search,$count=0)
    {
        if($count==1){
            $this->db->select('count(id) as total');
        }else{
        $this->db->select('a.*,u.skin_id');
        }
        $this->db->from('skin_templates a');
        $this->db->join('users u','a.id=u.skin_id AND u.id='.$this->uid,'left');
        $query = $this->db->get();
       // echo $this->db->last_query();
        if($count==1){
            $result = $query->row();
            return $result->total;
        }else{
            return $query->result();
        }
    }
    
    function _save($data)
    {
        if (isset($data['id'])) {
            $Id = $data['id'];
            $data = array(
                'title' => $data['title'],
                'description' => $data['description'],
                'image' => $data['image'],
                'path' => $data['path'],
                'dimension' => $data['dimension'],
                'status' => $data['status']
            );
            $this->db->set('modified', 'NOW()', FALSE);
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
            $this->db->set($catData);
            $this->db->set('created', 'NOW()', FALSE);           
            $this->db->insert('skin_templates');
            $catId = $this->db->insert_id();
        }
    }
}

?>