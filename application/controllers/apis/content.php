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
                            cfile.name as `video_filename`,
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
        $query = sprintf('select p.id,p.name,p.description,p.publish,p.status,p.start_date,p.end_date
                         from playlists p
                         left join channels c on c.id = p.channel_id
                         where c.type = "Linear" ');
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
                            c.feature_video as featured,
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
                            where pv.playlist_id = %d AND pv.status != 0 AND cfile.absolute_path != "" order by pv.index ',$qString['k']);
        
        
            $dataset = $this->db->query($query)->result();
            
            foreach($dataset as $key=>$val){
                /*
                $bpath = explode('.',$val->video_basepath);
                
                $tmp = array('2g'=>$bpath[0].'_2g.'.$bpath[1],
                                     '3g'=>$bpath[0].'_3g.'.$bpath[1],
                                     'wifi'=>$bpath[0].'_wifi.'.$bpath[1]);
                
                $val->videos = array('2g'=>file_exists($tmp['2g']) ? $tmp['2g'] : '',
                                     '3g'=>file_exists($tmp['3g']) ? $tmp['3g'] : '',
                                     'wifi'=>file_exists($tmp['wifi']) ? $tmp['wifi'] : '');
                */
                $response[] = $val;
            }    
        }else{
            $response['error'] = 'Invalid Key';    
        }
        $this->response($response);
    }
    
    function channels_get(){
        $response = array();        
               
        $query = sprintf('SELECT
                         cc.id as channel_cat_id,
                         cc.category as channel_cat_name,
                         cc.color as channel_cat_color,
                         cc.index as channel_cat_index,
                         concat(cc.range_from,"-",cc.range_to) as channel_cat_range,
                         c.id as channel_id,
                         c.name as channel_name,
                         c.number as channel_number,
                         c.type as channel_type,
                         p.id as playlist_id,
                         p.name as playlist_name,
                         p.start_date as playlist_startdate,
                         p.end_date as playlist_enddate
                         FROM `channels` c
                            left join `channel_categories` cc on cc.id = c.category_id
                            left join playlists p on p.channel_id = c.id 
                            where c.category_id <> 0 and c.status = 1 and c.uid = %d order by cc.range_from asc ',$this->app->id);
        
        $dataset = $this->db->query($query)->result();
        $response['data'] = $this->getFormatData($dataset,'category',0);
               
      // echo '<pre>'; print_r($dataset);die;
        //-----//
        
        //$ads = $this->get_data('http://182.18.165.43/multitvfinal/api/ads/list');
        //$ads = json_decode($ads);
        
        $ads = array('https://www.youtube.com/watch?v=443Vy3I0gJs',
                     'https://www.youtube.com/watch?v=S2nBBMbjS8w',
                     'https://www.youtube.com/watch?v=stIkdrYFgcI',
                     'https://www.youtube.com/watch?v=k-STkFCCrus',
                     'https://www.youtube.com/watch?v=9URY-zGV4N8',
                     'https://www.youtube.com/watch?v=YRm9kfJFIag',
                     'https://www.youtube.com/watch?v=31kVmY3PcRY',
                     'https://www.youtube.com/watch?v=uZ9l3ZwtCFQ',
                     'https://www.youtube.com/watch?v=R0YPk05tQv0',
                     'https://www.youtube.com/watch?v=pQK69dgAjPQ',
                     'https://www.youtube.com/watch?v=nRpH73tkITY',
                     'https://www.youtube.com/watch?v=JfKhtyTY12Y',
                     'https://www.youtube.com/watch?v=ks9vBiL4SkA',
                     'https://www.youtube.com/watch?v=WBN1vY9gMvU',
                     'https://www.youtube.com/watch?v=sq1MMrTUD6c',
                     'https://www.youtube.com/watch?v=qNc5KS9Lq_c',
                     'https://www.youtube.com/watch?v=s6_WQDqd9pw',
                     'https://www.youtube.com/watch?v=s6_WQDqd9pw',
                     'https://www.youtube.com/watch?v=unM41s4UoSA',
                     'https://www.youtube.com/watch?v=AFtUpMTs4vI',
                     'https://www.youtube.com/watch?v=aOM_Q4uPn5g',
                     'https://www.youtube.com/watch?v=njqEhaEZYw8',
                     'https://www.youtube.com/watch?v=E8yNly2Bui0',
                     'https://www.youtube.com/watch?v=NpoBwFD1D5k',
                     'https://www.youtube.com/watch?v=FHgRZx4uAOM');
        
        shuffle($ads);
        
        
        $counter = 0;
        foreach($response['data'] as $k1=>$v1){
            foreach($v1['chList'] as $k2=>$v2){
                switch(strtolower($v2['chTyp'])){
                    case 'linear' :
                        if(isset($v2['chCtnt'])){
                            array_walk($v2['chCtnt'],function(&$data) use ($ads){
                                $data['PCtnt'] = $this->getPlaylistDetail($data['PId'],'linear');
                            });
                        }
                        break;
                    case 'loop' :
                        if(isset($v2['chCtnt'])){
                            array_walk($v2['chCtnt'],function(&$data) use ($ads){
                                $data['PCtnt'] = $this->getPlaylistDetail($data['PId'],'loop');
                                if(is_array($data['PCtnt'])){
                                    foreach($data['PCtnt'] as $key=>$val){
                                        $data['PCtnt'][$key]->ctnAd = $ads[rand(0,count($ads)-1)];
                                    }
                                }
                            });
                        }
                        break;
                    case 'youtube' :
                        
                        break;
                    case 'live' :
                        if(isset($v2['chCtnt'])){
                            $v2['chCtnt']['ctntUrl'] = $this->getLiveUrl($v2['chId']);
                            $v2['chCtnt']['chEpg'] = $this->getLivechannelEpg($v2['chId']);
                            $v2['chCtnt']['ctnAd'] = $ads[rand(0,count($ads)-1)];
                        }
                        break;
                }
                $response['data'][$k1]['chList'][$k2] = $v2;
            }
        }
        $response['vod'] = $this->getnewvod_get();
        $this->response($response);
    }
    
    function getnewvod_get(){
        $response = array();
        
        $this->db->select('c.id as catid,c.category,c.color,a.id,a.title,a.description,a.created,c1.name as thumbnail_path');
        $this->db->from('contents a');               
        $this->db->join('categories c', 'a.category = c.id', 'left');    
        $this->db->join('video_thumbnails h','h.content_id=a.id AND h.default_thumbnail = 1 ','left');
        $this->db->join('files c1', 'h.file_id = c1.id', 'left');               
        $this->db->where('a.status','1');
        $this->db->where('a.uid',$this->app->id);
        $this->db->order_by('c.id');
        $this->db->limit($this->limit);
        $query = $this->db->get();
        $data =  $query->result();
        
        $tmp = array();
        foreach($data as $key=>$val){
            $tmp[$val->catid]['CatId'] = $val->catid;
            $tmp[$val->catid]['CatName'] = $val->category;
            $tmp[$val->catid]['Counter'] = $this->limit;
            $tmp[$val->catid]['Cntnt'][] = array('title'=>$val->title,
                                            'description'=>$val->description,
                                            'thumbnail'=>$val->thumbnail_path,
                                            'url'=> base_url().'index.php/details?id='.$val->id.'&type=vod');
        }
        return array_values($tmp);
    }
    
    function getFormatData($data = array(),$type,$id){
       
        $response = array();
        switch($type){
            case 'category' :
                foreach($data as $key=>$val){
                $response[$val->channel_cat_id] = array('cNm'=>$val->channel_cat_name,
                                               'cId'=>$val->channel_cat_id,
                                               'cRange'=>$val->channel_cat_range,
                                               'cOrdr'=>$val->channel_cat_index,
                                               'cColr'=>$val->channel_cat_color,
                                               'chList'=>$this->getFormatData($data,'channel',$val->channel_cat_id));    
                }
                break;
            case 'channel' :
                foreach($data as $key=>$val){
                    if($val->channel_cat_id == $id  && $val->channel_id > 0)
                    switch($val->channel_type){
                        case 'Linear' :
                            $response[$val->channel_id] = array('chNm'=>$val->channel_name,
                                               'chId'=>$val->channel_id,
                                               'chNmbr'=>$val->channel_number,
                                               'chTyp'=>$val->channel_type,
                                               'chSTym'=>date('Y-m-d h:i:s'),
                                               'chCtnt'=>$this->getFormatData($data,'playlist',$val->channel_id));    
                            break;
                        case 'Loop' :
                            $response[$val->channel_id] = array('chNm'=>$val->channel_name,
                                               'chId'=>$val->channel_id,
                                               'chNmbr'=>$val->channel_number,
                                               'chTyp'=>$val->channel_type,
                                               'chCtnt'=>$this->getFormatData($data,'playlist',$val->channel_id));    
                            break;
                        //case 'Youtube' :
                        case 'Live' :
                            $response[$val->channel_id] = array('chNm'=>$val->channel_name,
                                               'chId'=>$val->channel_id,
                                               'chNmbr'=>$val->channel_number,
                                               'chTyp'=>$val->channel_type,
                                               'chCtnt'=>array());
                            break;
                    }
                }
                break;
            case 'playlist' :
                foreach($data as $key=>$val){
                    if($val->channel_id == $id && $val->playlist_id > 0){
                        $response[$val->playlist_id] = array('Pnm'=>$val->playlist_name,
                                                         'PId'=>$val->playlist_id,
                                                         'PStTym'=>$val->playlist_startdate,
                                                         'PEdTym'=>$val->playlist_enddate,
                                                         'PCtnt'=>array());        
                    }
                    
                }
                break;
        }
        return array_values($response);
    }
    
    function getLiveUrl($channel_id){
       /* $query = sprintf('select
                              ios,
                              android,
                              web,
                              windows,
                              youtube,
                              thumbnail_url as thumb
                              from livestream where channel_id = %d ',$channel_id);
        */
       $query = sprintf('select   web,                            
                              thumbnail_url as thumb
                              from livestream where channel_id = %d ',$channel_id);
        $dataset = $this->db->query($query)->result();
        $webUrl = $dataset[0]->web;
        $cntUrl =  base_url().'index.php/details?id='.$channel_id.'&type=live';
        //$tmp = array('ios'=> false,'android'=>false,'web'=>false,'windows'=>false,'youtube'=>false,'thumb'=>false);
        $tmp = array('ios'=> $cntUrl.'&device=ios','android'=>$cntUrl.'&device=android','web'=>$webUrl.'&device=web','windows'=>$cntUrl.'&device=ios','youtube'=>$cntUrl.'&device=youtube','thumb'=>false);
        return array_merge($tmp,(Array)reset($dataset));        
    }
    
    function getLivechannelEpg($channel_id){
        $query = sprintf('select show_title,show_time,show_thumb,show_language,show_description,show_type
                              from livechannel_epg where channel_id = %d order by show_time ',$channel_id);
        $dataset = $this->db->query($query)->result();
        if(count($dataset) > 0){
            return $dataset;
        }else{
            return array();
        }
    }
    
    function getPlaylistDetail($playlist_id,$type){
        $dataset = array();
        switch($type){
            case 'loop' :
                    $query = sprintf('select
                            c.title as `ctntNm`,
                            c.id as `ctntId`,
                            concat("http://img.youtube.com/vi/",c.content_token,"/0.jpg") as `ctnThmb`,
                            v.duration as `ctnDur`,
                            f.absolute_path as `ctntUrl`
                            from contents c
                            left join videos v on v.content_id = c.id
                            left join files f on f.id = v.file_id
                            left join playlist_video pv on pv.content_id = c.id
                            where c.type = "youtube" and pv.playlist_id = %d',$playlist_id);
                    $dataset = $this->db->query($query)->result();
                break;
            case 'linear' :
                    $query = sprintf('select * from playlists where playlists.status = "1" and playlists.publish = "1" and playlists.id = %d',$playlist_id);
                    $tmp = $this->db->query($query)->result();
                    if(isset($tmp[0]->url)){
                        $webUrlArr = json_decode($tmp[0]->url);
                        $webUrl = $webUrlArr[0]->web->wifi;
                        $cntUrl =  base_url().'index.php/details?id='.$playlist_id.'&type=linear';                        
                        $dataset = array(array('ctntUrl'=>array($cntUrl,$webUrl),'chEpg'=>$this->getEpg($playlist_id)));
                    }
                break;
        }
        return $dataset;
    }
    
    function getEpg($playlist_id){
        
        $query = sprintf('select *,TIMEDIFF(playlist_epg.end_date,playlist_epg.start_date) as diff from playlist_epg where playlist_id = %d',$playlist_id);
        $tmp = $this->db->query($query)->result();
        $response = array();
        foreach($tmp as $key=>$val){
            $response[] = array('Plst'=>$val->playlist_id,
                                'ttl'=>$val->title,
                                'EStTym'=>$val->start_date,
                                'EEdTym'=>$val->end_date,
                                'Dur'=>$val->diff);
        }
        return $response;
    }
    
    /******* Insert Data *******/
    function insertdata_get(){
        
        $row = 0;
        if (($handle = fopen("assets/tmp/links.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                if($row == 1) continue;
                
                $content_id = $playlist_id = $channel = $channel_cat = 0;
                if($data[0] != ''){
                    // Insert channel category
                    $query = sprintf('select * from channel_categories where category like "%s" ',$data[0]);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $channel_cat = $dataset[0]->id;
                    }else{
                        $tmp = array();
                        $tmp['category'] = $data[0];
                        $tmp['u_id'] = 29;
                        $tmp['created'] = date('Y-m-d h:i:s');
                        $this->db->insert('channel_categories',$tmp);
                        $channel_cat = $this->db->insert_id();
                        
                    }
                    
                }
                
                //insert channels
                if($data[1] != '' && $channel_cat > 0){
                    
                    $data[1] = trim($data[1]);
                    $query = sprintf('select * from channels where name like "%s" ',$data[1]);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $channel = $dataset[0]->id;
                    }else{
                        $tmp = array();
                        $tmp['name'] = $data[1];
                        $tmp['type'] = 'Loop';
                        $tmp['category_id'] = $channel_cat;
                        $tmp['uid'] = 29;
                        $tmp['status'] = 1;
                        $tmp['created'] = date('Y-m-d h:i:s');
                        $this->db->insert('channels',$tmp);
                        $channel = $this->db->insert_id();
                        
                    }
                }
                
                //insert playlists with timing
                if($data[2] != '' && $channel > 0){
                    
                    $data[2] = trim($data[2]);
                    $query = sprintf('select * from playlists where name like "%s" ',$data[2]);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $playlist_id = $dataset[0]->id;
                    }else{
                        $tmp = array();
                        $tmp['name'] = $data[2];
                        $tmp['channel_id'] = $channel;
                        $tmp['start_date'] = date('Y-m-d h:i:s');
                        $tmp['end_date'] = date('Y-m-d h:i:s',strtotime("+2 hours"));
                        $tmp['uid'] = 29;
                        $tmp['status'] = 0;
                        $tmp['created'] = date('Y-m-d h:i:s');
                        $this->db->insert('playlists',$tmp);
                        $playlist_id = $this->db->insert_id();
                        
                    }
                }
                
                // Insert content in content table
                if($data[4] != ''){
                    $videoUrl = trim($data[4]);
                    $youtube_id = $this->getYoutubeIdFromUrl($videoUrl);
                    $query = sprintf('select * from contents where content_token like "%s" ',$youtube_id);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        $content_id = $dataset[0]->id;
                    }else{
                        $tmp = $this->get_youtube($videoUrl);
                        if(isset($tmp['id']) && $tmp['id']!='' && count($tmp['detail']) > 0){
                            $this->load->model('videos_model');
                            $youtubeData = $tmp['detail']['entry']->{'media$group'};
                            $post['content_token'] = $tmp['id'];
                            $post['content_title'] = $tmp['detail']['entry']->{'title'}->{'$t'};
                            $post['description'] = $youtubeData->{'media$description'}->{'$t'};
                            $post['duration'] = $youtubeData->{'media$content'}[0]->duration; 
                            $post['uid'] = 29;
                            $post['created'] = date('Y-m-d');
                            $post['type'] = 'youtube';
                            $post['filename'] = $videoUrl;
                            $post['created'] = date('Y-m-d');
                            $post['relative_path'] = $videoUrl;
                            $post['absolute_path'] = $videoUrl;
                            $post['status'] = '0';
                            $post['type'] = 'youtube';
                            $post['minetype'] = "";
                            $post['info'] = base64_encode($videoUrl);
                            $content_id = $this->videos_model->_saveVideo($post);
                            
                        }else{
                            echo $tmp['id'] . ' is private or invalid Id';
                            continue;
                        }
                    }
                }
                
                //Insert data in playlist_video
                if($playlist_id > 0 && $content_id > 0){
                    $query = sprintf('select * from playlist_video where content_id = %d and playlist_id = %d ',$content_id,$playlist_id);
                    $dataset = $this->db->query($query)->result();
                    if(count($dataset) > 0){
                        
                    }else{
                        $tmp = array();
                        $tmp['playlist_id'] = $playlist_id;
                        $tmp['content_id'] = $content_id;
                        $tmp['color'] = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                        $tmp['status'] = 1;
                        $tmp['created'] = date('Y-m-d h:i:s');
                        $this->db->insert('playlist_video',$tmp);
                    }
                }
            }
            fclose($handle);
        }
        exit;
    }
    
    function getFeelingLucky($id)
    {
        //--- get user keywords --//
         $query = sprintf('select                              
                              keywords
                              from customers where id = %d ',$id);
        $dataset = $this->db->query($query)->row();
        
        if(!empty($dataset) && $dataset->keywords!=''){
        $keywords = explode(',',$dataset->keywords);       
            foreach($keywords as $val){
             $this->db->or_like('c.keywords',$val);
             $this->db->or_like('c.name',$val);
             $this->db->or_like('p.name',$val);
            }
            
            //--- get keywords content ---//
        $this->db->select('cc.id as channel_cat_id,
                         cc.category as channel_cat_name,
                         cc.color as channel_cat_color,
                         cc.index as channel_cat_index,
                         concat(cc.range_from,"-",cc.range_to) as channel_cat_range,
                         c.id as channel_id,
                         c.name as channel_name,
                         c.number as channel_number,
                         c.type as channel_type,
                         p.id as playlist_id,
                         p.name as playlist_name,
                         p.start_date as playlist_startdate,
                         p.end_date as playlist_enddate',false);
        $this->db->from('channels c');
        $this->db->join('channel_categories cc','cc.id=c.category_id','left');
        $this->db->join('playlists p','p.channel_id = c.id','left');
        $this->db->where('c.category_id <> 0');
        $this->db->where('c.status','1');
      
        $query = $this->db->get();
         //echo $this->db->last_query();die;
        return $query->result();   
        }
           
    }
    
    
    function feelinglucky_get()
    {
        $id = $this->get('id');
         //-- get user keywords --//
         $response['feelingLucky'] =array();
        if($id){
            $feelLucky = $this->getFeelingLucky($id);
            
            array_walk ( $feelLucky, function (&$key) { 
                $key->channel_cat_id = 99999;
                $key->channel_cat_name ="Feeling Lucky";
                $key->channel_cat_range ='';
            });
            //echo '<pre>';//print_r($feelLucky);die;
            if($feelLucky){
            $response['feelingLucky'] = $this->getFormatData($feelLucky,'category',0);
           // print_r($response['feelingLucky']);die;
            $counter = 0;
            foreach($response['feelingLucky'] as $k1=>$v1){
                foreach($v1['chList'] as $k2=>$v2){
                    switch(strtolower($v2['chTyp'])){
                        case 'linear' :
                            if(isset($v2['chCtnt'])){
                                array_walk($v2['chCtnt'],function(&$data) use ($ads){
                                    $data['PCtnt'] = $this->getPlaylistDetail($data['PId'],'linear');
                                });
                            }
                            break;
                        case 'loop' :
                            if(isset($v2['chCtnt'])){
                                array_walk($v2['chCtnt'],function(&$data) use ($ads){
                                    $data['PCtnt'] = $this->getPlaylistDetail($data['PId'],'loop');
                                    if(is_array($data['PCtnt'])){
                                        foreach($data['PCtnt'] as $key=>$val){
                                            $data['PCtnt'][$key]->ctnAd = $ads[rand(0,count($ads)-1)];
                                        }
                                    }
                                });
                            }
                            break;
                        case 'youtube' :
                            
                            break;
                        case 'live' :
                            if(isset($v2['chCtnt'])){
                                $v2['chCtnt']['ctntUrl'] = $this->getLiveUrl($v2['chId']);
                                $v2['chCtnt']['ctnAd'] = $ads[rand(0,count($ads)-1)];
                            }
                            break;
                    }
                    $response['feelingLucky'][$k1]['chList'][$k2] = $v2;
                }
            }
        }
        }
        $this->response($response);
        //--------------------------------//
    }
    /****** APIs for Divya TV channels **************/
    
    
    
    
    
    
    
    
    
}
