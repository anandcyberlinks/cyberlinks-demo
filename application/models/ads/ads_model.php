<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Ads_model extends CI_Model {

   function __construct()
   {
       parent::__construct();
	   $this->load->helper('url');
   }
   
    public function getContent($id)
    {
        $this->db->select('a.location_sensor,b.file_id,a.id as contentid,a.title,c.*');
	$this->db->from('contents a');
	$this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('files c', 'b.file_id = c.id', 'inner');        
      //  $this->db->join('contents_ads c', 'a.id = c.content_id', 'left');
       // $this->db->join('vast d', 'a.id = d.content_id', 'left');
       // $this->db->join('files e ', 'e.id = c.file_id', 'left');
	$this->db->where('a.status','1');
        $this->db->where('a.id',$id);
        $this->db->group_by('a.id');
	$query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
	return $query->row();
    }
    
    public function getContentAds($id)
    {
        $this->db->select('c.* ,a.id as adsid,a.ad_type,ad_title,ad_desc,b.id as ads_content_id,b.*');
	$this->db->from('ads a');
        $this->db->join('contents_ads b', 'a.id = b.ads_id AND b.content_id='.$id, 'left');
        $this->db->join('files c', 'a.file_id = c.id', 'left');
	$this->db->where('a.status','1');
        //$this->db->where('a.id',$id);
	$query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
	return $query->result();
    }
    
    public function getAdsEdit($id)
    {
        $this->db->select('a.id as adsid,a.*');
	$this->db->from('contents_ads a');       
	$this->db->where('a.status','1');
        $this->db->where('a.content_id',$id);
	$query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
	return $query->result();
    }
    
    public function getContentAdsForLocation($id)
    {
        $this->db->select('((a.offset_hrs*3600)+(a.offset_minutes*60)+a.offset_seconds) as offset,a.id as ads_content_id,a.*');	
        $this->db->from('contents_ads a');
	$this->db->where('a.status','1');
        $this->db->where('a.content_id',$id);
	$query = $this->db->get();
       // echo $this->db->last_query();
	return $query->result();
    }
    
    public function getCountContentAds($id)
    {
        $this->db->select('count(a.id) as tot');	
        $this->db->from('contents_ads a');
	$this->db->where('a.status','1');
        $this->db->where('a.content_id',$id);
        //$this->db->limit(1);
	$query = $this->db->get();
        //echo $this->db->last_query();
	return $query->row();
    }
    
    function getAdsGenerateVast($id)
    { 
        $this->db->select('c.* ,a.id as adsid,a.ad_type,ad_title,ad_desc,b.id as ads_content_id,b.id as ads_content_id,b.*');
	$this->db->from('ads a');
        $this->db->join('contents_ads b', 'a.id = b.ads_id', 'left');
        $this->db->join('files c', 'a.file_id = c.id', 'left');
	$this->db->where('a.status','1');
        $this->db->where('b.id >','0');
        
       // $this->db->where('a.id',$id);
	$query = $this->db->get();
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
    }
    
  function getContentLocationSensorOffset($lat,$long,$limit){
      $this->db->select('c.*,a.ads_id,ROUND(((ACOS(SIN('.$lat.' * PI() / 180) * SIN(a.latitude * PI() / 180) + COS('.$lat.' * PI() / 180) * COS(a.latitude * PI() / 180) * COS(('.$long.' - a.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)) AS distance');     
      $this->db->from('ads_locations a');
      $this->db->join('vast b','a.ads_id=b.ads_id','inner');
      $this->db->join('files c','b.file_id=c.id','inner');
      //$this->db->where('a.content_id',$id);
      $this->db->having('ROUND(distance) <= ' ,3);
      $this->db->order_by('distance');
      $this->db->limit($limit);
      $query = $this->db->get();
      //echo '<br>'.$this->db->last_query();
      return $query->result();
  }
    function getAdsScheduleBreaks($id=0)
    { 
        $this->db->select('c.* ,((a.offset_hrs*3600)+(a.offset_minutes*60)+a.offset_seconds) as offset,a.*');
	$this->db->from('contents_ads a');
        $this->db->join('vast b', 'a.ads_id = b.ads_id', 'left');
        $this->db->join('files c', 'b.file_id = c.id OR a.file_id=c.id', 'left');
	$this->db->where('a.status','1');    
        $this->db->where('a.content_id',$id); 
        $this->db->group_by('a.id');
        
       // $this->db->where('a.id',$id);
	$query = $this->db->get();
       //echo '<br>'.$this->db->last_query();die;
	return $query->result();
        
    }
    
    public function getPlayerSettings()
    {
        $this->db->select('a.*');
	$this->db->from('jwplayer_setting a');
	$this->db->where('a.status','1');
	$query = $this->db->get();
	return $query->result();
    }
    
    public function getVastFile()
    {
         $this->db->select('a.*');
	$this->db->from('vast a');
	$this->db->where('a.status','1');
	$query = $this->db->get();
	return $query->row();
    }
        
   function save_player_settings($data,$key)
   {
		$this->db->where('setting_key', $key);
		$this->db->update('jwplayer_setting', $data); 
		//echo '<br>'.$this->db->last_query();
		return true; 
   } 

   function save_file($data)
   {
	$this->db->set($data);
	$this->db->set('created','NOW()',false);
	$this->db->insert('files', $data); 
	//echo '<br>'.$this->db->last_query();die;
	return $this->db->insert_id(); 
   }
   function save_vast($data)
   {
	$this->db->set($data);
	$this->db->insert('vast', $data); 
	//echo '<br>'.$this->db->last_query();die;
	return $this->db->insert_id(); 
   }
   
   function update_vast($data,$id)
   {
        $this->db->where('id', $id);
        $this->db->update('vast', $data); 
        return 1;
   }
   
   function save_content_ads($data)
   {
	//$this->db->where('setting_key', $key);
	$this->db->insert('contents_ads', $data); 
	//echo '<br>'.$this->db->last_query();
        return $this->db->insert_id();	
   } 
   
  function update_content_ads($data,$id)
  {
    $this->db->where('id', $id);
    $this->db->update('contents_ads', $data); 
    return true;
  }
  
  function update_content($data,$id)
  {
      $this->db->where('id',$id);
      $this->db->update('contents',$data);
  }
  
  function delete_content_ads($id)
  {echo 'delete';
    $this->db->where('content_id', $id);
    $this->db->delete('contents_ads');
    return true;
  }
  
  function delete_vast($id)
  {
    $this->db->where('content_id', $id);
    $this->db->delete('vast');
    return true;
  }
  
  function save_ads($data)
  {
      $this->db->set('created','NOW()',false);
      $this->db->insert('ads', $data); 
	//echo '<br>'.$this->db->last_query();
        return $this->db->insert_id();	
  }
}
