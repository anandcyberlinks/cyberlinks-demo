    <?php
    ini_set('display_errors',1);
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Youtubecurl extends MY_Controller {
    
    
    function __construct() {
        parent::__construct();
        $this->load->model('videos_model');
        $this->load->model('category_model');
        unset($this->session);
    }
    
    function index() {
        if (isset($_POST) && (!empty($_POST))) {
            if($this->file_exists($_POST['filename'])) {
                $userId = $_POST['uid'];
                $originalFilePath = $_POST['filename'];
                $originalThumbFilePath = $_POST['thumbfilename'];
                $fileExt = end(explode('.',$originalFilePath));
                $thumbFileExt = end(explode('.',$originalThumbFilePath)); 
                $fileUniqueName = uniqid().".".$fileExt;
                $thumbFileUniqueName = uniqid().".".$thumbFileExt;
                
                $catId = $this->category_model->getCatId(trim($_POST['content_category']), $userId);
                $fieDestPath =  REAL_PATH.serverVideoRelPath. $fileUniqueName;
                $videoresult = $this->_uploadFileCurl($originalFilePath, $fieDestPath);
                $thumbFieDestPath =  REAL_PATH.serverImageRelPath. $thumbFileUniqueName;
                $thumbresult = $this->_uploadFileCurl($originalThumbFilePath, $thumbFieDestPath, false, true);
                if($videoresult) {
                    $_POST['filename'] = $fileUniqueName;
                    $_POST['category'] = $catId;
                    $_POST['relative_path'] = serverVideoRelPath . $fileUniqueName;
                    $_POST['absolute_path'] = REAL_PATH.serverVideoRelPath.$fileUniqueName;
                    $_POST['status'] = '1';
                    $_POST['type'] = $fileExt;
                    $_POST['minetype'] = "video/" . $fileExt;
                    $_POST['info'] = base64_encode($fileUniqueName);
                    $_POST['status'] =  1;
                    $post_key = $_POST['tags'];
                    $last_id = $this->videos_model->_saveVideo($_POST);
                    if($last_id){
                        $this->videos_model->_setKeyword($post_key, $last_id);  //save keyword
                        /* save thumb */
                        if($thumbresult) {
                            list($width, $height, $type, $attr) = getimagesize($thumbFieDestPath);
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
                            $fileData['content_id'] = $last_id;
                            $fileData['filename'] = $thumbFileUniqueName;
                            $fileData['type'] = $type;
                            $fileData['minetype'] = $imageType;
                            $fileData['width'] = $width;
                            $fileData['height'] = $height;
                            $fileData['relative_path'] = serverImageRelPath.$thumbFileUniqueName;
                            $fileData['absolute_path'] = REAL_PATH.serverImageRelPath.$thumbFileUniqueName;
                            $fileData['status'] = '0';
                            $fileData['uid'] = $userId;
                            $data_postFile = @serialize($fileData);
                            $dataFile = base64_encode($data_postFile);
                            $fileData['info'] = $dataFile;
                            $lastThumbId = $this->videos_model->_saveThumb($fileData);
                            if($lastThumbId){
                                $result = $this->videos_model->setDefaultImg($last_id, $lastThumbId);
                            }                            
                        }                        
                    }
                }else{
                    echo 'file copy problem';
                }
            }else{
                echo 'file not exists';
            }
        } else {
            $this->load->view('curl_post_youtube');
        }
        
    }
    
    function getvideo(){
        $postUrl = 'http://localhost/multitvfinal/youtubecurl';
        $folderPath = $_GET['url'];
        if (is_dir($folderPath)) {
            if ($dh = opendir($folderPath)) {
                while (($file = readdir($dh)) !== false) {
                    if($file != '.' && $file != '..'){
                        
                        $basepath = $folderPath.'/'.$file;
                        if(is_file($basepath)){
                            $extension = end(explode('.',$basepath));
                            if(strtolower($extension) == 'mp4'){
                                
                                $id = str_replace('.mp4','',$file);
                                $url = sprintf('http://www.youtube.com/watch?v=%s',$id);
                                $data = $this->get_youtube($url);
                                
                                $params = array();
                                if($data['content']['title'] != ''){
                                    $params['uid'] = isset($_GET['uid']) ? $_GET['uid'] : '1';
                                    $params['content_title'] = $data['content']['title'];
                                    $params['content_category'] = isset($_GET['cat']) ? $_GET['cat'] : '';
                                    $params['description'] = $data['content']['descritpion'];
                                    $params['filename'] = $basepath;
                                    $params['thumbfilename'] = sprintf('http://img.youtube.com/vi/%s/0.jpg',$id);
                                    $params['tags'] = $data['content']['keywords'];    
                                }
                                
                                $ch = curl_init();  
                                curl_setopt($ch,CURLOPT_URL,$postUrl);
                                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                                curl_setopt($ch,CURLOPT_HEADER, false); 
                                curl_setopt($ch,CURLOPT_POST, count($params));
                                curl_setopt($ch,CURLOPT_POSTFIELDS, $params);    
                                $output = curl_exec($ch);
                                curl_close($ch);
                            }
                        }
                    }
                }
            }
        }else{
            echo $folderPath.' dir not exists';
            exit;
        }
    }
    
    function _uploadFileCurl($originalFilePath, $fieDestPath, $fileUniqueName = null, $thumb = null){
        if($this->isRemoteFile($originalFilePath)){
            echo 'server copy';
        }else{
            if (!copy($originalFilePath, $fieDestPath)) {
                return false;
            }else{
                if($thumb) {
                    $thumbdimensions = unserialize(THUMB_DIMENSION);
                    foreach($thumbdimensions as $key=>$value){
                        if($key == 'small') {
                            $path = REAL_PATH.THUMB_SMALL_PATH;
                        } else if($key == 'medium') {
                            $path = REAL_PATH.THUMB_MEDIUM_PATH;
                        } else if($key == 'large') {
                            $path = REAL_PATH.THUMB_LARGE_PATH;
                        }
                        $img = $this->create_thumbnail($fileUniqueName, $fieDestPath, $path, $value['width'], $value['height']);                                    
                        $fileNameNw='';
                    }
                }
                return true;    
            }
        }
    }
    
    function file_exists($url){
        if($this->isRemoteFile($url)){
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($retcode==200)
                return true;
            else
                return false;
        }else{
            return file_exists($url);
        }
    }
    
    function isRemoteFile($url){
        if(strpos('http://',$url) !== false){
            return true;
        }else{
            return false;
        }
    }
    
    function get_youtube($url){
        $id = $this->getYoutubeIdFromUrl($url);
        $html = $this->get_data($url);
        $data = $this->get_meta_tags($html);
        
        $youtube = sprintf('http://gdata.youtube.com/feeds/api/videos/%s?v=2&alt=json',$id);
        $curl = curl_init($youtube);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        
        $data['detail'] = (array) json_decode($return);
        return $data;
    }
    
    function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if($qs['vi']){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;
    }
    
    function get_data($url) {
        $ch = curl_init();
        $timeout = 50;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    function get_meta_tags($html){
        preg_match_all ("/<title>(.*)<\/title>/", $html, $title);
        preg_match_all ("/<meta name=\"description\" content=\"(.*)\">/i", $html, $description);
        preg_match_all ("/<meta name=\"keywords\" content=\"(.*)\">/i", $html, $keywords);
        $res["content"] = @array("title" => $title[1][0], "descritpion" => $description[1][0], "keywords" =>  $keywords[1][0]);
        return $res;
    }
    
}