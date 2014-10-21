<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Splash_model extends CI_Model
{   
    
   function __construct()
   {
       parent::__construct();
       $this->load->database();
   }

   function getdetails($token)
   {
       $this->db->select('d.token,d.image as logo,a.user_id,a.width,a.height,a.dimension_name,b.dimension_id,b.file_id,c.name,c.relative_path as thumbnail_path');       
       $this->db->from('splash_dimension a');
       $this->db->join('splash_dimension_image b','a.id=b.dimension_id','left');
       $this->db->join('files c','c.id=b.file_id','left');
       $this->db->join('users d','a.user_id=d.id','left');
       $this->db->where('a.status',1);
       $this->db->where('d.token',$token);
       $query = $this->db->get();
       //echo $this->db->last_query();
       return $query->result();
   }
}