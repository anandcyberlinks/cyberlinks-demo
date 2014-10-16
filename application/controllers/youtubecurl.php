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
            if($_POST['filename'] != '') {
                $originalFilePath = $_POST['filename'];
                $path_parts = pathinfo($originalFilePath);
                $fileExt = $path_parts['extension'];
                $fileUniqueName = uniqid().".".$fileExt;
                $catId = $this->category_model->getCatId($_POST['content_category']);
                $fieDestPath =  REAL_PATH.serverVideoRelPath. $fileUniqueName;
                $videoresult = $this->_uploadFileCurl($originalFilePath, $fieDestPath, $fileUniqueName);
                if($videoresult) {
                    $_POST['filename'] = $fileUniqueName;
                    $_POST['content_category'] = $catId;
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
                        $this->videos_model->_setKeyword($post_key, $last_id);                    
                    }
                }
            }
        } else {
            $this->load->view('curl_post_youtube');
        }
        
    }
    
}
    
        
    
    /* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */
            
