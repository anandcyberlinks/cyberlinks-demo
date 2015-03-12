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
   
   function get_videocount($uid, $data=''){      
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('ads.*');
        $this->db->from('ads');
        $this->db->join('categories', 'ads.category = categories.id', 'left');
        $this->db->where_in('ads.uid', $id);
        if (isset($data['title']) && $data['title'] != '') {
            $this->db->like('ad_title', trim($data['title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('ads.category', $data['category']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("ads.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("ads.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("ads.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        return count($query->result());
    }
   
   function get_video($uid, $limit, $start, $sort = '', $sort_by = '', $data) {
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('a.*, b.category , c.username, e.name as file,e.minetype');        
        $this->db->from('ads a');
        $this->db->where_in('a.uid', $id); 
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');        
        $this->db->join('files e', 'a.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        if (isset($data['title']) && $data['title'] != '') {
            $this->db->like('ad_title', trim($data['title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('a.category', $data['category']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $this->db->group_by('a.id');
        $this->db->order_by($sort, $sort_by);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();
        
        $data = $query->result();
        return $data;
    }
    
    function get_category($uid,$relation = false) {
        $this->db->select('child.id,child.category,child.parent_id,parent.category as parent');
        $this->db->from('categories child');
        $this->db->join('categories parent', 'child.parent_id = parent.id', 'left');
        $this->db->where('child.u_id', $uid);
        $this->db->order_by('child.category', 'asc');
        $query = $this->db->get();
        $result = $query->result();
        
        $category = array();
        if($relation === false){
            foreach($result as $key=>$val){
                $category[$val->id] = ucfirst(strtolower($val->category));
            }
        }else{
            foreach($result as $key=>$val){
                if($val->parent_id > 0){
                    $category[$val->parent][$val->id] = ucfirst(strtolower($val->category));
                }else{
                    $category[$val->id] = ucfirst(strtolower($val->category));
                }
            }
        }
        return $category;
    }
    
    function get_ownerid($uid){
        $this->db->select('id');
        $this->db->where('owner_id', $uid);
        $query = $this->db->get('users');
        //return $query->result();
            $data = array();
        $i =1;
        foreach($query->result() as $value){
            //print_r($value);
            $data[$i] =  $value->id;
            $i++;
        }
        return  $data;
        
    }
    
     /**
     *Function for Save and update video created by arshad
     *$data = array();
     * $data['title'] ='mandatory' for insert and update query
     * $data['uid'] ='mandatory' for insert and update query
     * $data['description'] ='optional'
     * $data['fname'] = 'filename' mandatory for insert query
     * $data['type'] = 'filetype' mandatory for insert query
     * $data['minetype'], $data['relative_path'], $data['absolute_path'], $data['info']   = mandatory for insert query
     * for update table you should be send $data['id'] = 'content_id'
     * $data['category'] = 'category_id' mandatory for update
     * $data['feature_video'] = '0 or 1' mandatory for update
     * $data['status'] = '0 or 1' mandatory for update
     * $data['star_cast'], $data['director'], $data['music_director'], $data['producer']  = 'optional' for update
     */
    
    function _saveVideo($data){

        $contents['ad_title'] = $data['content_title'];
        if(isset($data['description'])){
            $contents['ad_desc'] = $data['description'];
            $contents['status'] = $data['status'];
        }
        
        if(isset($data['age_group_from'])){
            $contents['age_group_from'] = $data['age_group_from'];
        }
        if(isset($data['age_group_to'])){
            $contents['age_group_to'] = $data['age_group_to'];
        }
	                   
        if(isset($data['content_id'])){
            $cid = $data['content_id'];
            $contents['category'] = $data['content_category'];
                       
            $this->db->where('id', $cid);
            $this->db->set($contents);
            $this->db->update('ads');
            
        }else{
	    ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $fid = $this->db->insert_id();
	    
            ###inserting data in contents table and return id###
            $contents['uid'] = $data['uid'];
            if(isset($data['category'])){
                $contents['category'] = $data['category'];
            }
	    $contents['file_id'] = $fid;
            $this->db->set($contents);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('ads');
            $cid = $this->db->insert_id();
            //$this->db->last_query();
	    /*
	     ###transcoding video ###
           
                $this->db->select('*');
                $this->db->from('flavors');
                $query = $this->db->get();
                $flav = $query->result();
                foreach($flav as $flavorVal){
                    $videoFlavorsData['flavor_id'] = $flavorVal->id;
                    $videoFlavorsData['ads_id'] = $cid;
                    $videoFlavorsData['file_id'] = $fid;
                    $videoFlavorsData['status'] = 'pending';
                    //$videoFlavorsData['created'] = date('Y-m-d');
                    $this->db->set('created','NOW()',FALSE);
                    $this->db->insert('ads_flavors', $videoFlavorsData);
                }
            */
        }
        return $cid;
    }
    ###saveVideo() function end #####
    
    function edit_profile($id) {
        $this->db->select('a.*, b.id , c.username, g.name as file');
        $this->db->from('ads a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');      
        $this->db->join('files g', 'a.file_id = g.id');
        //$this->db->join('video_detail h', 'h.content_id = a.id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return reset($query->result());
    }
    
    function get_thumbs($id) {
        $this->db->select('a.*, b.default_thumbnail, b.ads_id');
        $this->db->from('files a');
        $this->db->join('ads_thumbnails b', 'a.id = b.file_id');
        $this->db->order_by('b.file_id', 'desc');
        $this->db->where('b.ads_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_videoInfo($vid) {

        $this->db->select('f.name');
        $this->db->from('ads a');
        $this->db->where('a.id', $vid);        
        $this->db->join('files f', 'a.file_id = f.id', 'left');
        $query = $this->db->get();
        $result = $query->result();
        $fileInfo = $result[0]->name;
        if ($fileInfo != "") {
            return $fileInfo;
        } else {
            return 0;
        }
    }
    
    function get_defaultThumb($vid) {
        $this->db->select('default_thumbnail');
        $this->db->from('ads_thumbnails');
        $this->db->where('ads_id', $vid);
        $query = $this->db->get();
        $thumbVal = '0';
        foreach ($query->result_array() as $row) {
            $default_thumbnail = $row['default_thumbnail'];
            if ($default_thumbnail == '1') {
                $thumbVal = '1';
            }
        }
        return $thumbVal;
    }
	
    function get_thumbIds($id) {
        $this->db->select('a.id, a.name');
        $this->db->from('files a');
        $this->db->join('ads_thumbnails b', 'a.id = b.file_id');
        $this->db->where('b.ads_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getThumbsInfo($id) {
        $this->db->select('a.name');
        $this->db->from('files a');
        $this->db->join('ads_thumbnails b', 'a.id = b.file_id');
        $this->db->order_by('b.file_id', 'desc');
        $this->db->where('b.ads_id', $id);
        $query = $this->db->get();
        return $query->result();
    }



    function setDefaultImg($content_id, $file_id) {
        if ($content_id != '' && $file_id != '') {
            $result = $this->updateDefaultImgPre($content_id);
            if ($result) {
                $data = array(
                    'default_thumbnail' => '1',
                );
                $this->db->where('ads_id', $content_id);
                $this->db->where('file_id', $file_id);
                $this->db->update('ads_thumbnails', $data);
                return true;
            }
        }
    }

    function updateDefaultImgPre($content_id) {
        $data = array(
            'default_thumbnail' => '0',
        );
        $this->db->where('ads_id', $content_id);
        $this->db->update('ads_thumbnails', $data);
        return true;
    }

    function getThumbImgName($file_id) {
        $this->db->select('name');
        $this->db->from('files');
        $this->db->where('id', $file_id);
        $query = $this->db->get();
        $result = $query->result();
        $fileName =  $result[0]->name;
        if ($fileName != "") {
            return $fileName;
        } else {
            return 0;
        }
    }

    function deleleThumb($content_id, $file_id) {
        $this->db->delete('ads_thumbnails', array('ads_id' => $content_id, 'file_id' => $file_id));
        $this->db->delete('files', array('id' => $file_id));
        return true;
    }
    
    function delete_video($id) {
        if($id){
            $videoFileId = $this->getVideoFileIds($id); 
          //  $deleteKeyword = $this->deletekeywords($id);
            
            $videoThumbFileIds = $this->getVideoThumbFileIds($id);
            if(isset($videoThumbFileIds)){
                foreach($videoThumbFileIds as $fileinfo){
                    if($this->checkIfRecordsExists('files', 'id', $fileinfo->file_id)){
                        $this->db->delete('files', array('id' => $fileinfo->file_id));
                    }
                }
            }
            /*if($this->checkIfRecordsExists('video_source', 'content_id', $id))
            {
                $this->db->delete('video_source', array('content_id' => $id));
            }*/
            
            //If($videoFileId) {
                if($this->checkIfRecordsExists('ads', 'id', $id)){
                    $this->db->delete('ads', array('id' => $id));
                }
                if($this->checkIfRecordsExists('files', 'id', $videoFileId)){
                    $this->db->delete('files', array('id' => $videoFileId));
                }
             /*  if($this->checkIfRecordsExists('video_flavors', 'content_id', $id)){
                   $this->db->delete('video_flavors', array('content_id' => $id));
                }
                */
                if($this->checkIfRecordsExists('ads_thumbnails', 'ads_id', $id)){
                   $this->db->delete('ads_thumbnails', array('ads_id' => $id));
                }
                              
                //$this->db->delete('wowza_video', array('content_id' => $id));
                return 1;
            //} else {
                    //return 0;
            //}
        } else {
        return 0;
        }
    }
    
    function delete_location($id){
        if($id){
            $this->db->delete('ads_location', array('id' => $id));
            return 1;
        }  else {
            return 0;
        }
    }
    
    
    function _saveThumb($data){
        $cid = $data['ads_id'];
        if(isset($cid)){
            ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['status'] = $data['status'];
            $file['uid'] = $data['uid'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('files');
            $fid = $this->db->insert_id();
            
            ###inserting data in videos table with contents_id and file_id###
            
            $this->db->set('ads_id', $cid);
            $this->db->set('file_id', $fid);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('ads_thumbnails');            
            $thumbId = $this->db->insert_id();
        }
        return $fid;
    }
    
    function getVideoFileIds($id) {
        $this->db->select('a.id');
        $this->db->from('files a');
        $this->db->join('ads b', 'a.id = b.file_id');
        $this->db->where('b.id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
            $result = $query->result();
            if(count($result))
            {
                    $videoFileId = $result[0]->id;
                    return $videoFileId;
            } else {
                    return 0;
            }

    }
    
     function getVideoThumbFileIds($id) {
        $this->db->select('a.file_id');
        $this->db->from('ads_thumbnails a');
        $this->db->where('a.ads_id', $id);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        $result = $query->result();
        return $result;
    }
    
    function checkIfRecordsExists($table, $field, $value){
        $this->db->select('id');
        $this->db->from($table);
        $this->db->where($field, $value);
        $query = $this->db->get();
        $result = $query->result();
        $cnt = count($result);
        if($cnt > 0)
        {
            return true;
        } else {
            return false;
        }
        
    }
    
    function getstatuscount($uid) {
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        
	$this->db->select('count(a.id) as total');
	$this->db->from('ads a');
	$this->db->join('ads_flavored_video fv', 'fv.ads_id = a.id','left');	
	$this->db->join('categories', 'categories.id = a.category', 'left'); 	
        $this->db->where_in('a.uid', $id); 
	$this->db->order_by('a.id', 'DESC');
        $query = $this->db->get();
	//echo $this->db->last_query();
        $result = $query->row();
	return $result->total;
    }    
    
    function getstatus($uid, $limit, $start) {
	$id = $this->get_ownerid($uid);
        array_push($id, $uid);
        
	$this->db->select('a.id,a.ad_title,fv.type,categories.category,a.created as assignedTime,a.transcode_status as status,fv.path as previewPath,fv.created as completedTime');
	$this->db->from('ads a');
	$this->db->join('ads_flavored_video fv', 'fv.ads_id = a.id','left');	
	$this->db->join('categories', 'categories.id = a.category', 'left'); 	
        $this->db->where_in('a.uid', $id); 
	$this->db->order_by('a.id', 'DESC');
	$this->db->limit($limit, $start);
        $query = $this->db->get();
	//echo $this->db->last_query();
	$data = $query->result();
        return $data;
    }
    
    function video_detail($id) {
        $this->db->select('a.*, b.category , c.username, e.name as file');
        $this->db->from('ads a');
        $this->db->where('a.id', $id);
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');       
        $this->db->join('files e', 'a.file_id = e.id', 'left');
        $query = $this->db->get();
        $data = $query->result();
        return $data;
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
	$this->db->from('content_cuepoints a');
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
  
  function _saveAdsLocation($data){
        $cid = $data['ads_id'];
        if(isset($cid)){
            
            ###inserting data and return id###
            $this->db->set($data);
            $this->db->set('created','NOW()',FALSE);
            $this->db->set('modified','NOW()',FALSE);
            $this->db->insert('ads_location');
            $fid = $this->db->insert_id();
            
        }
        return $fid;
    }
    
    function get_AdsLocationInfoCount($vid,$data='') {

        $this->db->select('a.id,a.latitude,a.longitude,a.formatted_address');
        $this->db->from('ads_location a');
        $this->db->where('a.ads_id', $vid);
        $this->db->like('a.formatted_address', trim($data['formatted_address']));
        $this->db->order_by('a.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result();
        $fileInfo = $result[0]->id;
        if ($fileInfo != "") {
            return count($result);
        } else {
            return 0;
        }
    }
    
    function get_AdsLocationInfo($vid, $limit, $start, $data='') {

        $this->db->select('a.id,a.latitude,a.longitude,a.formatted_address');
        $this->db->from('ads_location a');
        $this->db->where('a.ads_id', $vid);
        $this->db->like('a.formatted_address', trim($data['formatted_address']));
        $this->db->order_by('a.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $result = $query->result();
        $fileInfo = $result[0]->id;
        if ($fileInfo != "") {
            return $result;
        } else {
            return 0;
        }
    }
    
    /*
     *Function for keyword insert
     *$keydata is post keyword example 'computer,hello, hollywood, bollywood'
    */
    function _setKeyword($keywords, $ads_id){
        //echo $keywords.$ads_id; exit;
        $keydata = explode(',', $keywords);
        foreach ($keydata as $value) {
            $this->db->select('id');
            $this->db->from('keywords');
            $this->db->where('name', $value);
            $query = $this->db->get();
            $result = $query->result();
            if(count($result) > 0){
                $keyword_ids[] = $result[0]->id;
            }else{
            $this->db->set('name', $value);
            $this->db->set('created', 'NOW()', false);
            $this->db->set('modified', 'NOW()', false);
            $this->db->insert('keywords');
            $this->result = $this->db->insert_id();
            $keyword_ids[] = $this->result;    
            } 
        }
            $data = array('ads_id' => $ads_id);
            $this->db->delete('ads_keywords', $data);
            $total = array();
            //insert data in content_keywords table
            foreach($keyword_ids as $key=>$val){
                $data = array('ads_id'=>$ads_id,'keyword_id'=>$val,'status'=>1);
                $this->db->set($data);
                $this->db->set('created', 'NOW()', false);
                $this->db->set('modified', 'NOW()', false);
                $this->db->insert('ads_keywords', $data);
                $total[] = $this->db->insert_id();
            }
        return $total;
    }
    
    /*
     *Function for get Keyword
     */
    function _getKeyword($content_id){
        $this->db->select('keywords.name');
        $this->db->from('keywords');
        $this->db->join('ads_keywords','keywords.id = ads_keywords.keyword_id');
        $this->db->where('ads_keywords.ads_id', $content_id);
        $query = $this->db->get();
        $keyword = $query->result_array();
	return implode(',', $this->_array_column($keyword, 'name'));
    }
    function _array_column($keyword, $field) {
	$ret = array();
	foreach($keyword as $keywords){
	    foreach($keywords as $key=>$val){
		if($key == $field){		    
		    $ret[] = $val;
		}
	    }
	}
	return $ret;
    }
    
    function getUserKeywords($id)
    {
      $this->db->select('c.keywords,c.gender,c.dob');
      //$this->db->from('user_content_keywords k');
      $this->db->from('customers c');
      //$this->db->join('customers c','c.id=k.user_id','left');
      $this->db->where('id',$id);
      $this->db->limit(1);
      $query = $this->db->get();
    //  echo $this->db->last_query();echo '<br>';
     return $query->row_array();
      /*if($result){
	 return unserialize($result->keywords);
      }else{
	 return 0;
      }*/
    }
        
    /* function to get users location wise ads list */
    function getUserLocationWiseAds($lat,$long,$id,$data,$limit=0){
      $this->db->start_cache();
      $keywords = array();
      if(!empty($data['keywords']) ){	 
	//$keywords = unserialize($data->keywords);
	$keywords = explode(",",$data['keywords']);
      }
     // print_r($keywords);
      if(@$data->gender !=''){
	$keywords[] = $data->gender;
      }
   if($keywords){      
     // $this->db->where_in('k.name',unserialize($data->keywords));
      $this->db->where_in('k.name',$keywords);      
   }
    
    if(@$data['dob'] && $data['dob'] !='0000-00-00'){
       $date1 = date_create(date('Y-m-d'));
       $date2 = date_create($data['dob']);
       $datediff =  date_diff($date1,$date2);
       $age = $datediff->y;
       $between = sprintf("%s BETWEEN a.age_group_from AND a.age_group_to",$age);
      $this->db->where($between, null, false);      
    }
     
     if($lat !='' && $long!=''){
      $distance_query = 'MIN(ROUND(((ACOS(SIN('.$lat.' * PI() / 180) * SIN(al.latitude * PI() / 180) + COS('.$lat.' * PI() / 180) * COS(al.latitude * PI() / 180) * COS(('.$long.' - al.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515)))';
    //  $this->db->order_by('distance');
      $distance = ",".$distance_query." AS distance";      
      $this->db->order_by("(CASE WHEN $distance_query IS NULL THEN 1 ELSE 0 END)",NULL,FALSE);
     }
      $this->db->select('a.uid,a.ad_type,c.name as file_name,k.name as tags,c.relative_path as vast_file,a.id as ads_id'.$distance);     
      $this->db->from('ads a');
      $this->db->join('ads_location al','a.id=al.ads_id','left');
      $this->db->join('files c','a.vast_file_id=c.id','inner');
      $this->db->join('ads_keywords ak','a.id=ak.ads_id','left');
      $this->db->join('keywords k','ak.keyword_id=k.id','left');
      
    //  $this->db->where('a.content_id',$id);
     // $this->db->having('ROUND(distance) <= ' ,3);
     $this->db->group_by('a.id');
          
      $this->db->stop_cache();
      
      //echo $this->db->count_all_results();
      //$query = $this->db->get();
      if($this->db->count_all_results()==0){	 
	  $this->db->or_where('1=1',null,false);	  
      }
       $this->db->limit($limit);
      $query = $this->db->get();
      $this->db->flush_cache();
     // echo '<br>'.$this->db->last_query();
     // die;
      return $query->result();
    }
    
    function getCuePoints($id,$type,$flag=0){
      if($flag){
	 $this->db->select('count(id) as tot');
	 $this->db->limit(1);
      }else{
	 $this->db->select('cue_points');
      }
      
      if($type=='live'){
	 $this->db->where('type',$type);
      }
      $this->db->from('content_cuepoints');
      $this->db->where('content_id',$id);
      $this->db->order_by('cue_points');
      $query = $this->db->get();
      //echo '<br>'.$this->db->last_query();
      if($flag){
      $result = $query->row();
	 return $result->tot;
      }else{
	 $result = $query->result_array();
	 //-- convert array into element --//       
        foreach ($result AS $key => $value) {
            $cuepoints[] = $value['cue_points'];
        }
	return $cuepoints;
        //-----------------------------------//
      }
    }
    
    function get_swtich()
    {
     /* $switch_db = $this->load->database('stitch_report', TRUE);
      $switch_db->select('*');
      $switch_db->from('restAPI');
      $query = $switch_db->get();*/
      $this->db->select('*');
      $this->db->from('restAPI');
      $query = $this->db->get();
      //echo $this->db->last_query();die;*/
      return $query->row();
    }
}
