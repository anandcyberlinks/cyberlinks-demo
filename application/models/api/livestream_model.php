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
    $this->db->select('l.thumbnail_url,l.youtube,l.ios,l.android,l.windows,l.web,c.name as channel_name');
    $this->db->from('livestream l');
    $this->db->join('channels c','c.id=l.channel_id');
    $this->db->where('l.status',1);
    $query = $this->db->get();
    return $query->result();
   }
   
  function getPlayList()
  {
   $this->db->select('p.url as ios,p.url as android, p.url as windows,p.url as web,c.name as channel_name');
   $this->db->from('channels c');
   $this->db->join('playlists p','p.channel_id=c.id');
   $this->db->where('p.status','1');
   $this->db->where('c.status',1);
   $this->db->where('c.type','Loop');
   $query = $this->db->get();
     //echo $this->db->last_query();
   return $query->result();
  }
}
   ?>