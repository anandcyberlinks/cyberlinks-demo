<?php
ini_set('display_errors', 'On');
class Videos_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    
    /*
     * Function for live streaming
     */
    function saveUrl($url, $uid){
        $this->db->delete('options', array('user_id'=>$uid, 'key'=>'livestream'));
        $this->db->insert('options', array('user_id'=>$uid, 'key'=>'livestream', 'value'=>$url));
    }
    
    function getLivestream($uid){
        $this->db->select('value');
        $this->db->where('user_id', $uid);
        $this->db->where('key', 'livestream');
        $query = $this->db->get('options');
        return $query->result();
    }
    
    function deleteUrl($uid){
       $this->db->delete('options', array('user_id'=>$uid, 'key'=>'livestream')); 
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

        $contents['title'] = $data['content_title'];
        if(isset($data['description'])){
            $contents['description'] = $data['description'];
            $contents['status'] = $data['status'];
        }
	
	if(isset($data['content_token'])){
            $contents['content_token'] = $data['content_token'];
        }	
	if(isset($data['content_from'])){
            $contents['type'] = $data['content_from'];
        }        
        if(isset($data['genre'])){
            $contents['genre'] = $data['genre'];
        }	
        if(isset($data['content_id'])){
            $cid = $data['content_id'];
            $contents['category'] = $data['content_category'];
            $contents['feature_video'] = $data['feature_video'];
            
            $this->db->where('id', $cid);
            $this->db->set($contents);
            $this->db->update('contents');
            
        }else{
            ###inserting data in contents table and return id###
            $contents['uid'] = $data['uid'];
            if(isset($data['category'])){
                $contents['category'] = $data['category'];
            }
            $this->db->set($contents);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('contents');
            $cid = $this->db->insert_id();
            //$this->db->last_query(); 
            
            ###inserting data in video_detail table with contents_id###
            $this->db->set('content_id', $cid);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('video_detail');
            
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
            
            ###inserting data in videos table with contents_id and file_id###
            $this->db->set('content_id', $cid);
            $this->db->set('file_id', $fid);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('videos');
            
            ###transcoding video ###
            if($data['type'] != 'youtube'){
                $this->db->select('*');
                $this->db->from('flavors');
                $query = $this->db->get();
                $flav = $query->result();
                foreach($flav as $flavorVal){
                    $videoFlavorsData['flavor_id'] = $flavorVal->id;
                    $videoFlavorsData['content_id'] = $cid;
                    $videoFlavorsData['file_id'] = $fid;
                    $videoFlavorsData['status'] = 'pending';
                    //$videoFlavorsData['created'] = date('Y-m-d');
                    $this->db->set('created','NOW()',FALSE);
                    $this->db->insert('video_flavors', $videoFlavorsData);
                }
            }
        }
        return $cid;
    }
    ###saveVideo() function end #####

        /*
     *Function for keyword insert
     *$keydata is post keyword example 'computer,hello, hollywood, bollywood'
    */
    function _setKeyword($keywords, $content_id){
        //echo $keywords.$content_id; exit;
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
            $this->db->insert('keywords');
            $this->result = $this->db->insert_id();
            $keyword_ids[] = $this->result;    
            } 
        }
            $data = array('content_id' => $content_id);
            $this->db->delete('content_keywords', $data);
            $total = array();
            //insert data in content_keywords table
            foreach($keyword_ids as $key=>$val){
                $data = array('content_id'=>$content_id,'keyword_id'=>$val,'status'=>1);
                $this->db->set($data);
                $this->db->insert('content_keywords', $data);
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
        $this->db->join('content_keywords','keywords.id = content_keywords.keyword_id');
        $this->db->where('content_keywords.content_id', $content_id);
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
    
    
    function get_videocount($uid, $data=''){
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        $this->db->select('contents.*');
        $this->db->from('contents');
        $this->db->join('categories', 'contents.category = categories.id', 'left');
        $this->db->where_in('contents.uid', $id);
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('contents.category', $data['category']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
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
        $this->db->from('contents a');
        $this->db->where_in('a.uid', $id); 
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
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

    function edit_profile($id) {
        $this->db->select('a.*, b.id , c.username, g.name as file');
        $this->db->from('contents a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos f', 'a.id = f.content_id');
        $this->db->join('files g', 'f.file_id = g.id');
        //$this->db->join('video_detail h', 'h.content_id = a.id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return reset($query->result());
    }
    
    function _saveThumb($data){
        $cid = $data['content_id'];
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
            
            $this->db->set('content_id', $cid);
            $this->db->set('file_id', $fid);
            $this->db->set('created','NOW()',FALSE);
            $this->db->insert('video_thumbnails');            
            $thumbId = $this->db->insert_id();
        }
        return $fid;
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
    
    
    /*function deleteyoutube($id){
        $this->db->delete('videos', array('content_id' => $id));
		$this->db->delete('contents', array('id' => $id));
    }*/
    function delete_video($id) {
        if($id){
            $videoFileId = $this->getVideoFileIds($id); 
            $deleteKeyword = $this->deletekeywords($id);
            
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
                if($this->checkIfRecordsExists('contents', 'id', $id)){
                    $this->db->delete('contents', array('id' => $id));
                }
                if($this->checkIfRecordsExists('files', 'id', $videoFileId)){
                    $this->db->delete('files', array('id' => $videoFileId));
                }
                if($this->checkIfRecordsExists('video_flavors', 'content_id', $id)){
                   $this->db->delete('video_flavors', array('content_id' => $id));
                }
                if($this->checkIfRecordsExists('video_thumbnails', 'content_id', $id)){
                   $this->db->delete('video_thumbnails', array('content_id' => $id));
                }
                if($this->checkIfRecordsExists('videos', 'content_id', $videoFileId)){
                    $this->db->delete('videos', array('id' => $videoFileId));
                }     
                if($this->checkIfRecordsExists('content_keywords', 'content_id', $id)){
                    $this->db->delete('content_keywords', array('content_id' => $id));
                }
                if($this->checkIfRecordsExists('video_detail', 'content_id', $id)){
                    $this->db->delete('video_detail', array('content_id' => $id));
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
    
    	
    function getVideoFileIds($id) {
        $this->db->select('a.id');
        $this->db->from('files a');
        $this->db->join('videos b', 'a.id = b.file_id');
        $this->db->where('b.content_id', $id);
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
        $this->db->from('video_thumbnails a');
        $this->db->where('a.content_id', $id);
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
    
    function deletekeywords($id) {
        $this->db->select('a.id');
        $this->db->from('keywords a');
        $this->db->join('content_keywords b', 'a.id = b.keyword_id');
        $this->db->where('b.content_id', $id);
        $query = $this->db->get();
        $result = $query->result();
        $cnt = count($result);
        if($cnt)
        {
            for($i=0; $i<$cnt; $i++){
                $this->db->delete('keywords', array('id' => $result[$i]->id));
            }
        } else {
                return 0;
        }

    }


    /*function upload_detail($post) {
        $data = array(
            'content_id' => $post['content_id'],
            'file_id' => $post['filed_id'],
            'status' => $post['status']
        );
        $this->db->set('created','NOW()',FALSE);
        $this->db->set('modified','NOW()',FALSE);
        $this->db->insert('videos', $data);
        $this->result = $this->db->insert_id();
        return $this->result;
    }

    function update_profile($post) {
        $data = array(
            'title' => $post['title'],
            //'star_cast' => $post['star_cast'],
            'category' => $post['category'],
            //'channel' => $post['channel'],
            'description' => $post['description'],
            'status' => $post['status'],
			'feature_video' => $post['feature_video']
        );
        $this->db->set('modified','NOW()', FALSE);
        $this->db->where('id', $post['id']);
        $this->db->update('contents', $data);
        //echo $this->db->last_query(); exit;
        return true;
    }*/
    function save_scheduling($value) {
        $this->db->set($value);
        $this->db->insert('video_scheduling');
        $this->result = $this->db->insert_id();
        return $this->result;
    }

    function get_scheduling($id) {
        $this->db->select('*');
        $this->db->from('video_scheduling');
        $this->db->where('content_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function update_scheduling($post) {
        $data = array(
            'schedule' => $post['schedule'],
            'startDate' => $post['startDate'],
            'endDate' => $post['endDate'],
            'content_id' => $post['content_id'],
        );
        $this->db->set('modified','NOW()',FALSE);
        $this->db->where('content_id', $post['content_id']);
        $this->db->update('video_scheduling', $data);
        return true;
    }


	
    /*function checkvideoexist($data) {
        $this->db->from('contents');
        $this->db->where('title', $data);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if ($rowcount > 0)
            return 0;
        else
            return 1;
    }*/

    /* function added to get thumnails(4/8/14 ry) */

    function get_thumbs($id) {
        $this->db->select('a.*, b.default_thumbnail, b.content_id');
        $this->db->from('files a');
        $this->db->join('video_thumbnails b', 'a.id = b.file_id');
        $this->db->order_by('b.file_id', 'desc');
        $this->db->where('b.content_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getThumbsInfo($id) {
        $this->db->select('a.name');
        $this->db->from('files a');
        $this->db->join('video_thumbnails b', 'a.id = b.file_id');
        $this->db->order_by('b.file_id', 'desc');
        $this->db->where('b.content_id', $id);
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
                $this->db->where('content_id', $content_id);
                $this->db->where('file_id', $file_id);
                $this->db->update('video_thumbnails', $data);
                return true;
            }
        }
    }

    function updateDefaultImgPre($content_id) {
        $data = array(
            'default_thumbnail' => '0',
        );
        $this->db->where('content_id', $content_id);
        $this->db->update('video_thumbnails', $data);
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
        $this->db->delete('video_thumbnails', array('content_id' => $content_id, 'file_id' => $file_id));
        $this->db->delete('files', array('id' => $file_id));
        return true;
    }

    function get_videoInfo($vid) {

        $this->db->select('f.name');
        $this->db->from('contents a');
        $this->db->where('a.id', $vid);
        $this->db->join('videos v', 'a.id = v.content_id', 'left');
        $this->db->join('files f', 'v.file_id = f.id', 'left');
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
        $this->db->from('video_thumbnails');
        $this->db->where('content_id', $vid);
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
        $this->db->join('video_thumbnails b', 'a.id = b.file_id');
        $this->db->where('b.content_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    /* function added for status */
    	
    function getstatus($uid, $limit, $start) {
	$id = $this->get_ownerid($uid);
        array_push($id, $uid);
        
	$this->db->select('c.id,c.title,categories.category,f.*,vf.created as assignedTime,vf.status,fv.path as previewPath,fv.created as completedTime');
	$this->db->from('video_flavors vf');
	$this->db->join('flavored_video fv', 'vf.id = fv.flavor_id', 'left');
	$this->db->join('flavors f', 'f.id = vf.flavor_id', 'left');
	$this->db->join('contents c', 'vf.content_id = c.id', 'left');
	$this->db->join('categories', 'categories.id = c.category', 'left'); 
	$this->db->where('vf.content_id >', 0);
        $this->db->where_in('c.uid', $id); 
	$this->db->order_by('c.id', 'DESC');
	$this->db->limit($limit, $start);
        $query = $this->db->get();
	//echo $this->db->last_query();
	$data = $query->result();
        return $data;
    } 

    function getstatuscount($uid) {
        $id = $this->get_ownerid($uid);
        array_push($id, $uid);
        
	$this->db->select('c.id,c.title,categories.category,f.*,vf.created as assignedTime,vf.status,fv.path as previewPath,fv.created as completedTime');
	$this->db->from('video_flavors vf');
	$this->db->join('flavored_video fv', 'vf.id = fv.flavor_id', 'left');
	$this->db->join('flavors f', 'f.id = vf.flavor_id', 'left');
	$this->db->join('contents c', 'vf.content_id = c.id', 'left');
	$this->db->join('categories', 'categories.id = c.category', 'left'); 
	$this->db->where('vf.content_id >', 0);
        $this->db->where_in('c.uid', $id); 
	$this->db->order_by('c.id', 'DESC');
        $query = $this->db->get();
	//echo $this->db->last_query();

        return count($query->result());
    }    
    
    function checkstatus($data) {
        $this->db->select('*');
        $this->db->where($data);
        $query = $this->db->get('video_flavors');
        return $query->result();
    }
    
    function changestatus($data) {
        $data['status'] = 'inprocess';
        $data['created'] = date('y-m-d h:i:s');
        $data['modified'] = date('y-m-d h:i:s');
        $this->db->insert('video_flavors', $data);
    }

    function deletestatus($data) {
        $id = $data[0]->id;
        $this->db->delete('video_flavors', array('id' => $id));
    }

    /* function added to edit video_status() */

    /* function used for upload video source starts */

    function checkVideoSrc($url) {
        $this->db->select('*');
        $this->db->from('video_source');
        $this->db->where('url', $url);
        $query = $this->db->get();
        $srcurl = count($query->result());
        return $srcurl;
    }

    function saveVideoSrc($post) {
        $data = array(
            'url' => $post['source_url'],
            'content_id' => $post['content_id'],
            'uid' => $post['uid'],
            'created' => $post['created'],
        );
        $this->db->insert('video_source', $data);
        return true;
    }

    /* function used for upload video source ends */

    /* function used for video setting flavor section starts */

    function getsetting($vid) {
        $query = $this->db->query("
            select * from flavors f left join (select flavor_id,file_id,status from video_flavors where content_id = $vid) vf on vf.flavor_id = f.id   
            ");
        //   echo   $this->db->last_query();
        return $query->result();
    }
    
    function getFlavorData() {
        $this->db->select('*');
        $this->db->from('flavors');
        $query = $this->db->get();
        return $query->result();
    }

    function checkFlavorData($settings_key, $uid) {
        $this->db->select('id');
        $this->db->from('options');
        $this->db->where(array('key' => $settings_key, 'user_id' => $uid));
        $query = $this->db->get();
        $countFlavorData = count($query->result());
        return $countFlavorData;
    }

    function getOptionData($uid) {
        $this->db->select('value');
        $this->db->from('options');
        $this->db->where(array('key' => 'flavor_settings', 'user_id' => $uid));
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $optionData = $row['value'];
        }
        if (isset($optionData)) {
            return $optionData;
        } else {
            return "";
        }
    }

    function update_flavorOption($post, $settings_key, $uid) {
        $this->db->where(array('key' => $settings_key, 'user_id' => $uid));
        $this->db->update('options', $post);
        return true;
    }

    function insert_flavorOption($post) {
        $this->db->insert('options', $post);
    }

    function getPlayerData($uid) {
        $this->db->select('value');
        $this->db->from('options');
        $this->db->where(array('key' => 'player_settings', 'user_id' => $uid));
        $query = $this->db->get();
        $optionData = array();
        foreach ($query->result_array() as $row) {
            $optionData = $row['value'];
        }
        return $optionData;
    }
	
    function insert_video_flavors($post){
        $this->db->set('created','NOW()',FALSE);
        $this->db->insert('video_flavors', $post);
    }

    /* function used for video setting flavor section starts */


    /* function used for promo section */

    function get_promos($id) {
        $this->db->select('a.*, b.default, b.file_id');
        $this->db->from('files a');
        $this->db->join('video_promos b', 'a.id = b.file_id');
        $this->db->order_by('b.id', 'desc');
        $this->db->where('b.content_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_defaultPromo($vid) {
        $this->db->select('default');
        $this->db->from('video_promos');
        $this->db->where('content_id', $vid);
        $query = $this->db->get();
        $promoVal = '0';
        foreach ($query->result_array() as $row) {
            $default_promo = $row['default'];
            if ($default_promo == '1') {
                $promoVal = '1';
            }
        }
        return $promoVal;
    }

    function video_detail($id) {
        $this->db->select('a.*, b.category , c.username, e.name as file');
        $this->db->from('contents a');
        $this->db->where('a.id', $id);
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id', 'left');
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

    /* functions added for promo section */
	
	function getCountryList(){
	    $this->db->select('id, name');
            $query = $this->db->get('countries');
            return $query->result();
	}

    function get_videocountstatus($uid){
         $query = sprintf("SELECT c.uid,SUM(1) as total_video, SUM(IF(f.type = 'youtube',1,0)) as youtube_video,vf.tj as total_jobs,vf.tcomp as completed_jobs,vf.tpend as pending_jobs,vf.tinpro as inprocess_jobs
        FROM `contents` c 
        left join videos v on v.content_id = c.id
        left join files f on f.id = v.file_id
        left join ( SELECT c.uid as uid, SUM(1) as tj, SUM(IF(vf.status = 'completed',1,0)) as tcomp, SUM(IF(vf.status = 'pending',1,0)) as tpend,SUM(IF(vf.status = 'inprocess',1,0)) as tinpro FROM `video_flavors` vf left join contents c on c.id = vf.content_id where c.uid = %d ) vf on vf.uid = c.uid WHERE c.uid = %d ",$uid,$uid);
        $res = $this->db->query($query);
        return $res->result();
    }

    /*
    /--------------------------------------------------------------- 
    / Advance Video save Start
    /---------------------------------------------------------------
   */

   /* function get_videofieldadvance($id) {
        $this->db->select('*');
        $this->db->from('fields');
        $this->db->where('uid', $id);
        $query = $this->db->get(); 
      //echo $this->db->last_query();die();
        return $query->result();
    }*/
    function get_videofieldadvance($id) {
        $this->db->select('a.id as field_id, a.uid,a.form_id,a.field_title,a.field_name,a.field_type,a.field_options,a.field_validate,a.status');
        $this->db->from('fields a');
        $this->db->where('uid', $id);
        $query = $this->db->get(); 
      //echo $this->db->last_query();die();
        return $query->result();
    }
	
  
   /* function get_videofieldadvance($id, $cid) {
        $this->db->select('a.id as field_id, a.uid,a.form_id,a.field_title,a.field_name,a.field_type,a.field_options,a.field_validate,a.status,c.value');
        $this->db->from('field_value c');       
	$this->db->join('fields a','a.id = c.field_id','right');
	$this->db->join('forms b','b.uid = a.uid','right');
        $this->db->where('a.uid', $id);
         $this->db->where('c.content_id', $cid);
	//$this->db->join('forms b','b.uid = a.uid','right');
        $this->db->where('b.form_name', "extra_field");
        $query = $this->db->get(); 
       //echo $this->db->last_query();die();
        return $query->result();
    }*/

    function fetchvalue($cid, $fid){
        $this->db->select('id, value');
        $this->db->where('content_id', $cid);
        $this->db->where('field_id', $fid);
        $query = $this->db->get('field_value');
          return $query->result();
       // print_r($query->result());
    }
    
   	
    function save_videofieldvalueadvance($value) {
	 //print_r($value);
	 //die();
	$cid = base64_decode($value['cid']);
	
	unset($value['cid']);
	foreach($value as $key=>$val){ 
        $result = $this->get_videofieldvalueadvance($key, $cid);

	//echo $key."=>".$val;
        $this->db->set('content_id',$cid);
	$this->db->set('field_id',$key);
	$this->db->set('value',$val);
        if(count($result) == 0){
            $this->db->insert('field_value');
        }else{
            $this->db->where('field_id', $key);
             $this->db->where('content_id', $cid);
            $this->db->update('field_value');
        }
        $id[] = $this->result = $this->db->insert_id();
	}
 
        //$this->result = $this->db->insert_id();
       // echo count($id);
      // return $query->result();
    }
    
    

    function get_videofieldvalueadvance($id, $cid) {
        
        $this->db->select('value');
        $this->db->from('field_value');
        $this->db->where('field_id', $id);
        $this->db->where('content_id', $cid);
        $query = $this->db->get();

        return $query->result();

    }

    function update_videofieldvalueadvance($post) {
        $data = array(
            'schedule' => $post['schedule'],
            'startDate' => $post['startDate'],
            'endDate' => $post['endDate'],
            'content_id' => $post['content_id'],
        );
        $this->db->set('modified','NOW()',FALSE);
        $this->db->where('content_id', $post['content_id']);
        $this->db->update('field_value', $data);
        return true;
    }

    /*
    /--------------------------------------------------------------- 
    / Advance Video save End
    /---------------------------------------------------------------
   */


    function checkIfYoutubeVideoExists($id){
        
        $this->db->select('id');
        $this->db->from('contents');
        $this->db->where('content_token', $id);
        $query = $this->db->get();
        $result = $query->result();
        if(count($result) > 0){
            return true;
        }else{
            return false;
        }

    }
    
    /*
    /--------------------------------------------------------------- 
    / get video info(misssing file or thumbs)
    /---------------------------------------------------------------
   */
    
    function debugVideoInfo($uid, $limit, $start,  $data='') {
        $like = '';
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $like = " and c.title LIKE '%". $data['content_title'] ."%' ";
        }
        $query = sprintf('select
                        c.id as contentId, c.title,
                        f.name as video_filename, f.relative_path as video_relative_path,
                        f_thumb.name as thumb_filename, f_thumb.relative_path as thumb_relative_path
                        from contents c
                        left join videos v on v.content_id = c.id
                        left join files f on f.id =  v.file_id
                        left join video_thumbnails vt on vt.content_id =  c.id
                        left join files f_thumb on f_thumb.id =  vt.file_id
                        where c.uid=%d and c.status=1 and vt.default_thumbnail=1 %s limit %d,%d',$uid, $like, $start, $limit);
        $data = $this->db->query($query)->result();
        array_walk($data,function($data){
            
            $true = base_url().'assets/img/test-pass-icon.png';
            $false = base_url().'assets/img/test-fail-icon.png';
            
            $data->video_relative_path = file_exists(REAL_PATH.$data->video_relative_path) ? $true : $false ;
            $data->thumb_relative_path = file_exists(REAL_PATH.$data->thumb_relative_path) ? $true : $false ;
            $data->thumb_small = file_exists(REAL_PATH.THUMB_SMALL_PATH.$data->thumb_filename) ? $true : $false ;
            $data->thumb_medium = file_exists(REAL_PATH.THUMB_MEDIUM_PATH.$data->thumb_filename) ? $true : $false ;
            $data->thumb_large = file_exists(REAL_PATH.THUMB_LARGE_PATH.$data->thumb_filename) ? $true : $false ;
        });
        return $data;
    }
    
    function get_debugVideoInfoCount($uid,  $data='') {
        $like = '';
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $like = " and c.title LIKE '%". $data['content_title'] ."%' ";
        }
        $query = sprintf('select
                        c.id as contentId, c.title,
                        f.relative_path as video_relative_path,
                        f_thumb.name as thumb_filename, f_thumb.relative_path as thumb_relative_path
                        from contents c
                        left join videos v on v.content_id = c.id
                        left join files f on f.id =  v.file_id
                        left join video_thumbnails vt on vt.content_id =  c.id
                        left join files f_thumb on f_thumb.id =  vt.file_id
                        where c.uid=%d and c.status=1 and vt.default_thumbnail=1 %s', $uid, $like);
        $data = count($this->db->query($query)->result());
        return $data;
    }

}
