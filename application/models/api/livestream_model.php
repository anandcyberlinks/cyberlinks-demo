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
   
   function getCategory()
   {
         $this->db->select('id,category');
         $this->db->from('channel_categories');
         $this->db->where('status',1);
         //$this->db->where('u_id',$this->owner_id);
         $query = $this->db->get();
         return $query->result();
   }
   
   function getStream($id)
   {
    if($id){
      $this->db->where('c.category_id',$id);
    }
    $this->db->select('c.id,l.thumbnail_url,l.youtube,l.ios,l.android,l.windows,l.web,c.name as channel_name');
    $this->db->from('livestream l');
    $this->db->join('channels c','c.id=l.channel_id');
    $this->db->where('c.status',1);
    
   //  $this->db->where('c.uid',$this->owner_id);
    $query = $this->db->get();
   // echo $this->db->last_query();
    return $query->result();
   }
   
  function getPlayList($id)
  {
   if($id){
      $this->db->where('c.category_id',$id);
    }
   $this->db->select('c.id,p.url as ios,p.url as android, p.url as windows,p.url as web,c.name as channel_name');
   $this->db->from('channels c');
   $this->db->join('playlists p','p.channel_id=c.id');
   $this->db->where('p.status','1');
   $this->db->where('c.status',1);
   $this->db->where('c.type','Loop');
   $this->db->where('CURDATE() BETWEEN DATE_FORMAT( p.start_date, "%Y-%m-%d" ) AND DATE_FORMAT( p.end_date, "%Y-%m-%d" )');
   $query = $this->db->get();
   //  echo $this->db->last_query();
   return $query->result();
  }
  
  function getEPG($id)
  {   
   $this->db->select('co.id, co.title, c.name, DATE_FORMAT(pe.start_date,"%H:%m") as start_time, DATE_FORMAT(pe.end_date,"%H:%m") as end_time',false);
   $this->db->from('channels c');
   $this->db->join('playlists p','p.channel_id=c.id');
   $this->db->join('playlist_video pv','pv.playlist_id = p.id');
   $this->db->join('contents co','co.id = pv.content_id');
   $this->db->join('playlist_epg pe','pe.playlist_id = p.id AND pe.content_id = pv.content_id');
   $this->db->where('c.id',$id);
   $this->db->where('pe.start_date BETWEEN if(MINUTE(CURTIME()) < 30, DATE_FORMAT(NOW(),"%Y-%m-%d %H:00:00"), DATE_FORMAT(NOW(),"%Y-%m-%d %H:30:00")) AND DATE_ADD(now(), INTERVAL 2 HOUR)');
  //  $this->db->where('c.uid',$this->owner_id);
   $query = $this->db->get();
  // echo '<br>'.$this->db->last_query();
//  print_r($query->result());
  return $query->result();  
  }
}
   ?>