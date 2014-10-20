<?php defined('BASEPATH') OR exit('No direct script access allowed');
//ini_set('display_errors',1);
/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Video extends REST_Controller
{       
   function __construct()
   {
       parent::__construct();	
       $this->load->model('api/Video_model');
       $this->load->helper('url');  
       
       //-- validate token --//
       $token = $this->get('token');
       $this->validateToken($token);
       
       //--paging limit --//
          $this->param =  $this->paging($this->get('p'));
   }   
   
    public function category_get()
    {
       $result = $this->Video_model->categorylist();
       /*array_walk ( $result, function (&$key) {           
           $key->thumbnail_path = base_url().$key->thumbnail_path;           
       } ); */
     /*  if($result){
         $categories = $this->Video_model->recursiveCategory($result);
       }*/
       $i=0;
       foreach($result as $row){
           $thumbArray = array();
           $catids = explode(',',$row->catids);
           $thumbnails = $this->Video_model->getLatestImage($catids);     
           $thumbArray['small']='';
           $thumbArray['medium']='';
           $thumbArray['large']='';
                   
           if(!in_array('23',$catids)){ //-- to be deleted temporary for channels category --//
           //--- small, medium, large images path --//
           if(isset($thumbnails)){
            foreach($thumbnails as $data){
                //$thumbArray[$data['type']] = $data['image_path'];
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$data['image_path'];
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$data['image_path'];
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$data['image_path'];
            }
           }
           }else{ //-- to be deleted temporary for channels category --//
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$row->thumbnail;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$row->thumbnail;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$row->thumbnail;
           }
           //-- final array for category ---//
           $finalResult[$i]['id'] = $row->id;
           $finalResult[$i]['category'] = $row->category;
           $finalResult[$i]['color'] = $row->color;
           $finalResult[$i]['image'] = $thumbArray;   
           //---------//
           $i++;
       }
        //echo '<pre>';print_r($finalResult);
      
        if(isset($finalResult))
        {
            $this->response(array('code'=>1,'result'=>$finalResult), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function list_get()
    {      
       $id=$this->get('id');
       $p = $this->get('p');       
       $device = $this->get('device');
       //--paging limit --//
       /*if($p > 1){
           $param['limit'] = PER_PAGE * $p;
           $param['offset'] = ($p-1) * PER_PAGE;
       }else{
           $param['limit'] = PER_PAGE;
           $param['offset'] = 0;
       }*/
       
       //-- get subcategory array --//
       $subcats = $this->Video_model->subcategory($id);
       
       if($subcats->catids){
            $ids = $id.','.$subcats->catids;
            $ids = explode(',',$ids);
       }else
           $ids = $id;
       
       
       //print_r($ids);
       //--------------------//
       $result = $this->Video_model->videolist($ids,$device,$this->param);
      // $total_record = $this->Video_model->videolist($ids,$device);
       array_walk ( $result, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);                
                //-- get rating --//
                $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                if($key->thumbnail_path !=''){   
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
                }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
              
       //-------------------------------//
       
        if($result)
        {           
            //$result['total_record'] = $total_record[0]->total;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function featured_get()
    {       
       $p = $this->get('p');       
       $device = $this->get('device');
       
       //-----get featured video---------------//
       $result = $this->Video_model->featuredvideo($device,$this->param);
     //  $total_record = $this->Video_model->featuredvideo($device);
       array_walk ( $result, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);
                //-- get rating --//
                $rating =  $this->Video_model->getAverageRating($key->content_id);
                 //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
               if($key->thumbnail_path !=''){    
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
               }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$device;
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
       
        if($result)
        {           
           // $result['total_record'] = $total_record[0]->total;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function all_get()
    {       
       $p = $this->get('p');       
       $device = $this->get('device');
       
       //-----get featured video---------------//
       $result = $this->Video_model->allvideo($device,$this->param);
       //$total_record = $this->Video_model->allvideo($device);
       //print_r($total_record);
       array_walk ( $result, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);                
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                    $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
               if($key->thumbnail_path !='') {
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
               }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
       
        if($result)
        {           
           // $result['total_record'] = $total_record[0]->total;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function search_get()
    {
       $data['keywords'] = $this->get('keywords');
       $id = $this->get('id');
       $data['device'] = '';
       if($this->get('device')!=''){
           $data['device'] = $this->get('device');
       }
       //$data['startdate'] = ($this->post('startdate')!='' ? date('Y-m-d',strtotime($this->post('startdate'))):'');
       //$data['enddate'] = ($this->post('enddate')!='' ? date('Y-m-d',strtotime($this->post('enddate'))):'');

        
        //-- get subcategory array --//
       if($id){
            $subcats = $this->Video_model->subcategory($id);

            if($subcats->catids){
                 $ids = $id.','.$subcats->catids;
                 $ids = explode(',',$ids);
            }else
                $ids = $id;
           
       $data['category'] = $ids;
       }
       $result = $this->Video_model->searchvideo($data,$this->param);
       //$total_record = $this->Video_model->searchvideo($data);
        if($result)
        {
            array_walk ( $result, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                    $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                if($key->thumbnail_path != ''){
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
               }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$data['device'];
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
            //$result['total_record'] = $total_record[0]->total;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function detail_get()
    {
       $id = $this->get('id');
       $device = $this->get('device'); 
       $result = $this->Video_model->videodetails($id);
       //$result->video_path = base_url().$result->video_path;       
        if($result)
        {
            //-- get total likes --//
            $likes = $this->Video_model->like_count($result->content_id);
                
            //-- get rating --//
            $rating =  $this->Video_model->getAverageRating($result->content_id);
            //-- video duration --//
                $duration = $this->time_from_seconds($result->duration);
                $result->duration = $duration;
	//-- get favorite --//
		if($result->favorite >0)
			 $result->favorite =1;
		else
			 $result->favorite =0;	
                
            $result->likes = $likes;
            $result->rating = $rating;
            $result->url = base_url().'details?id='.$result->content_id.'&device='.$this->get('device');
           if($result->thumbnail_path !=''){
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$result->thumbnail_path;
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$result->thumbnail_path;
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$result->thumbnail_path;   
             }else{
                $thumbArray['small']='';
                $thumbArray['medium']='';
                $thumbArray['large']='';
               }
            $result->image = $thumbArray;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function related_get()
    {
        $arrInput = $this->get();

       // $type =  $this->get('type');
       // $id = $this->get('category_id');
        $result = $this->Video_model->relatedvideo($arrInput,$this->param);
          
        if($result){
        array_walk ( $result, function (&$key) { 
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);                
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);
                 //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                if($key->thumbnail_path){
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                $thumbArray['small']='';
                $thumbArray['medium']='';
                $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = round($rating);
           $key->image = $thumbArray;
            } );
            
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function latest_get()
    {
      
       $data = $this->get();
       $data['limit'] =  ($this->get('l') !='' ? $this->get('l'):10);
       $result = $this->Video_model->videolatest($data);
  
      if($result){
         array_walk ( $result, function (&$key) { 
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);                
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);
                 //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                if($key->thumbnail_path){
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                $thumbArray['small']='';
                $thumbArray['medium']='';
                $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = round($rating);
           $key->image = $thumbArray;
            } );
            
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }      
       
    }
    
    public function popular_get()
    {
        $arrInput = $this->get();
        $arrInput['limit'] =  ($this->get('l') !='' ? $this->get('l'):10);
       // $type =  $this->get('type');
       // $id = $this->get('category_id');
        $result = $this->Video_model->popularvideo($arrInput,$this->param);
          
        if($result){
        array_walk ( $result, function (&$key) { 
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);                
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);
                 //-- video duration --//
                $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
                
                if($key->thumbnail_path){
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                $thumbArray['small']='';
                $thumbArray['medium']='';
                $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = round($rating);
           $key->image = $thumbArray;
            } );
            
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
    
    public function livetv_get()
    {
        $device = $this->get('device');
        $stream = array('ios'=>'http://54.255.176.172:1935/live/smil:mystream.smil/playlist.m3u8',
            'android'=>'http://54.255.176.172:1935/live/smil:mystream.smil/playlist.m3u8',
            'windows'=>'http://54.255.176.172:1935/live/smil:mystream.smil/Manifest');
        
        if(array_key_exists($device,$stream))
        {
            $result = array('url'=>array('Wi-fi'=>$stream[$device],'_3G'=>$stream[$device],'_2G'=>$stream[$device]));
            $this->response($result, 200); // 200 being the HTTP response code
        }else{
            $this->response(array('error' => 'No record found'), 404);
        }        
    }    
    
    public function send_post()
    {
        var_dump($this->request->body);
    }

	public function send_put()
	{
		var_dump($this->put('foo'));
	}
        
         #### function for likes #########
         function like_post(){
            //print_r($this->get());
            $data = $this->post();  
               if(isset($data['content_id']) && $data['content_id'] != ''){
                  $content_id = $data['content_id'];
                  $con = $this->Video_model->check_content($content_id);
                  if($con){
                     if(isset($data['user_id']) && $data['user_id'] != ''){
                        $user_id = $data['user_id'];
                        $user = $this->Video_model->check_user($user_id);
                           if($user != '0'){
                           if(isset($data['like']) && $data['like'] == '1'){  
                              $likes = $this->Video_model->check_like($data);
                              if($likes){
                                 $total = $this->Video_model->like_count($data['content_id']);
                                 $this->response(array('code'=>0,'error'=>'You have already liked', 'result'=> $total), 404);
                              }else{
                                 $this->Video_model->add_like($data);
                                 $total = $this->Video_model->like_count($data['content_id']);
                                 $this->response(array('code'=>1,'result'=>$total), 200);
                              }
                           }else{
                              if(isset($data['like']) && $data['like'] == '0'){
                                 $likes = $this->Video_model->check_like($data);
                                 if(!$likes){
                                    $total = $this->Video_model->like_count($data['content_id']);
                                    $this->response(array('code'=>0,'error' => 'You dont Have like this', 'total_like'=>$total), 404);
                                 }else{
                                    $this->Video_model->delete_like($data);
                                    $total = $this->Video_model->like_count($data['content_id']);
                                    $this->response(array('code'=>1,'result'=>$total), 200);
                                 }
                              }else{
                                 $this->response(array('code'=>0,'error' => 'Invalid Request'), 404);
                              }
                           }
                        }else{
                           $this->response(array('code'=>0,'error' => 'Invalid User'), 404);
                        }
                     }else{
                     $this->response(array('code'=>0,'error' => 'user_id Not Found'), 404);
                     }
                  }else{
                     $this->response(array('code'=>0,'error' => 'content_id Do not exist'), 404);
                  }
               }else{
                  $this->response(array('code'=>0,'error' => 'content_id Not Found'), 404);
               }
         }
      
         function likeslist_get()
         {
             $category_id = $this->get('id');
             $user_id = $this->get('user_id');
             $device = $this->get('device');
       
        //-----get featured video---------------//
        $result = $this->Video_model->getlikesvideo($category_id,$user_id,$device,$this->param);
        
        array_walk ( $result, function (&$key) { 
                 //-- get total likes --//
                 $likes = $this->Video_model->like_count($key->content_id);
                 //-- get rating --//
                 $rating =  $this->Video_model->getAverageRating($key->content_id);
                  //-- video duration --//
                 $duration = $this->time_from_seconds($key->duration);
                 $key->duration = $duration;

                if($key->thumbnail_path !=''){    
                $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
                $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
                $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
                }else{
                     $thumbArray['small']='';
                     $thumbArray['medium']='';
                     $thumbArray['large']='';
                }
            $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
            $key->likes = $likes;
            $key->rating = $rating;
            $key->image = $thumbArray;          
                 } );

            if($result)
            {           
               // $result['total_record'] = $total_record[0]->total;
                $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
            }else{
                $this->response(array('code'=>0,'error' => 'No record found'), 404);
            }
        }
         
       /***** function used for rating section starts ******/  
      function rating_post(){
        $content_id = $this->post('content_id');
		$user_id = $this->post('user_id');
		$rating = $this->post('rating');
        if($content_id != '' && $user_id != '' && $rating != ''){
			$ratingExists = $this->Video_model->checkRating($content_id, $user_id);
			if(!$ratingExists) {
				$post['content_id'] = $content_id;
				$post['uid'] = $user_id;
				$post['rating'] = $rating;
				$result = $this->Video_model->insertRating($post);
				$this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
			} else { 
				$this->response(array('code'=>0,'error' => 'You have already rated this video'), 404);
			}
        } else {
            $this->response(array('error' => 'No record found'), 404);
        }         
      }	
	/***** function used for rating section ends ******/
      
      /***** function used for video view section starts ******/
       function view_get(){
         $content_id = $this->get('content_id');
         if($content_id != ''){
               $result = $this->Video_model->updateView($content_id);
               $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
         } else {
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
         }
       }
        /***** function used for video view  section ends ******/
       
        /***** function used for video view  section ends ******/
      function addcomment_post(){
         $data = $this->post();  
         if(isset($data['cid']) && $data['cid'] != ''){
            $content_id = $data['cid'];
            $con = $this->Video_model->check_content($content_id);
            if($con){
               if(isset($data['uid']) && $data['uid'] != ''){
                  $user_id = $data['uid'];
                  $user = $this->Video_model->check_user($user_id);
                  
                  if($user != '0'){
                     if(isset($data['comment']) && $data['comment'] != ''){
                        $this->Video_model->add_comment($data);
                        $this->response(array('code'=>1,'result'=>'Comment added successfully.'), 200);
                     }else{
                        $this->response(array('code'=>0,'error' => 'Comment blank'), 404);  
                     }
                  }else{
                     $this->response(array('code'=>0,'error' => 'Invalid User'), 404);
                  }
               }else{
                  $this->response(array('code'=>0,'error' => 'user Not Found'), 404);
               }
            }else{
               $this->response(array('code'=>0,'error' => 'video Do not exist'), 404);
            }
         }else{
            $this->response(array('code'=>0,'error' => 'content_id Not Found'), 404);
         }
      }
      
       ### function for get comment #####
      function comments_get(){
         $data = $this->get();
         $p=$data['p'];
          //--paging limit --//
            if($p > 1){
                $param['limit'] = PER_PAGE * $p;
                $param['offset'] = ($p-1) * PER_PAGE;
            }else{
                $param['limit'] = PER_PAGE;
                $param['offset'] = 0;
            }
       
         if(isset($data['id']) && $data['id'] != ""){
            $result = $this->Video_model->comments($data['id'],$param);
            //$total_record =$this->Video_model->comments($data['id']);
            
            if(count($result) == '0'){                
               $this->response(array('code'=>0,'error' => 'No comment on this content'), 404); 
            }
            //-- make full path for image --//
                    array_walk ( $result, function (&$key) { 
                        //--get times ago from timestamp --//
                            $key->comment_date = $this->timeago_from_timestamp($key->comment_date);
                        $key->image = base_url().PROFILEPIC_PATH.$key->image;
                        //$key->url = base_url().'index.php/details?id='.$key->content_id;
                    } );                  
           // $result['total_comment'] = $total_record[0]->total;            
            $this->response(array('code'=>1,'result'=>$result), 200);
         }else{
           $this->response(array('code'=>0,'error' => 'Content Missing'), 404); 
         }
      }
      
      #### function for likes #########
         function addfavorite_post(){            
            $data = $this->post();  
               if(isset($data['content_id']) && $data['content_id'] != ''){
                  $content_id = $data['content_id'];
                  $con = $this->Video_model->check_content($content_id);
                  if($con){
                     if(isset($data['user_id']) && $data['user_id'] != ''){
                        $user_id = $data['user_id'];
                        $user = $this->Video_model->check_user($user_id);
                           if($user != '0'){
                           if(isset($data['favorite']) && $data['favorite'] == '1'){  
                              $favorite = $this->Video_model->check_favorite($data);
                              if($favorite){                                
                                 $this->response(array('code'=>0,'error'=>'This video is already in your favorite list'), 404);
                              }else{
                                 $this->Video_model->add_favorite($data);
                                 //$result = $this->Video_model->favorite_list($data['content_id']);
                                 $this->response(array('code'=>1), 200);
                              }
                           }else{
                              if(isset($data['favorite']) && $data['favorite'] == '0'){
                                 $favorite = $this->Video_model->check_favorite($data);
                                 if(!$favorite){                                    
                                    $this->response(array('code'=>0,'error' => 'You dont any favorite record to remove'), 404);
                                 }else{
                                    $this->Video_model->delete_favorite($data);
                                    //$result = $this->Video_model->favorite_list($data['content_id']);
                                    $this->response(array('code'=>1), 200);
                                 }
                              }else{
                                 $this->response(array('code'=>0,'error' => 'Invalid Request'), 404);
                              }
                           }
                        }else{
                           $this->response(array('code'=>0,'error' => 'Invalid User'), 404);
                        }
                     }else{
                     $this->response(array('code'=>0,'error' => 'Invalid User'), 404);
                     }
                  }else{
                     $this->response(array('code'=>0,'error' => 'Content Do not exist'), 404);
                  }
               }else{
                  $this->response(array('code'=>0,'error' => 'Content Not Found'), 404);
               }
         }
         
         function favoritelist_get()
         {
             $data = $this->get();             
             $result = $this->Video_model->favorite_list($data,$this->param);
             
             array_walk ( $result, function (&$key) { 
                //-- get total likes --//
                $likes = $this->Video_model->like_count($key->content_id);                
                //-- get rating --//
                   $rating =  $this->Video_model->getAverageRating($key->content_id);
                //-- video duration --//
                    $duration = $this->time_from_seconds($key->duration);
                $key->duration = $duration;
               if($key->thumbnail_path !='') {
               $thumbArray['small'] = base_url().THUMB_SMALL_PATH.$key->thumbnail_path;
               $thumbArray['medium'] = base_url().THUMB_MEDIUM_PATH.$key->thumbnail_path;
               $thumbArray['large'] = base_url().THUMB_LARGE_PATH.$key->thumbnail_path;
               }else{
                    $thumbArray['small']='';
                    $thumbArray['medium']='';
                    $thumbArray['large']='';
               }
           $key->url = base_url().'index.php/details?id='.$key->content_id.'&device='.$this->get('device');
           $key->likes = $likes;
           $key->rating = $rating;
           $key->image = $thumbArray;          
            } );
       
        if($result)
        {           
           // $result['total_record'] = $total_record[0]->total;
            $this->response(array('code'=>1,'result'=>$result), 200); // 200 being the HTTP response code
        }else{
            $this->response(array('code'=>0,'error' => 'No record found'), 404);
        }
    }
}
