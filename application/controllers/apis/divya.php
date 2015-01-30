<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Divya extends Apis{
    
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
    
    function panchang_get(){
        $qString = $this->get();
        $response = array();
        $query = sprintf('select p.date,p.month,p.pakshya,p.tithi,p.sunrise,p.sunset,p.rahukal from panchang p 
                         where p.u_id = %d and p.status = 1 and p.date = "%s" ',$this->app->id,date('Y-m-d'));
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $response[] = $val;
        }
        
        $result = array('status'=>count($response) > 0 ? 1 : 0,'result'=>$response);
        $this->response($result);
    }
    
    function livestream_get(){
        $qString = $this->get();
        $response = array();
        $query = sprintf('select o.value as livestream from options o 
                         where o.user_id = %d and o.key = "livestream" ',$this->app->id);
        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            $response['livestream'] = json_decode($val->livestream);
            
        }
        
        $result = array('status'=>count($response) > 0 ? 1 : 0,'result'=>$response);
        $this->response($result);
    }
    
    function category_get(){
        $qString = $this->get();
        $condition = '';
        
        if(isset($qString['k']))
        switch($qString['k']){
            case 'type' :
                    $condition.= sprintf(' AND c.type = "%s" ',$qString['val']);
                break;
        }        
        
        $total_query = sprintf('select count(id) as tot from categories c where u_id = %d %s ',$this->app->id,$condition);
        $dataset_count = $this->db->query($total_query)->result();
        $dataset_count = isset($dataset_count[0]->tot) ? $dataset_count[0]->tot : 0;
        
        $query = sprintf('select
                            c.id,c.category,c.parent_id,
                            c.description,c.color,
                            sum(if(contents.id > 0,1,0)) as total,
                            f.relative_path
                            from categories c
                            left join contents on contents.category = c.id
                            left join files f on f.id = c.file_id
                            where c.u_id  = "%d" %s group by c.id ',$this->app->id,$condition);

        $dataset = $this->db->query($query)->result();
        foreach($dataset as $key=>$val){
            if(file_exists($val->relative_path)){
                $base_url = strpos('http://',$val->relative_path) > 0 ? '' : base_url();
                $dataset[$key]->relative_path = $base_url.$val->relative_path;
                $filename = end(explode('/',$val->relative_path));
                $dataset[$key]->thumbs = array('small'=>sprintf('%s',base_url().CATEGORY_SMALL_PATH.$filename),
                                         'medium'=>sprintf('%s',base_url().CATEGORY_MEDIUM_PATH.$filename),
                                         'large'=>sprintf('%s',base_url().CATEGORY_LARGE_PATH.$filename));
                
            }
        }
        $response = array('status'=>$dataset_count > 0 ? 1 : 0,'result'=>$dataset);
        $this->response($response);
    }
    
    
    function mastersearch_get($data){
        $qString = $data;
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
                        $response = array('status'=>count($temp) > 0 ? 1 : 0,'result'=>$temp);
                    }else{
                        $response = array('error' => 'Content Id Not valid');
                    }
                    $this->response($response);
                    exit;
            break;
            default:
                    $response = array('status'=>0,'error' => 'No key found');
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
        
        $response = array('status'=>$dataset_count > 0 ? 1 : 0,'tr'=>$dataset_count,'cp'=>$this->start + 1,'limit'=>$this->limit,'result'=>$dataset);
        $this->response($response);
    }
    
    
    function search_get(){
        $qString = $this->get();
        $response = array();
        $qString['k'] = isset($qString['k']) ? $qString['k'] : 'video';
        $title = isset($qString['val']) && $qString['val'] != '' ? $qString['val'] : 0;
        switch($qString['k']){
            case 'video' :
                    $data = array('k'=>'title','val'=>$title);
                    $tmp = $this->mastersearch_get($data);
                    $response[] = json_decode($tmp);
                break;
            case 'comments' :
                    $data = array('k'=>'comments','val'=>$qString['val']);
                    $tmp = $this->mastersearch_get($data);
                    $response[] = json_decode($tmp);
                break;
            case 'related' :
                    $data = array('k'=>'related','val'=>$qString['val']);
                    $tmp = $this->mastersearch_get($data);
                    $response[] = json_decode($tmp);
                break;
            case 'audio' :
                    $query = sprintf('select * from audio a where a.title like "%%%s%%" ',$title);
                    $dataset = $this->db->query($query)->result();
                    foreach($dataset as $key=>$val){
                        $response[] = array('title'=>$val->title,'file_path'=>$val->file_path);
                    }
                break;
        }
        
        $result = array('status'=>count($response) > 0 ? 1 : 0,'result'=>$response);
        $this->response($result);
    }
    
    function content_get(){
        $qString = $this->get();
        $response = array();
        $qString['k'] = isset($qString['k']) ? $qString['k'] : 'video';
        $category = isset($qString['val']) && $qString['val'] > 0 ? $qString['val'] : 0;
        switch($qString['k']){
            case 'video' :
                    $data = array('k'=>'category','val'=>$category);
                    $tmp = $this->mastersearch_get($data);
                    $response[] = json_decode($tmp);
                break;
            case 'audio' :
                    $query = sprintf('select * from audio a where a.category_id = %d ',$category);
                    $dataset = $this->db->query($query)->result();
                    foreach($dataset as $key=>$val){
                        $response[] = array('title'=>$val->title,'file_path'=>$val->file_path);
                    }
                break;
        }
        
        $result = array('status'=>count($response) > 0 ? 1 : 0,'result'=>$response);
        $this->response($result);
    }
    
    /*** Liked Post ***/
    function like_post(){
        
        $response = array();
        $validation = array('content_id');
        
        if(isset($this->user->id) && $this->user->id > 0){
            $data = $this->array_cleanup(array('content_id'=>'','user_id'=>$this->user->id,'like'=>0),$this->post());
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            if(!isset($response['error'])){
                $query = sprintf('select count(*) as tot from user_favlikes where content_id = %d and user_id = %d',$data['content_id'],$this->user->id);
                $total = reset($this->db->query($query)->result());
                if($total->tot > 0){
                    //update
                    $this->db->where('user_id',$this->user->id);
                    $this->db->where('content_id',$data['content_id']);
                    if($this->db->update('user_favlikes', $data)){
                        
                        //get total likes
                        $query = sprintf('select SUM(uf.like) as tot from user_favlikes uf where uf.content_id = %d ',$data['content_id']);
                        $total = reset($this->db->query($query)->result());
                        $total = isset($total->tot) && $total->tot > 0 ? $total->tot : 0;
                        $response = array('status'=>1,'result' => array('total'=>$total,'msg'=>sprintf("Like Successfully updated")));    
                    }
                }else{
                    //insert
                    if($this->db->insert('user_favlikes',$data)){
                        //get total likes
                        $query = sprintf('select SUM(uf.like) as tot from user_favlikes uf where uf.content_id = %d ',$data['content_id']);
                        $total = reset($this->db->query($query)->result());
                        $total = isset($total->tot) && $total->tot > 0 ? $total->tot : 0;
                        $response = array('code'=>true,'result' => array('total'=>$total,'msg'=>sprintf("Like Successfully inserted")));    
                    }    
                }
            }
        }else{
            $response['error'][] = 'Invalid Request';
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    function comment_post(){
        
        $response = array();
        $validation = array('content_id','comment');
        
        if(isset($this->user->id) && $this->user->id > 0){
            $data = $this->array_cleanup(array('user_id'=>$this->user->id,'content_id'=>'','comment'=>'',
                                  'created_date'=>date("Y-m-d H:i:s"),'updated_date'=>date("Y-m-d H:i:s"),
                                  'moderator_id'=>$this->user->id,'user_ip'=>$_SERVER['REMOTE_ADDR'],
                                  'approved'=>'YES','status'=>'active'),$this->post());
            
            //check validation
            foreach($validation as $key=>$val){
                if(empty($data[$val])){
                    $response['error'][] = sprintf('%s field is required',ucwords($val));
                }    
            }
            
            //check content id is exist or not
            if(!isset($response['error'])){
                $query = sprintf('select count(*) as tot from contents where id = %d',$data['content_id']);
                $total = reset($this->db->query($query)->result());
                if($total->tot > 0){
                    if($this->db->insert('comment',$data)){
                        $response = array('status'=>1,'result' => sprintf("Comment Successfully inserted")); 
                    }
                }else{
                    $response['error'][] = sprintf('No content found on this %d',$data['content_id']);
                }
            }
            
        }else{
            $response['error'][] = 'Invalid Request';    
        }
        
        $response['status'] = isset($response['error']) ? 0 : 1 ;
        $this->response($response);
        exit;
    }
    
    
    /*** User registration ***/
    function register_post(){
        $user = array_merge(array('first_name'=>'','last_name'=>'','gender'=>'','email'=>'','password'=>'123456',
                                  'contact_no'=>'','image'=>'','image_type'=>'','status'=>'inactive',
                                  'language'=>'english','role_id'=>'NULL','token'=>md5(uniqid()),'owner_id'=>$this->app->id,
                                  'created'=>date("Y-m-d H:i:s"),'modified'=>date("Y-m-d H:i:s")),$this->post());
        
        
        
        $validation = array('first_name','gender');
        $response = array();
        $response['status'] = 0;
        
        //check validation
        foreach($validation as $key=>$val){
            if(empty($user[$val])){
                $response['error'][] = sprintf('%s field is required',ucwords($val));
            }    
        }
        
        if(empty($user['contact_no']) && empty($user['email'])){
            $response['error'][] = sprintf('Email or Contact No. : One field is required');
        } 
        
        if(!isset($response['error'])){
            //check user already register with US
            $condition  = empty($user['email']) ? sprintf(' c.contact_no = "%s" ',$user['contact_no']) : sprintf(' c.email = "%s" ',$user['email']);
            $query = sprintf('select * from customers c where %s and c.owner_id = %d',$condition,$this->app->id);
            $dataset = $this->db->query($query)->result();
            if(count($dataset) > 0){
                $user = reset($dataset);
                
                //Create/update token in api_token table
                $query = sprintf('select * from api_token where user_id = %d ',$user->id);
                $dataset = $this->db->query($query)->result();
                $token = md5(uniqid());
                if(count($dataset) <= 0){
                    //insert
                    $query = sprintf('insert into api_token values(null,"%s",%d,now(),now())',$token,$user->id);
                    $this->db->query($query);
                }else{
                    //update
                    $query = sprintf('update api_token set token = "%s",created_time = now(), hit_time = now() where user_id = %d',$token,$user->id);
                    $this->db->query($query);
                }
                $response['status'] = 1;
                $response['token'] = $token;
            }else{
                
                //Insert new user
                $tmp_userpassword = $user['password'];
                $user['email'] = empty($user['email']) ? $user['contact_no'].'@gmail.com' : $user['email'] ; 
                $user['username'] = $user['email'];
                $user['status'] = 'active'; 
                $user['password'] = md5($user['password']);
                
                unset($user['image_type']);
                
                if($this->db->insert('customers',$user)){
                    
                    $user_id = $this->db->insert_id();
                    $this->db->insert('user_password',array('user_id'=>$user_id,'u_password'=>$tmp_userpassword));
                    
                    //Create/update token in api_token table
                    $query = sprintf('select * from api_token where user_id = %d ',$user_id);
                    $dataset = $this->db->query($query)->result();
                    $token = md5(uniqid());
                    if(count($dataset) <= 0){
                        //insert
                        $query = sprintf('insert into api_token values(null,"%s",%d,now(),now())',$token,$user_id);
                        $this->db->query($query);
                    }else{
                        //update
                        $query = sprintf('update api_token set token = "%s",created_time = now(), hit_time = now() where user_id = %d',$token,$user_id);
                        $this->db->query($query);
                    }
                    $response['status'] = 1;
                    $response['token'] = $token;
                    
                }
            }
        }
        $this->response($response);
        exit;
    }
    
}