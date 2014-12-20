<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Livestream_model extends CI_Model {

   function __construct()
   {
       parent::__construct();
       $this->load->database();
   }
   
   function getStream()
   {    
    $this->db->select('l.thumbnail_url,l.youtube,l.ios,l.android,l.windows,l.web,,u.first_name as channel_name');
    $this->db->from('livestream l');
    $this->db->join('users u','u.id=l.user_id');
    $this->db->where('l.status',1);
    $query = $this->db->get();
    return $query->result();
   }
   
  
}
   ?>