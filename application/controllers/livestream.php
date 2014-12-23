<?php
ini_set('display_errors', 1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Livestream extends MY_Controller {

    public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedImageExt;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('Livestream_model');
        $this->load->library('form_validation');
        $this->load->library('Session');
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
        $this->uid = $s[0]->id;
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');

    }

    protected $validation_rules = array
        (
        'add_category' => array(
            array(
                'field' => 'channel_name',
                'label' => 'Channel Name',
                'rules' => 'trim|required'
            ),         
        ),
        'update_Stream' => array(
            array(
                'field' => 'channel_name',
                'label' => 'Channel name',
                'rules' => 'trim|required'
            ),            
        )
    );

    function live_streaming() {
        if(isset($_POST['save'])){
            $this->Livestream_model->saveUrl($_POST['url'], $this->uid);
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url()."video/live_streaming");
        }
        $data['url'] = $this->videos_model->getLivestream($this->uid);
        $data['welcome'] = $this;
        $this->show_view('live_streaming', $data);
    }

    function index() {
        $data['result'] = $result = $this->Livestream_model->getStream($this->uid);
         //print_r($result);die;
         if(isset($_POST['save'])){
           
            //--- upload file --//
            if (isset($_FILES['chanelImage']['tmp_name']) && $_FILES['chanelImage']['tmp_name'] != "") {
                if(isset($result->thumbnail_url)){
                    unlink($result->thumbnail_url);                                
                }                
                $thumbnail = $this->uploadImg($_FILES['chanelImage']["tmp_name"], $_FILES['chanelImage']["name"]);
            } else {
                //$thumbnail = $result->thumbnail_url;
            }
            $input['youtube'] = $_POST['youtube'];
            $input['ios'] = $_POST['ios'];
            $input['android'] = $_POST['android'];
            $input['windows'] = $_POST['windows'];
            $input['web'] = $_POST['web'];
            $input['thumbnail_url'] = base_url().$thumbnail;
            $input['user_id'] = $this->uid;
            $input['status'] = 1;
            
            if(empty($result)){
                $this->Livestream_model->saveUrl($input);
            }else{
                $this->Livestream_model->saveUrl($input,'update');
            }
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url()."livestream");
        }
        
       
        $data['welcome'] = $this;
        $this->show_view('livestream', $data);
    }

    function uploadImg($tmpFilePath, $fileName, $id=null){
        $fileExt = $this->_getFileExtension($fileName); 
        $fileUniqueName = uniqid() . "." . $fileExt;
        $filepath = 'assets/upload/thumbs/'.$fileUniqueName;     
          move_uploaded_file($tmpFilePath,REAL_PATH.$filepath);
          return $filepath;        
    }
    
    function rendervideo(){
        $data['path'] = base64_decode($_GET['path']);
        $this->load->view('rendervideo',$data);    
    }
        
    public function delImage($file_id) {
        $fileName = $this->videos_model->getThumbImgName($file_id);
        if($fileName) {
            $delResultThumb = $this->_deleteFile($fileName, REAL_PATH.CATEGORY_PATH);
            $delResultThumbSmall = $this->_deleteFile($fileName, REAL_PATH.CATEGORY_SMALL_PATH);
            $delResultThumbMedium = $this->_deleteFile($fileName, REAL_PATH.CATEGORY_MEDIUM_PATH);
            $delResultThumbLarge = $this->_deleteFile($fileName, REAL_PATH.CATEGORY_LARGE_PATH);
            $result = $this->Category_model->delCategoryImage($file_id);
        }
    }    
}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */    