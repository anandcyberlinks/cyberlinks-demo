<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Content extends Apis{
    
    public $query = '';
    
    function __construct(){
        parent::__construct();
        if(isset($this->user->id) && $this->user->id > 0){
            $this->query =  sprintf( 'select 
                            c.id,
                            c.title,
                            c.description,
                            c.uid as `creater_id`,
                            cat.id as `category_id`,
                            cat.category as `category_name`,
                            if(v.views > 0,v.views,0) as `views`,
                            c.feature_video as featured,
                            v.duration as `duration`,
                            cfile.relative_path as `video_basepath`,
                            vtfile.relative_path as `video_basethumb`,
                            p.content_id as `price`,
                            if(ufl.`like` > 0,ufl.`like`,0) as `likes`,
                            ufl.liked,
                            ufl.favorited,
                            if(comments.comments > 0,comments.comments,0) as `comments`,
                            ((SUM(vr.rating) * 100) / SUM(5)) as `rating`,
                            c.created
                            from contents c
                            left join categories cat on cat.id = c.category
                            left join videos v on v.content_id = c.id
                            left join files cfile on cfile.id = v.file_id
                            left join price p on p.content_id = c.id
                            left join video_rating vr on vr.content_id = c.id
                            left join (select user_id,content_id,SUM(`like`) as `like`,SUM(`favorite`) as `favorite`,if(user_id = %d AND `like` = 1,1,0) as liked,if(user_id = %d AND `favorite` = 1,1,0) as favorited from user_favlikes group by content_id) as ufl on ufl.content_id = c.id
                            left join video_thumbnails vt on vt.content_id = c.id
                            left join files vtfile on vtfile.id = vt.file_id
                            left join (select content_id,SUM(1) as comments from comment group by content_id) as comments on comments.content_id = c.id
                            where c.uid = %d AND c.status = 1',$this->user->id,$this->user->id,$this->app->id);
        }else{ 
            $this->query =  sprintf( 'select 
                            c.id,
                            c.title,
                            c.description,
                            c.uid as `creater_id`,
                            cat.id as `category_id`,
                            cat.category as `category_name`,
                            if(v.views > 0,v.views,0) as `views`,
                            c.feature_video as featured,
                            v.duration as `duration`,
                            cfile.relative_path as `video_basepath`,
                            vtfile.relative_path as `video_basethumb`,
                            p.content_id as `price`,
                            if(ufl.`like` > 0,ufl.`like`,0) as `likes`,
                            if(comments.comments > 0,comments.comments,0) as `comments`,
                            ((SUM(vr.rating) * 100) / SUM(5)) as `rating`,
                            c.created
                            from contents c
                            left join categories cat on cat.id = c.category
                            left join videos v on v.content_id = c.id
                            left join files cfile on cfile.id = v.file_id
                            left join price p on p.content_id = c.id
                            left join video_rating vr on vr.content_id = c.id
                            left join (select content_id,SUM(`like`) as `like` from user_favlikes group by content_id) as ufl on ufl.content_id = c.id
                            left join user_favlikes uf on uf.content_id = c.id
                            left join video_thumbnails vt on vt.content_id = c.id
                            left join files vtfile on vtfile.id = vt.file_id
                            left join (select content_id,SUM(1) as comments from comment group by content_id) as comments on comments.content_id = c.id
                            where c.uid = %d AND c.status = 1',$this->app->id);
        }
    }
    
    function getlist_get(){
        $qString = $this->get();
        $total_query = sprintf('select count(id) as tot from contents where uid = %d',$this->app->id);
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s group by c.id limit %d,%d ',$this->query,$this->start,$this->limit);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $dataset[$key]->price = $val->price != '' ? 'Paid' : 'Free';
            if(file_exists($val->video_basethumb)){
                $base_url = strpos('http://',$val->video_basepath) > 0 ? '' : base_url();
                $dataset[$key]->video_basepath = $base_url.$val->video_basepath;
                
                $base_url = strpos('http://',$val->video_basethumb) > 0 ? '' : base_url();
                $dataset[$key]->video_basethumb = $base_url.$val->video_basethumb;
                $filename = end(explode('/',$val->video_basethumb));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().THUMB_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().THUMB_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().THUMB_LARGE_PATH.$filename));
                
            }
            $dataset[$key]->duration = $this->time_from_seconds($val->duration);
        }
        
        $response = array('tr'=>$dataset_count,'cp'=>$this->start + 1,'limit'=>$this->limit,'result'=>$dataset);
        $this->response($response);
    }
    
    function search_get(){
        $qString = $this->get();
        $condition = '';
        switch($qString['k']){
            case 'recent' :
                    $condition.= ' group by c.`id` ';
                    $condition.= sprintf(' ORDER BY c.`id` DESC ');
                break;
            case 'viewed' :
            case 'popular' :
                    $condition.= ' group by c.id ';
                    $condition.= sprintf(' ORDER BY v.`views` DESC ');
                break;
            case 'liked' :
                    $condition.= ' group by c.id ';
                    $condition.= sprintf(' ORDER BY ufl.`like` DESC ');
                break;
            case 'featured' :
                    $condition.= sprintf(' AND c.feature_video = 1 ');
                    $condition.= ' group by c.id ';
                break;
            case 'id' :
                    $condition.= isset($qString['val']) && $qString['val'] != '' ? sprintf('AND c.id = %d ',$qString['val']) : '';
                    $condition.= ' group by c.`id` ';
                break;
            case 'title' :
                    $condition.= isset($qString['val']) && $qString['val'] != '' ? sprintf('AND c.title like "%%%s%%" ',$qString['val']) : '';
                    $condition.= ' group by c.`id` ';
            break;
            case 'category' :
                    $condition.= isset($qString['val']) && $qString['val'] != '' ? sprintf('AND cat.id = %d ',$qString['val']) : '';
                    $condition.= ' group by c.`id` ';
            break;
            case 'cc' :
                    $condition.= isset($qString['title']) && $qString['title'] != '' ? sprintf('AND c.title = %s ',$qString['title']) : '';
                    $condition.= isset($qString['cat']) && $qString['cat'] != '' ? sprintf('AND cat.id = %d ',$qString['cat']) : '';
                    $condition.= ' group by c.`id` ';
            break;
            case 'related' :
                    $content_id = isset($qString['val']) && $qString['val'] != '' ? $qString['val'] : false;
                    if($content_id){
                        $query = sprintf('select c.category from contents c where c.id = %d ',$content_id);
                        $temp = $this->db->query($query)->result();
                        $category_id = isset($temp[0]->category) ? $temp[0]->category : 0;
                        if($category_id > 0){
                            $condition.= sprintf('AND cat.id = %d ',$category_id);
                            $condition.= ' group by c.`id` ';
                        }else{
                            $this->response($response);
                            exit;
                        }
                    }
            break;
            case 'comments' :
                    $content_id = isset($qString['val']) && $qString['val'] != '' ? $qString['val'] : false;
                    if($content_id){
                        $query = sprintf('select c.id,c.comment,c.created_date as created,c.updated_date as modified,
                                         c.approved,c.status,u.id as user_id,u.username,u.email,u.first_name as fname,
                                         u.last_name as lname,u.gender,u.image
                                         from comment c
                                         left join customers u on u.id = c.user_id
                                         where u.id > 0
                                         AND c.approved = "YES"
                                         AND c.content_id = %d ',$content_id);
                        
                        $temp = $this->db->query($query)->result();
                        array_walk($temp,function(&$temp){
                            $temp->gender = $temp->gender == 'male' ? 'Male' : 'Female';
                            $tmp_filepath = 'assets/upload/profilepic/'.$temp->image;
                            if(file_exists($tmp_filepath) && is_file($tmp_filepath)){
                                $temp->image = base_url().$tmp_filepath;
                            }else{
                                $temp->image = base_url(). 'assets/upload/profilepic/userdefault.png';
                            }
                        });
                        $response = array('tr'=>count($temp),'result'=>$temp);
                    }else{
                        $response = array('error' => 'Content Id Not valid');
                    }
                    $this->response($response);
                    exit;
            break;
            default:
                    $response = array('error' => 'No key found');
                    $this->response($response);
                    exit;
            break;
        }
        
        $total_query = sprintf('select count(*) as tot from (%s %s) as tmp ',$this->query,$condition);
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s %s limit %d,%d ',$this->query,$condition,$this->start,$this->limit);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $dataset[$key]->price = $val->price != '' ? 'Paid' : 'Free';
            if(file_exists($val->video_basethumb)){
                $base_url = strpos('http://',$val->video_basepath) > 0 ? '' : base_url();
                $dataset[$key]->video_basepath = $base_url.$val->video_basepath;
                
                $base_url = strpos('http://',$val->video_basethumb) > 0 ? '' : base_url();
                $dataset[$key]->video_basethumb = $base_url.$val->video_basethumb;
                $filename = end(explode('/',$val->video_basethumb));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().THUMB_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().THUMB_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().THUMB_LARGE_PATH.$filename));
                
            }
            $dataset[$key]->duration = $this->time_from_seconds($val->duration);
        }
        
        $response = array('tr'=>$dataset_count,'cp'=>$this->start + 1,'limit'=>$this->limit,'result'=>$dataset);
        $this->response($response);
    }
    
    function favorite_get(){
        $qString = $this->get();
        
        $total_query = sprintf('select count(c.id) as tot from contents c
                               left join user_favlikes uf on uf.content_id = c.id
                               where uf.favorite = 1 and uf.user_id = %d',$this->user->id);
        
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s AND ufl.favorite = 1 and ufl.user_id = %d group by c.id limit %d,%d ',$this->query,$this->user->id,$this->start,$this->limit);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $dataset[$key]->price = $val->price != '' ? 'Paid' : 'Free';
            if(file_exists($val->video_basethumb)){
                $base_url = strpos('http://',$val->video_basepath) > 0 ? '' : base_url();
                $dataset[$key]->video_basepath = $base_url.$val->video_basepath;
                
                $base_url = strpos('http://',$val->video_basethumb) > 0 ? '' : base_url();
                $dataset[$key]->video_basethumb = $base_url.$val->video_basethumb;
                $filename = end(explode('/',$val->video_basethumb));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().THUMB_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().THUMB_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().THUMB_LARGE_PATH.$filename));
                
            }
            $dataset[$key]->duration = $this->time_from_seconds($val->duration);
        }
        
        $response = array('tr'=>$dataset_count,'cp'=>$this->start + 1,'limit'=>$this->limit,'result'=>$dataset);
        $this->response($response);
    }
    
    function like_get(){
        $qString = $this->get();
        
        $total_query = sprintf('select count(c.id) as tot from contents c
                               left join user_favlikes uf on uf.content_id = c.id
                               where uf.like = 1 and uf.user_id = %d',$this->user->id);
        
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s AND ufl.`like` = 1 and ufl.`user_id` = %d group by c.id limit %d,%d ',$this->query,$this->user->id,$this->start,$this->limit);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $dataset[$key]->price = $val->price != '' ? 'Paid' : 'Free';
            if(file_exists($val->video_basethumb)){
                $base_url = strpos('http://',$val->video_basepath) > 0 ? '' : base_url();
                $dataset[$key]->video_basepath = $base_url.$val->video_basepath;
                
                $base_url = strpos('http://',$val->video_basethumb) > 0 ? '' : base_url();
                $dataset[$key]->video_basethumb = $base_url.$val->video_basethumb;
                $filename = end(explode('/',$val->video_basethumb));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().THUMB_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().THUMB_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().THUMB_LARGE_PATH.$filename));
                
            }
            $dataset[$key]->duration = $this->time_from_seconds($val->duration);
        }
        
        $response = array('tr'=>$dataset_count,'cp'=>$this->start + 1,'limit'=>$this->limit,'result'=>$dataset);
        $this->response($response);
    }
    
    function page_get(){
        $qString = $this->get();
        $response = array();
        if(isset($qString['title']) && $qString['title'] != ''){
            $query = sprintf('select * from pages p where p.page_title = "%s" and user_id = %d ',$qString['title'],$this->app->id);
            $dataset = $this->db->query($query)->result();
            if(count($dataset) > 0){
                $tmp = reset($dataset);
                $response['result'] = $tmp->page_description;
            }else{
                $response['error'][] = 'Invalid page Id';
            }
        }else{
            $response['error'][] = 'Invalid page Id';
        }
        $this->response($response);
    }
    
    function webtv_get(){
        $qString = $this->get();
        $response = array();
        $query = sprintf('select p.id,p.name,p.description,p.status,p.start_date,p.end_date
                         from playlists p
                         where p.uid = %d and p.status = "0" ',$this->app->id);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $response[] = $val;
        }
        $this->response($response);
    }
    
    function webtvsave_post(){
        $data = array();
        $response = array();
        if(isset($_POST['id']) && $_POST['id'] != ''){
            $data['url'] = isset($_POST['url']) ? $_POST['url'] : '';
            $data['status'] = isset($_POST['status']) ? $_POST['status'] : "0";
            $this->db->where('id', $_POST['id']);
            $this->db->update('playlists',$data);
            $response['result'] = 'data saved successfully';
        }else{
            $response['error'][] = 'Invalid Playlist Id';
        }
        $this->response($response);
    }
    
    function webtvdetail_get(){
        $qString = $this->get();
        $response = array();
        
        if(isset($qString['k']) && $qString['k'] != ''){
            $query =  sprintf( 'select 
                            c.id,
                            pv.playlist_id,
                            c.title,
                            c.description,
                            c.uid as `creater_id`,
                            cat.id as `category_id`,
                            cat.category as `category_name`,
                            if(v.views > 0,v.views,0) as `views`,
                            c.feature_video as featured,
                            v.duration as `duration`,
                            cfile.name as `filename`,
                            cfile.absolute_path as `video_basepath`,
                            vtfile.relative_path as `video_basethumb`,
                            pe.start_date,
                            pe.end_date,
                            c.created
                            from contents c
                            left join categories cat on cat.id = c.category
                            left join playlist_video pv on pv.content_id = c.id
                            left join playlist_epg pe on pe.content_id = pv.content_id
                            left join videos v on v.content_id = c.id
                            left join files cfile on cfile.id = v.file_id
                            left join video_thumbnails vt on vt.content_id = c.id
                            left join files vtfile on vtfile.id = vt.file_id
                            where c.uid = %d AND pv.playlist_id = %d AND pv.status != 0 order by pv.index ',$this->app->id,$qString['k']);
        
        
            $dataset = $this->db->query($query)->result();
            foreach($dataset as $key=>$val){
                $response[] = $val;
            }    
        }else{
            $response['error'] = 'Invalid Key';    
        }
        
        $this->response($response);
    }
}
