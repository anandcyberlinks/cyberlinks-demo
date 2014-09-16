<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Video_model extends CI_Model {

   function __construct()
   {
       parent::__construct();	
       $this->load->database();
   }
  
   public function categorylist()
   {
       // $this->db->select('a.id,a.category,a.description,b.relative_path as thumbnail_path');
	   $this->db->select('a.id,a.category,a.description');
	$this->db->from('categories a');
       // $this->db->join('files b', 'a.file_id = b.id', 'left');
        $this->db->where('a.status','1');
        $query = $this->db->get();    
       // echo '<br>'.$this->db->last_query();
	return $query->result();
   }
   /*
   public function videolist($id)
   {
        $this->db->select('a.id as content_id,a.title,a.description,a.type,c1.name as video,c1.absolute_path as video_path,c1.minetype as video_mimetype,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        $this->db->where('a.status','1');
        $this->db->where('a.category',$id);
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   */
   public function videolist($id,$device)
   {
        $this->db->select('e.id,a.id as content_id,a.title,a.description,a.type,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc,e.path as video_path,g.flavor_name');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        //$this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        $this->db->join('wowza_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->where('a.status','1');
        $this->db->where('a.category',$id);
        $this->db->where('e.device_type',$device);
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   /*
   public function videolatest()
   {
        $this->db->select('a.id as content_id,a.title,a.description,a.type,c1.name as video,c1.absolute_path as video_path,c1.minetype as video_mimetype,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        $this->db->where('a.status','1');
        $this->db->order_by('a.created desc');
        $this->db->limit('10');
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }*/
   
   public function videolatest($device)
   {
        $this->db->select('a.id as content_id,a.title,a.description,a.type,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc,e.path as video_path,e.device_type,g.flavor_name');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
      //  $this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        $this->db->join('wowza_video e', 'a.id = e.content_id', 'left');
         $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->where('a.status','1');        
        $this->db->where('e.device_type',$device);
        $this->db->order_by('a.created desc');
        $this->db->limit('10');
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function searchvideo($data)
   {
        $this->db->select('a.id as content_id,a.title,a.description,a.type,a.created as created_date,c1.name as video,c1.absolute_path as video_path,c1.minetype as video_mimetype,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
                if(isset($data['title'])&& $data['title']!='')
		{
                    $data['title'] = trim($data['title']);
                    $this->db->like('a.title',$data['title']);
		}
		if(isset($data['category'])&& $data['category']!='')
		{
                    $this->db->where('a.category',$data['category']);
		}
		if((isset($data['startdate'])&& $data['startdate']!='') && (isset($data['enddate'])&& $data['enddate']!=''))
		{
                    $this->db->where('date_format(a.created,"%Y-%m-%d") >=',$data['startdate']);
                    $this->db->where('date_format(a.created,"%Y-%m-%d") <=',$data['enddate']);
		}
		else
		{
                    if(isset($data['startdate'])&& $data['startdate']!='')
                    {
                        $this->db->where('date_format(a.created,"%Y-%m-%d")',$data['startdate']);
                    }
                    if(isset($data['enddate'])&& $data['enddate']!='')
                    {
			$this->db->where('date_format(a.created,"%Y-%m-%d")',$data['enddate']);
                    }
		}
                $query = $this->db->get();    
       // echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function videodetails($id)
   {
        $this->db->select('a.id as content_id,a.title,a.description,a.type,c1.name as video,c1.absolute_path as video_path,c1.minetype as video_mimetype,c2.name as thumbnail,c2.relative_path as thumbnail_path,c2.minetype as thumb_mimetype,d.id as category_id,d.category as category_name,d.description as category_desc');
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
        $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
         $this->db->where('a.status','1');
        $this->db->where('a.id',$id);
        $query = $this->db->get(); 
         //echo '<br>'.$this->db->last_query();die;
        return $query->row();
   }
   
   public function video_flavors()
   {
       $this->db->select('a.id as map_id,a.flavor_id,d.flavor_name,a.content_id,c.name,c.minetype as content_type,c.absolute_path as video_file_name');
       $this->db->from('video_flavors a');      
       $this->db->join('videos b','a.content_id=b.content_id','inner');
       $this->db->join('files c','b.file_id=c.id','inner');
       $this->db->join('flavors d','a.flavor_id=d.id','inner');
       $this->db->where('a.status','pending');
       $query = $this->db->get(); 
        //echo '<br>'.$this->db->last_query();die;
        return $query->result();
   }
   
    public function update_video_flavors($id,$data){       
       $this->db->where('id', $id);
        $this->db->update('video_flavors', $data);
        return true;
   }
   
   public function save_flavored_video($data)
   {
       $this->db->set($data);
       $this->db->set('created','NOW()',FALSE);
       $this->db->insert('flavored_video',$data);
       return $this->db->insert_id();
   }
   
   public function save_wowza_video($data)
   {
       $this->db->set($data);
       $this->db->set('created','NOW()',FALSE);
       $this->db->insert('wowza_video',$data);
       return $this->db->insert_id();
   }
}