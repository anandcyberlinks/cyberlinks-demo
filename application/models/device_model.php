<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Device_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getDimensions($id)
    {        
        $this->db->select('a.user_id,a.width,a.height,a.dimension_name,b.file_id,b.dimension_id');
        $this->db->from('splash_dimension a');
        $this->db->join('splash_dimension_image b','a.id=b.dimension_id','left');
        $this->db->where('a.user_id',$id);
        $this->db->where('a.status',1);
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->result();
    }
    
    function checkSplash($id)
    {
        $this->db->select('a.user_id,a.width,a.height,a.dimension_name,b.file_id,b.dimension_id,c.relative_path,c.absolute_path,d.id as splash_id');
        $this->db->from('splash_dimension a');
        $this->db->join('splash_dimension_image b','a.id=b.dimension_id','left');       
        $this->db->join('files c','b.file_id=c.id','left');
        $this->db->join('splash_screen d','b.splash_id=d.id','left');
        $this->db->where('a.user_id',$id);
        $query = $this->db->get();
       //echo $this->db->last_query();
        return $query->result();
    }
    
    function insert_splash_screen($data)
    {
        $this->db->set('created','NOW()',FALSE);
       $id = $this->db->insert('splash_screen',$data);
        return $this->db->insert_id();;
    }
    
    function insert_splash_dimension($data)
    {
       $this->db->set('created','NOW()',FALSE);
       $id = $this->db->insert('splash_dimension',$data);
        return $this->db->insert_id();;
    }
    
    function insert_splash_dimension_image($data)
    {
        $this->db->set('created','NOW()',FALSE);
        $id = $this->db->insert('splash_dimension_image',$data);
        return $this->db->insert_id();
    }
    
    function update_splash_screen($data,$id)
    {
        $this->db->where(array('user_id' => $id));
        $id = $this->db->update('splash_screen', $data);
        
    }
    function delete_splash_screen($id)
    {
        $this->db->delete('splash_screen', array('user_id'=>$id));
        return true;
    }
    
    function delete_splash_dimension($id)
    {
        $this->db->delete('splash_dimension', array('user_id'=>$id));
        return true;
    }
    
    function delete_splash_dimension_image($id)
    {
        $this->db->delete('splash_dimension_image', array('dimension_id'=>$id));
        return true;
    }
    
    function delete_file($id){
     $this->db->delete('files', array('id' => $id));
     return true;
    }
}