<?php

class Ads_model extends CI_Model{
    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    function getAds()
    {
        $this->db->select('a.id, a.ad_title as title,f.relative_path as url');
        $this->db->from('ads a');
        $this->db->join('files f','a.file_id=f.id');
        $this->db->where('a.status',1);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getAdsFlavour()
    {     
       $this->db->select('a.id,c.name,c.minetype as content_type,c.relative_path as video_file_name');
       $this->db->from('ads a');   
       $this->db->join('files c','a.file_id=c.id','inner');      
       $this->db->where('a.transcode_status','pending');      
       $query = $this->db->get(); 
        //echo '<br>'.$this->db->last_query();die;
        return $query->result();   
    }
    
    function getTranscodedAds($id)
    {
        $this->db->select('a.ad_title,a.duration,fv.ads_id,fv.type,fv.path');
        $this->db->from('ads a');
        $this->db->join('ads_flavored_video fv','fv.ads_id=a.id');
       // $this->db->where('f.status','completed');
        $this->db->where('a.id',$id);
        $this->db->where('a.vast_file_id',0);
        $query = $this->db->get();        
        return $query->result();
    }
    
    public function update_ads_flavors($id,$data){
        $this->db->where('id', $id);
        $this->db->update('ads', $data);
        return true;
   }
   
    public function save_flavored_ads($data)
    {
       $this->db->set($data);
       $this->db->set('created','NOW()',FALSE);
       $this->db->insert('ads_flavored_video',$data);
       return $this->db->insert_id();
    }
    
    function update_ads($data,$id)
   {
    $this->db->where('id', $id);
    $this->db->update('ads', $data); 
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
   
   function getReviveAds()
    {
        $this->db->select('a.id as ads_id, a.ad_title as title,af.path');
        $this->db->from('ads a');
        $this->db->join('ads_flavored_video af','a.id=af.ads_id');
        $this->db->where('af.type','desktop');
        $query = $this->db->get();
        return $query->result();
    }
}
    ?>