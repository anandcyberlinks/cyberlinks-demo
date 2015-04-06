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
       $parent=array();
   }
  
   public function categorylist()
   {      
       // $this->db->select('a.id,a.category,a.description,b.relative_path as thumbnail_path');
	$this->db->select('a.id,a.category,GROUP_CONCAT(CONCAT_WS(",",a.id,b.id) SEPARATOR ",") as catids,a.color,c.name as thumbnail',false);
	$this->db->from('categories a');
        $this->db->join('categories b', 'b.parent_id = a.id', 'left');
        $this->db->join('files c','a.file_id=c.id','left'); //-- to be deleted temporary for channels--//
        $this->db->where('a.status','1');
        $this->db->where('a.parent_id','0');
	$this->db->where('a.u_id',$this->owner_id);
        $this->db->group_by('a.id');
        $query = $this->db->get();    
       //echo '<br>'.$this->db->last_query();
	return $query->result();
   }
   
   public function subcategory($id)
   {
       // $this->db->select('a.id,a.category,a.description,b.relative_path as thumbnail_path');
	$this->db->select('a.id,a.category,GROUP_CONCAT(CONCAT_WS(",",a.id,b.id) SEPARATOR ",") as catids',false);
	$this->db->from('categories a');
        $this->db->join('categories b', 'b.parent_id = a.id', 'left');
        $this->db->where('a.status','1');
        $this->db->where('a.parent_id',$id);        
        $query = $this->db->get();    
       //echo '<br>'.$this->db->last_query();die;
	return $query->row();
   }
   /*
   public function recursiveCategory($result)
   {
       foreach($result as $row){
           $this->parent[] = $row->id;
           $this->recursion($row->id);
       }
       
   }
   
    function recursion($id)
    {
        
        $this->db->select('a.id');
	$this->db->from('categories a');      
        $this->db->where('a.status','1');
        $this->db->where('a.parent_id',$id);
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();
	$subcategory = $query->result();
        print_r($subcategory);
        foreach($subcategory as $row){
            $this->parent[] = $this->recursion($row->id);
        }
        echo '<pre>';print_r($this->parent);
    }
    */
    function getLatestImage($ids =array())
    {
        $this->db->select('a.id');
        $this->db->from('contents a');      
        $this->db->join('video_thumbnails b','a.id=b.content_id','inner');
        $this->db->where_in('a.category',$ids);
        $this->db->order_by('a.created desc');
        $this->db->limit('1');
        $query = $this->db->get();
        //echo '<br>'.$this->db->last_query();die;
       $result =  $query->row();
       
       //-- get thumbnail images ---//
       if($result){
            $this->db->select('b.name as image_path');        
            $this->db->from('video_thumbnails a');
            $this->db->join('files b','a.file_id=b.id','left');
            $this->db->where('a.content_id',$result->id);
            $this->db->where('default_thumbnail','1');
            $query = $this->db->get();
            //echo '<br>'.$this->db->last_query();die;
            return  $query->result_array();
       }
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
   public function videolist($id,$device, $param=array())
   {
       if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,d.category,a.id as content_id,a.title,a.description,a.type,a.content_type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration,e.path as video_path');
        }else{
            $this->db->select('count(a.id) as total');
        }
        if($device !=''){
	 $this->db->where('g.flavor_name',$device);
	}
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');
        $this->db->where_in('a.category',$id);        
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('a.id desc');
        $this->db->group_by('a.id');
        $query = $this->db->get();    
        //echo '<pre>'.$this->db->last_query();die;
	return $query->result();
   }
   
   
   public function featuredvideo($device, $param=array(),$category = '')
   {      
      
       if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            $this->db->select('count(a.id) as total');
        }
        
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');
        $this->db->where('a.feature_video','1');
        $this->db->where('g.flavor_name',$device);
	$this->db->where('a.uid',$this->owner_id);
        
	if($category != '')
	$this->db->where('d.id',$category);
	
	$this->db->order_by('a.created desc');
        $this->db->group_by('a.id');
        $query = $this->db->get();    
        //echo '<pre>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function videolatest($data)
   {
     if(@$data['id']>0){
      $this->db->where_in('a.category',$data['id']);
     }
	 $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration,e.path as video_path');
        $this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');
       // $this->db->where_in('a.category',$data['id']);
	$this->db->where('g.flavor_name',$data['device']);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('a.id desc');
        $this->db->group_by('a.id');
	$this->db->limit($data['limit']);
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function allvideo($device,$param=array())
   {
        if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.content_token,a.content_type,a.category as category_id,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            $this->db->select('count(a.id) as total');
        }
        
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');        
        $this->db->where('g.flavor_name',$device);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('a.id desc');
        $this->db->group_by('a.id');
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();
	return $query->result();
   }
   
   public function searchvideo($data,$param=array())
   {
       
       if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            $this->db->select('count(a.id) as total');
        }
        
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->join('genres i','b.genre_id=i.id','left');
        $this->db->where('a.status','1');
        
	if(isset($data['title']) && $data['title']!='') 
	$this->db->like('a.title',$data['title']);
	
	$this->db->where('g.flavor_name',$data['device']);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->group_by('a.id');
                               
                if(isset($data['keywords'])&& $data['keywords']!='')
		{
                    $data['keywords'] = trim($data['keywords']);
                    
                    $this->db->where('(a.title LIKE \'%'.$data['keywords'].'%\' OR a.description LIKE \'%'.$data['keywords'].'%\' OR d.category LIKE \'%'.$data['keywords'].'%\' OR d.description LIKE \'%'.$data['keywords'].'%\' OR i.genre_name LIKE \'%'.$data['keywords'].'%\')', NULL, FALSE);
                   // $this->db->like('a.title',$data['keywords'])->or_like('a.description',$data['keywords'])->or_like('d.category',$data['keywords']);
                   /*
                    $this->db->or_like('a.description',$data['keywords']);
                   $this->db->or_like('d.category',$data['keywords']);
                   $this->db->or_like('d.description',$data['keywords']);
                   $this->db->or_like('i.genre_name',$data['keywords']);
                    */
		}
		if(isset($data['category'])&& $data['category']!='')
		{
                    $this->db->where_in('a.category',$data['category']);
		}
		/*if((isset($data['startdate'])&& $data['startdate']!='') && (isset($data['enddate'])&& $data['enddate']!=''))
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
		}*/
                $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function videodetails($id,$user_id)
   {
        $this->db->select('a.id as content_id,a.content_type,i.id as favorite,a.title,a.description,a2.star_cast,a2.director,a2.music_director,a2.producer,b.views,c1.name as thumbnail_path,d.id as category_id,d.category as category_name,b.genre_id as type_id,b1.genre_name as type,b.duration');
	$this->db->from('contents a');
        $this->db->join('video_detail a2', 'a.id = a2.content_id', 'left');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');
        $this->db->join('genres b1', 'b1.id = b.genre_id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');  
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c1', 'h.file_id = c1.id', 'left');
	$this->db->join('user_favorites i', 'i.content_id = a.id AND i.user_id='.$user_id, 'left');
        $this->db->where('a.status','1');
        $this->db->where('a.id',$id);
	$this->db->where('a.uid',$this->owner_id);
        $query = $this->db->get();
        // echo '<br>'.$this->db->last_query();die;
        return $query->row();
   }
   
   
   public function relatedvideo($arrVal=array(),$param=array())
   {
      //-- get category id --//
	 $this->db->select('category');
	 $this->db->from('contents c');
	 $this->db->where('c.id',$arrVal['id']);
	 $this->db->limit('1');
	 $query = $this->db->get();
	 $result = $query->row();
	 $category_id = $result->category;
      //------------------------//
	
	if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a2.star_cast,a2.director,a2.music_director,a2.producer,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            $this->db->select('count(a.id) as total');
        }
	        
	$this->db->from('contents a');
        $this->db->join('video_detail a2', 'a.id = a2.content_id', 'left');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('genres b1', 'b1.id = b.genre_id', 'left');
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');        
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');        
        $this->db->where('g.flavor_name',$arrVal['device']);
        $this->db->where('a.category',$category_id);
        $this->db->where_not_in('a.id',$arrVal['id']);
	$this->db->where('a.uid',$this->owner_id);
	$this->db->group_by('a.id');
	$this->db->limit('5');
        $query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
	return $query->result();
   }
   
   public function popularvideo($data=array(),$param=array())
   {
      if(@$data['id']>0){
	 $this->db->where_in('a.category',$data['id']);
      }
        if($param){
            $this->db->limit($param['limit'],$param['offset']);
             $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration,e.path as video_path');
        }else{
            $this->db->select('count(a.id) as total');
        }
	$this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration,e.path as video_path');
        $this->db->from('contents a');	
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->where('a.status','1');
       // $this->db->where_in('a.category',$data['id']);
	$this->db->where('g.flavor_name',$data['device']);
	$this->db->where('b.views > ',0);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('b.views desc');        
	$this->db->limit($data['limit']);
	
        $query = $this->db->get();    
        //echo '<br>'.$this->db->last_query();
	return $query->result();
   }
   
   public function getlikesvideo($category_id,$user_id,$device, $param=array())
   {
       if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            $this->db->select('count(a.id) as total');
        }
        
        if($user_id >0){
            $this->db->where('i.user_id',$user_id);
        }
        
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->join('likes i', 'i.content_id = a.id', 'inner');
        $this->db->where('a.status','1');
        $this->db->where('a.category',$category_id);
        $this->db->where('g.flavor_name',$device);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('a.id desc');
        $this->db->group_by('a.id');
        $query = $this->db->get();    
        //echo '<pre>'.$this->db->last_query();die;
	return $query->result();
   }
   
   function favorite_list($data,$param=array())
   {
      if(@$data['id']>0){
	 $this->db->where_in('a.category',$data['id']);
      }
      
       if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.category as category_id,a.content_type,d.category,a.id as content_id,a.title,a.description,a.type,c3.name as thumbnail_path,d.id as category_id,d.category as category_name,b.views as total_view,b.duration');
        }else{
            //$this->db->select('count(a.id) as total');
        }
        
	$this->db->from('contents a');
        $this->db->join('videos b', 'a.id = b.content_id', 'left');        
        $this->db->join('files c1', 'b.file_id = c1.id', 'left');
       // $this->db->join('files c2', 'b.thumb_id = c2.id', 'left');
        $this->db->join('categories d', 'a.category = d.id', 'left');
        //$this->db->join('wowza_video e', 'a.id = e.content_id', 'left');       
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c3', 'h.file_id = c3.id', 'left');
        $this->db->join('user_favorites i', 'i.content_id = a.id', 'inner');
        $this->db->where('a.status','1');
        //$this->db->where('a.category',$data['id']);
        $this->db->where('i.user_id',$data['user_id']);
        $this->db->where('g.flavor_name',$data['device']);
	$this->db->where('a.uid',$this->owner_id);
        $this->db->order_by('a.id desc');
        $this->db->group_by('a.id');
        $query = $this->db->get();    
        //echo '<pre>'.$this->db->last_query();die;
	return $query->result();
   }
   
   public function video_play($id,$device)
   {
        $this->db->select('a.type, a.id as content_id,a.uid as content_provider,c1.name as thumbnail_path,e.path as video_path');
	$this->db->from('contents a');               
        $this->db->join('flavored_video e', 'a.id = e.content_id', 'left');
        $this->db->join('video_flavors f', 'f.id = e.flavor_id', 'left');
        $this->db->join('flavors g', 'f.flavor_id = g.id', 'left');  
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c1', 'h.file_id = c1.id', 'left');               
        $this->db->where('a.status','1');
        $this->db->where('a.id',$id);
        $this->db->where('g.flavor_name',$device);
        $query = $this->db->get();
       //echo '<br>'.$this->db->last_query();die;
        return $query->row();
   }
   
   
   public function channel_play($id)
   {
        $this->db->select('a.id as content_id,a.type,a.name, a.uid as content_provider,b.id as playlist_id,b.url as video_path');
	$this->db->from('channels a');               
        $this->db->join('playlists b', 'a.id = b.channel_id', 'inner');                     
        $this->db->where('b.status','1');
        $this->db->where('b.id',$id);        
        $query = $this->db->get();
      // echo '<br>'.$this->db->last_query();die;
        return $query->row();
   }
   
   public function video_play_youtube($id,$type='youtube')
   {
        $this->db->select('a.type,a.id as content_id,a.uid as content_provider,c1.name as thumbnail_path,f.relative_path as video_path');
	$this->db->from('contents a');               
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail=1','left');
        $this->db->join('files c1', 'h.file_id = c1.id', 'left');
	$this->db->join('videos v','v.content_id=a.id','inner');
	$this->db->join('files f','f.id=v.file_id');
        $this->db->where('a.status','1');
        $this->db->where('a.id',$id);
       $this->db->where('a.type',$type);
        $query = $this->db->get();
       //echo '<br>'.$this->db->last_query();die;
        return $query->row();
   }
   
   public function livestream_play($id,$device)
   {
      $this->db->select('ch.id as content_id,ch.uid as content_provider,ch.name,l.thumbnail_url as thumbnail_path, l.'.$device.' as video_path');
      $this->db->from('channels ch');
      $this->db->join('livestream l','ch.id=l.channel_id');
      $this->db->where('l.status','1');
     // $this->db->where('ch.status','1');
      $this->db->where('ch.id',$id);
      $this->db->limit(1);
      $query = $this->db->get();
     // echo '<br>'.$this->db->last_query();die;
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
   
   public function update_video($id,$data){       
       $this->db->where('id', $id);
        $this->db->update('videos', $data);
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
   
   function check_content($id){
      $this->db->where('id', $id);
      $query = $this->db->get('contents');
      $data = count($query->result());
      if($data == '0'){
	 return false; 
      }else{
	 return true;
      }
   }
   function check_like($data){
      $this->db->where('content_id', $data['content_id']);
      $this->db->where('user_id', $data['user_id']);
      $query = $this->db->get('likes');
      $data = count($query->result());
      if($data == '0'){
	 return false; 
      }else{
	 return true;
      }
   }
   
   function check_favorite($data){
      $this->db->where('content_id', $data['content_id']);
      $this->db->where('user_id', $data['user_id']);
      $query = $this->db->get('user_favorites');
      $data = count($query->result());
      if($data == '0'){
	 return false; 
      }else{
	 return true;
      }
   }
   
   function add_favorite($data){
      $value['content_id'] = $data['content_id'];
      $value['user_id'] = $data['user_id'];
      $this->db->set($value);
      $this->db->set('created','NOW()',FALSE);
      $this->db->insert('user_favorites',$value);
   }
   
   function delete_favorite($data){
      $this->db->where('content_id', $data['content_id']);
      $this->db->where('user_id', $data['user_id']);
      $this->db->delete('user_favorites');
   }
   
   function add_like($data){
      $value['content_id'] = $data['content_id'];
      $value['user_id'] = $data['user_id'];
      $this->db->set($value);
      $this->db->set('created','NOW()',FALSE);
      $this->db->insert('likes',$value);
   }
   function like_count($content_id){
      $this->db->where('content_id', $content_id);
      $query = $this->db->get('likes');
      return count($query->result());
   }
   function delete_like($data){
      $this->db->where('content_id', $data['content_id']);
      $this->db->where('user_id', $data['user_id']);
      $this->db->delete('likes');
   }
   
   function check_user($user_id){
      $this->db->where('id', $user_id);
      $query = $this->db->get('customers');
      //echo $this->db->last_query();
      return count($query->result());
   }
   
   ### Comments ####
   function add_comment($data){
      $value['content_id'] = $data['cid'];
      $value['user_id'] = $data['uid'];
      $value['comment'] = $data['comment'];    
      $this->db->set('created_date','NOW()',FALSE);
      $this->db->insert('comment',$value);
   }
   #### end Comments ###3
   
   /***** function used for rating section starts ******/

	function insertRating($data)
	{
        $this->db->set($data);
        $this->db->set('created','NOW()',FALSE);
		$this->db->insert('video_rating', $data);
		$avgRating = $this->getAverageRating($data['content_id']);
		return $avgRating;
	}
	
	function getAverageRating($content_id)
	{
		$this->db->select('SUM(rating)/count(id) as average');
		$this->db->from('video_rating');
		$this->db->where('content_id',$content_id);
		$query = $this->db->get();                 
		$result = $query->result();
		return round($result['0']->average);
	}
	
	function checkRating($content_id, $uid)
	{
		$this->db->select('id');
		$this->db->from('video_rating');
		$this->db->where(array('content_id' => $content_id, 'uid' => $uid));
		$query = $this->db->get(); 
		$data = count($query->result());
		if($data == '0'){
			return false; 
		} else {
			return true;
		}

	}
        
        /***** function used for rating section ends ******/
   
    /***** function used for video view section starts ******/
	
	function updateView($content_id)
	{
	       $this->db->set('views', 'views+1', FALSE);
               $this->db->where('content_id', $content_id);
               $this->db->update('videos');
	       $result = $this->getview($content_id);              
                return $result->views;
	}
	
	function getview($content_id){
	 $this->db->select('views');
	  $this->db->where('content_id', $content_id);
	 $this->db->from('videos');
         $query = $this->db->get(); 
         $result = $query->row();
	 return $result;
	}
        
        #### function for get comments ####
      function comments($cid,$param=array()){
        if($param){
            $this->db->limit($param['limit'],$param['offset']);
            $this->db->select('a.user_id,a.id as comment_id,a.comment,a.created_date as comment_date, b.first_name as name,b.image');
        }else{
            $this->db->select('count(a.id) as total');
        }
	 
	 $this->db->from('comment a');
	 $this->db->join('customers b', 'a.user_id=b.id');
	 $this->db->where('a.content_id', $cid);
	 $this->db->order_by('a.id', 'DESC');
	 $query = $this->db->get();         
         //echo $this->db->last_query();
	 return $query->result(); 
      }
      
    function get_allChannels(){
        $query = $this->db->get('channels');
        if($query->num_rows() > 0)        {
            $data = $query->result_array();
        }else{
            $data = null;
        }
        return $data;
    }
    
    function deleteChannelEpg($channel_id){
        $sql = "DELETE FROM livechannel_epg WHERE channel_id = ".$channel_id;
        $this->db->query($sql);
    }
    
    function insertLiveChannelEpg($values){
        $sql = "INSERT INTO livechannel_epg (channel_id, channel_name, date,show_title,show_time,show_thumb,show_language,show_description,show_type    )
                            VALUES ".$values;
        $this->db->query($sql);
    }
}
