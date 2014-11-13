<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Content extends Apis{
    
    public $query = '';
    
    function __construct(){
        parent::__construct();
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
            case 'title' :
                    $condition.= isset($qString['val']) && $qString['val'] != '' ? sprintf('AND c.title like "%%%s%%" ',$qString['val']) : '';
                    $condition.= ' group by c.id ';
                break;
            case 'category' :
                    $condition.= isset($qString['val']) && $qString['val'] != '' ? sprintf('AND cat.id = %d ',$qString['val']) : '';
                    $condition.= ' group by c.id ';
                break;
            case 'recent' :
                    $condition.= ' group by c.id ';
                    $condition.= sprintf(' ORDER BY c.id DESC ');
                break;
            case 'popular' :
                    $condition.= ' group by c.id ';
                    $condition.= sprintf(' ORDER BY v.views DESC ');
                break;
            case 'liked' :
                    $condition.= ' group by c.id ';
                    $condition.= sprintf(' ORDER BY ufl.`like` DESC ');
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
    
    function featured_get(){
        $qString = $this->get();
        $total_query = sprintf('select count(id) as tot from contents where uid = %d and feature_video = 1 ',$this->app->id);
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s and c.feature_video = 1 group by c.id limit %d,%d ',$this->query,$this->start,$this->limit);
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
    
    /*************************************************
     * Likes Section
     *************************************************/
    
    function like_post(){
        $qString = $this->get();
        $response = false;
        if(isset($_POST['content_id']) && $_POST['content_id'] != ''){
            switch($_POST['value']){
                case '1' :
                    $query = sprintf('select * from user_favlikes where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $query = sprintf('update user_favlikes set `like` = 1 where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                        $this->db->query($query);    
                    }else{
                        echo $query = sprintf('insert into user_favlikes set user_id = %d, content_id = %d, `like` = 1',$this->user->id,$_POST['content_id']);
                        $this->db->query($query);    
                    }
                    $response = true;
                    break;
                default:
                    $query = sprintf('update user_favlikes set `like` = 0 where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                    $this->db->query($query);  
                    $response = true;
                    break;
            }
        } 
        $response = array('tr'=>1,'result'=>$response);
        $this->response($response);
    }
    
    /*************************************************
     * Favorite Section
     *************************************************/
    
    function favorite_post(){
        $qString = $this->get();
        $response = false;
        if(isset($_POST['content_id']) && $_POST['content_id'] != ''){
            switch($_POST['value']){
                case '1' :
                    $query = sprintf('select * from user_favlikes where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $query = sprintf('update user_favlikes set `favorite` = 1 where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                        $this->db->query($query);    
                    }else{
                        echo $query = sprintf('insert into user_favlikes set user_id = %d, content_id = %d, `favorite` = 1',$this->user->id,$_POST['content_id']);
                        $this->db->query($query);    
                    }
                    $response = true;
                    break;
                default:
                    $query = sprintf('update user_favlikes set `favorite` = 0 where content_id = %d and user_id = %d ',$_POST['content_id'],$this->user->id);
                    $this->db->query($query);  
                    $response = true;
                    break;
            }
        } 
        $response = array('tr'=>1,'result'=>$response);
        $this->response($response);
    }
    
    function favorite_get(){
        $qString = $this->get();
        
        $total_query = sprintf('select count(c.id) as tot from contents c
                               left join user_favlikes uf on uf.content_id = c.id
                               where uf.favorite = 1 and uf.user_id = %d',$this->user->id);
        
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('%s AND uf.favorite = 1 and uf.user_id = %d group by c.id limit %d,%d ',$this->query,$this->user->id,$this->start,$this->limit);
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
    
    
    /*** Comment section ****/
    function comments_get(){
        $qString = $this->get();
        $response = $dataset = array();
        if(isset($qString['cid']) && $qString['cid'] != ''){
            $query = sprintf('select
                                  c.id,
                                  c.comment,
                                  if(c.approved = "Yes",1,0)  as status,
                                  c.created_date as created,
                                  c.user_ip as ip,
                                  u.id as user_id,
                                  u.first_name,
                                  u.last_name,
                                  CONCAT(u.first_name," ",u.last_name) as username,
                                  u.gender,
                                  f.relative_path,
                                  f.absolute_path
                                  from comment c
                                  left join users u on u.id = c.user_id
                                  left join files f on f.id = u.image
                                  where content_id = %d AND c.approved = "No"
                                  group by c.id ',$qString['cid']);
            
            $dataset = $this->db->query($query)->result();
            array_walk($dataset,function(&$dataset){
                if($dataset->relative_path != ''){
                    $base_url = strpos('http://',$dataset->relative_path) > 0 ? '' : base_url();
                    $dataset->relative_path = $base_url.$dataset->relative_path;
                }
            });
        }else{
            $response['error'] = 'Invalid content Id';
        }
        $response = array('tr'=>count($dataset),'result'=>$dataset);
        $this->response($response);
        exit;
    }
    
    function comments_post(){
        $qString = $this->get();
        if(isset($_POST['content_id']) && $_POST['content_id'] != '' && isset($_POST['comment']) && $_POST['comment'] != ''){
                $query = sprintf('insert into comment set user_id = %d, content_id = %d,
                                 comment = "%s" , created_date = now(), approved = "No", status = "active" ',$this->user->id,$_POST['content_id'],$_POST['comment']);
                $this->db->query($query);
                $response['result'] = true;
        }else{
            $response['error'] = 'Fields required!!!';
        } 
        $this->response($response);
        exit;
    }
}