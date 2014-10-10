<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Cms_model extends CI_Model
{   
    
   function __construct()
   {
       parent::__construct();
       $this->load->database();
   }
   
   function getlist()
   {
       $this->db->select('id,page_title,page_description,created');       
       $this->db->from('pages');
       $this->db->where('status',1);
       $query = $this->db->get();
       return $query->result();
   }
   
   function getdetails($id)
   {
       $this->db->select('id,page_title,page_description,created');       
       $this->db->from('pages');
      // $this->db->where('status',1);
       $this->db->where('page_link',$id);
       //$this->db->where('id',$id);
       $query = $this->db->get();
       //echo $this->db->last_query();
       return $query->result();
   }
}