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
   
   function getStream($device)
   {
    if($device != ''){
       $this->db->select($device.' as url');
    }
    $this->db->select('id,channel_name,thumbnail_url');
    $this->db->from('livestream');
    $this->db->where('status',1);
    $query = $this->db->get();
    return $query->result();
   }
}
   ?>