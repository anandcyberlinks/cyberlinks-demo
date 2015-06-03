<?php
  ini_set('display_errors',1);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Youtubevideo extends MY_Controller
{
    public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedImageExt;

	
    function __construct(){
        parent::__construct();
	$this->load->config('messages');
    $this->load->model('videos_model');
	$this->load->library('session');
	$per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
	$this->load->library('form_validation');
        $data['welcome'] = $this;
	$s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
	$this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');

    }
    function index() {
	 // echo '<pre>';print_r($_SERVER);

    $this->data['welcome'] = $this;
	if (isset($_POST['submit']) && ($_POST['submit']=='Search')) {
	  $user = $_POST['title'];
	  $videotype = $_POST['videotype'];
	  $total = 5;
	  $index=10;
	  
	  $search = array('L1PiqwoU_Dw',
'ZsQXoAmCxe8',
'iXpwcI5DpAs',
'Ezjk3CZMCtU',
'y3nQ9Rocaus',
'2J_ohBWZvOw',
'RF7TTZ0ZmT8',
'jT0bPT1n0Hc',
'Ezjk3CZMCtU',
'wAH68XmYuNs',
'vNEE1qJlZ8o',
'ekZWJiMihz0',
'4W9ct_VMdsE',
'NS9VslaoSSo',
'tgiGS6wYtfc',
'2Nx78kU__0s',
'IpYevCHWqqM',
'bRPQs7y5lUQ',
'ZN4d_KIferU',
'gZGn6-qV0Nk',
'AjdtEZZlnVk',
'Yz1zMFTJHf8',
'DeG35Wzq2kc',
'ykrPTarT6fE'
);
	  
	   
	  //-- get data using id ---//
	  for($i=0;$i<count($search);$i++){
		$youtube = sprintf('http://gdata.youtube.com/feeds/api/videos/%s?v=2&alt=json', $search[$i]);
		//-- save data ---//
		   $path = "http://globalpunjab.s3.amazonaws.com/videos/"; 			 
			$post = array();
            
			//-- hit youtube api url ---//
			$curl = curl_init($youtube);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$return = curl_exec($curl);
			curl_close($curl);
			$data['id'] = $id;
			$tmp['detail'] = (array) json_decode($return);			
			///-------------------------//
			
            $content_title = $tmp['detail']['entry']->{'title'}->{'$t'};				  
			//$filename = str_replace(array(',', '-', '%','"',':','|'), '', $content_title);
			//$filename = preg_replace('/\s+/', ' ',$filename);	
			//$filename = str_replace(" ",'_',$filename);
			$filename = $search[$i];
			
		echo '<br>'.	$path .= $filename.'.mp4';
			 
            $youtubeData = $tmp['detail']['entry']->{'media$group'};
            $post['content_token'] = $tmp['id'];
            $post['content_title'] = $tmp['detail']['entry']->{'title'}->{'$t'};
            $post['description'] = $youtubeData->{'media$description'}->{'$t'};
            $post['duration'] = $youtubeData->{'media$content'}[0]->duration; 
            $post['uid'] = $this->uid;
            $post['created'] = date('Y-m-d');
            $post['filename'] = $filename.'.mp4';
            $post['uid'] = $this->uid;
            $post['created'] = date('Y-m-d');
            $post['relative_path'] = $path;
            $post['absolute_path'] = $path;
            $post['status'] = '0';
            $post['type'] = 'mp4';
            $post['minetype'] = "video/mp4";
            $post['info'] = base64_encode($path);
           // echo "<pre>";            print_r($post); exit;
            $last_id = $this->videos_model->_saveVideo($post);
			
			
			//---save thumbnail --//
			$image_path = "http://globalpunjab.s3.amazonaws.com/images/".$filename.".jpg";
			$type = 'thumbnail';
			$fileData = array();
			$fileData['content_id'] = $last_id;
			$fileData['filename'] = $filename.'jpg';
			$fileData['type'] = 'jpg';
			$fileData['minetype'] = "image/jpg";
			$fileData['width'] = "480";
			$fileData['height'] = "360";
			$fileData['relative_path'] = $image_path;
			$fileData['absolute_path'] = $image_path;
			$fileData['status'] = '0';
			$fileData['uid'] = $this->uid;
			$data_postFile = @serialize($fileData);
			$dataFile = base64_encode($data_postFile);
			$fileData['info'] = $dataFile;
			$last_id = $this->videos_model->_saveThumb($fileData);
	  }
	  echo "upload successfully";
	  die;
	  
	 
	  //------------------------//
	  
	 // for($index=1;$index <= $total;){
			$api = file_get_contents("http://gdata.youtube.com/feeds/api/users/" . $user . "/uploads?v=2&alt=jsonc&start-index=".$index."&max-results=50");
			$datayoutube = json_decode($api);
			//echo '<pre>';print_r($datayoutube->data->items);echo '</pre>'; exit;
			$this->data['result'] = $result = $datayoutube->data->items;
			 $path = "http://globalpunjab.s3.amazonaws.com/videos/";
			$reccount = count($result);
			for($i=0; $i < $reccount; $i++) {			  
			 $filename = str_replace(array(',', '-', '%', ' '), '_', $result[$i]->title);			  
			  
			//  $filename = str_replace(" ","_",$result[$i]->title);
			 // $filename = str_replace(",",'',$filename);
			  $path .= $filename.'.mp4';
			  $post['uid'] = $this->uid;
			  $post['content_title'] = $result[$i]->title;
			  $post['duration'] = $result[$i]->duration;
			  $post['description'] = $result[$i]->description;		
			  $post['filename'] = $filename;
			  $post['type'] = 'mp4';
			  $post['created'] = date('Y-m-d');
			  $post['relative_path'] = $path;
			  $post['absolute_path'] = $path;
			  $post['status'] = '0';
			  $post['minetype'] = "video/mp4";
			  $last_id = $this->videos_model->_saveVideo($post);
			  
			$source_path = $result[$i]->thumbnail->hqDefault;
			$ext = strrchr($source_path,".");
			  //-- upload thumbnail --//
			  $this->uploadThumb($last_id,$source_path,$filename.$ext);
			}
			$index += $total;
			$total += 50;
	 // }								  
	    			
	  $this->show_view('youtubevideo', $this->data);

	} else {
	  $this->show_view('youtubevideo', $this->data);
	}
    }
    
	function uploadThumb($id,$source_path,$filename)
	{	  
	  //--- download file from youtube url ----//	  
	/*  $url  = $source_path;	  
	  $image_path = "http://globalpunjab.s3.amazonaws.com/images/";
	  $path = REAL_PATH . serverImageRelPath.$filename.'.jpg';	  
	  $ch = curl_init($url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	  
	  $data = curl_exec($ch);	  
	  curl_close($ch);	  
	  file_put_contents($path, $data);
	  */
	  //---------------------------------------//
	  $image_path = "http://globalpunjab.s3.amazonaws.com/images/$filename";
	   
	  list($width, $height, $type, $attr) = getimagesize($path);
      switch ($type) {
      case "1":
      $imageType = 'image/gif';
        break;
      case "2":
      $imageType = 'image/jpg';
       break;
      case "3":
      $imageType = 'image/png';
        break;
     }
      $type = 'thumbnail';
      $fileData = array();
      $fileData['content_id'] = $content_id;
      $fileData['filename'] = $fileUniqueName;
      $fileData['type'] = $type;
      $fileData['minetype'] = $imageType;
      $fileData['width'] = $width;
      $fileData['height'] = $height;
      $fileData['relative_path'] = serverImageRelPath . $fileUniqueName;
      $fileData['absolute_path'] = REAL_PATH . serverImageRelPath . $fileUniqueName;
      $fileData['status'] = '0';
      $fileData['uid'] = $this->uid;
      $data_postFile = @serialize($fileData);
      $dataFile = base64_encode($data_postFile);
      $fileData['info'] = $dataFile;
      $last_id = $this->videos_model->_saveThumb($fileData);
	}
}