<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Applications extends MY_Controller {

        public $allowedImageExt;
        
	function __construct()
	{
            
            parent::__construct();
            $this->load->model('Applications_model');
	    $this->load->library('User_Agent');
            $this->load->library('form_validation');
	    $this->load->helper('common');
	    $this->load->helper('pdf_helper');
	    $this->load->helper('csv_helper');
	    
	    $this->load->config('messages');
	    $this->data['welcome'] = $this;
            $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
	    
	    //$this->load->library('User_Agent');//--regex class to get user agent --//
	    //-- get browser http_user_agent info in array --//
                 //   $this->result = get_browser(null, true);
		    
		   $this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		 
                //---------------------//
		
		$this->load->library('session');
		$s = $this->session->all_userdata();
		$this->user = $s[0]->username;
		$this->uid = $s[0]->id;
		$this->role_id = $s[0]->role_id;
	}
        
        protected $validation_rules = array
        (
            'application_name' => array(
                array(
                    'field' => 'application_name',
                    'label' => 'Application Name',
                    'rules' => 'trim|required'
                ),
            ),
            'application_category' => array(
                array(
                    'field' => 'application_category',
                    'label' => 'Category',
                    'rules' => 'trim|required'
                ),
            ),
            'application_version' => array(
                array(
                    'field' => 'application_version',
                    'label' => 'Version',
                    'rules' => 'trim|required'
                ),
            )
        );
        
	
	function index()
	{
            $this->data['welcome'] = $this;
            
            if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
            } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
                $this->session->unset_userdata('search_form');
            }
            $searchterm = $this->session->userdata('search_form');
           // echopre($searchterm);
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = base_url() . "applications/index/";
            $config["total_rows"] = $this->Applications_model->get_applications_count($this->uid);
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $this->data['result'] = $this->Applications_model->get_applications_history($this->uid,PER_PAGE,$page);
            $this->data["links"] = $this->pagination->create_links();
            $this->data['total_rows'] = $config["total_rows"]; 


            $this->show_view('applications_history',$this->data);
        
	}
        
	function registration()
	{
            $this->data['welcome'] = $this;
            if(isset($_GET['edit'])) {
                $app_id = $_GET['edit'];
                $this->data['edit_app'] = $this->Applications_model->get_application_detail($app_id);
                $this->data['edit_app'] = $this->data['edit_app'][0];
            }
            
            if (isset($_POST['submit']) && $_POST['submit'] == "Save") {
                $this->form_validation->set_rules($this->validation_rules['application_name']);
                $this->form_validation->set_rules($this->validation_rules['application_category']);
                $this->form_validation->set_rules('timezone','Titles','required|callback_select_validate');
                $this->form_validation->set_message('select_validate', 'The Timezone field is required.');
                $this->form_validation->set_rules($this->validation_rules['application_version']);
                
                if ($this->form_validation->run()) {
                    
                    if(isset($_POST['edit_id']) && $_POST['edit_id']!=''){
                        $_POST['id'] = $_POST['edit_id'];
                    }
                        $appOldFileId = $_POST['appOldFileId'];
                        if (isset($_FILES['applicationLogo']['tmp_name']) && $_FILES['applicationLogo']['tmp_name'] != "") {
                            if(isset($appOldFileId)){
                                $result = $this->delApplicationLogo($appOldFileId);                                
                            }
                            
                            $file_id = $this->uploadApplicationLogo($_FILES['applicationLogo']["tmp_name"], $_FILES['applicationLogo']["name"]);
                        } else {
                            $file_id = $appOldFileId;
                        }
                        $_POST['file_id'] = $file_id; 
                        
                        $this->Applications_model->_saveApplication($_POST);
                        $msg = $this->loadPo($this->config->item('success_record_update'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('applications');
                        

                }else{
                    $this->data['time_zones'] = unserialize(TIME_ZONE_LIST);
                    $this->show_view('register_application',$this->data);
                }
            }else{
                $this->data['time_zones'] = unserialize(TIME_ZONE_LIST);
                $this->show_view('register_application',$this->data);
            }
	}
        
        function select_validate($post_string) {
            return $post_string == '0' ? FALSE : TRUE;
        }
        
        function uploadApplicationLogo($tmpFilePath, $fileName, $id=null){
        $fileExt = $this->_getFileExtension($fileName); 
        $this->load->model('Category_model');
        $fileUniqueName = uniqid() . "." . $fileExt;
        if (!in_array($fileExt, $this->allowedImageExt)) {
            $msg = $this->loadPo($this->config->item('error_file_format'));
            $this->log($this->user, $msg);
            $this->data['error'] = $msg;
            $this->show_video_view('register_application', $this->data);                
        } else {
            $result = $this->_upload($tmpFilePath, $fileUniqueName, 'applications');
            if($result){
                list($width, $height, $type, $attr) = getimagesize(REAL_PATH.APPLICATIONS_PATH.$fileUniqueName);
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
                $fileData['filename'] = $fileUniqueName;
                $fileData['type'] = $type;
                $fileData['minetype'] = $imageType;
                $fileData['width'] = $width;
                $fileData['height'] = $height;
                $fileData['relative_path'] = APPLICATIONS_PATH.$fileUniqueName;
                $fileData['absolute_path'] = REAL_PATH.APPLICATIONS_PATH.$fileUniqueName;
                $fileData['status'] = '0';
                $fileData['uid'] = $this->uid;
                $fileData['created'] = date('Y-m-d');
                $data_postFile = @serialize($fileData);
                $dataFile = base64_encode($data_postFile);
                $fileData['info'] = $dataFile;
                $fid = $this->Category_model->_saveFile($fileData);
            }
        }
        return $fid;
    }

    public function delApplicationLogo($file_id) {
        $this->load->model('videos_model');
        $this->load->model('Category_model');
        $fileName = $this->videos_model->getThumbImgName($file_id);
        if($fileName) {
            $delResultThumb = $this->_deleteFile($fileName, REAL_PATH.APPLICATIONS_PATH);
            $delResultThumbSmall = $this->_deleteFile($fileName, REAL_PATH.APPLICATIONS_SMALL_PATH);
            $delResultThumbMedium = $this->_deleteFile($fileName, REAL_PATH.APPLICATIONS_MEDIUM_PATH);
            $delResultThumbLarge = $this->_deleteFile($fileName, REAL_PATH.APPLICATIONS_LARGE_PATH);
            $result = $this->Category_model->delCategoryImage($file_id);
        }
    }
    
    /* 	Delete Category */

    function deleteApplication() {
        $id = $_GET['id'];
        $this->Applications_model->delete_application($id);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_delete'))));
        redirect(base_url() . 'applications');
        
    }
        
        
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
