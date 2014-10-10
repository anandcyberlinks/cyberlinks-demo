<?php
  ini_set('display_errors',1);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Device extends MY_Controller
{
	public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedImageExt;

	
    function __construct(){
        parent::__construct();
		$this->load->config('messages');
        $this->load->model('videos_model');
        $this->load->model('Device_model');
		$this->load->library('session');
		$this->load->library('form_validation');
        $data['welcome'] = $this;
		$s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
		$this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');

    }
    
    protected $validation_rules = array
        (
        'upload_splash' => array(
            array(
                'field' => 'uploadfile',
                'label' => 'Upload file',
                'rules' => 'trim|required'
            )
            
        )
	);
	
    function index() {
        $this->data['welcome'] = $this;
        $tab = $this->uri->segment(3);
        $this->data['tab'] = $tab;
		$result = $this->Device_model->checkSplash($this->uid);
		if(!empty($result)){
			$this->data['splash_screen'] = $result[0]->relative_path;
		} else {
			$this->data['splash_screen'] = '';
		}
		$dimensions = unserialize(SPLASH_SCREEN_DIMENSION);
        switch ($tab) {
            case "Flavors":
                $this->show_view('video_settings', $this->data);
                break;
            case "Player":
                $this->data['playerData'] = $this->videos_model->getPlayerData($this->uid);
                $this->show_view('video_settings', $this->data);
                break;
			case "country":
                $this->data['countryData'] = $this->videos_model->getCountryList();
                $this->show_view('video_settings', $this->data);
                break;
            default:
                $this->data['tab'] = 'Splash';
                //-- get splash screen dimensions ----//        
                $this->data['result'] = $this->Device_model->getDimensions($this->uid);        
                $this->show_view('device_settings', $this->data);
        }
    }
    
    function setting_splash()
    {
		$this->data['welcome'] = $this;
        $data['tab'] = 'Splash';
		if(isset($_POST['wsplash'])){
			$width_splash = $_POST['wsplash'];
		}
		if(isset($_POST['wsplash'])){
			$height_splash = $_POST['hsplash'];
		}
		
		foreach ($_POST['dimension'] as $key=>$value){
			$dimensions[$value]['width'] = $width_splash[$key];
			$dimensions[$value]['height'] = $height_splash[$key];			
		}
		if (isset($_POST['save'])) {		
			if (isset($_FILES['uploadfile']['tmp_name']) && $_FILES['uploadfile']['tmp_name'] != "") {
				//-- upload splash screen ---//
				$this->uploadSplashScreen($this->uid,$dimensions);	
			}
			else{
				$msg = $this->loadPo($this->config->item('error_file_upload'));
				$this->log($this->user, $msg);
				$data['message'] = $this->_errormsg($msg);
				redirect('device/index');
			}		
		}
	 
        if (isset($_POST['upload'])) {		           
            if (isset($_FILES['uploadfile']['tmp_name']) && $_FILES['uploadfile']['tmp_name'] != "") {
                //-- upload splash screen ---//
				$this->uploadSplashScreen($this->uid,$dimensions);
				redirect('device/index');
            } else{
				$msg = $this->loadPo($this->config->item('error_file_upload'));
				$this->log($this->user, $msg);
				$data['message'] = $this->_errormsg($msg);
				redirect('device/index');
			}
        }
    }
    
    function uploadSplashScreen($uid,$dimensions=array())
    {
		//-- check if already exists --//
		$splashArr = $this->Device_model->checkSplash($uid);
		if($splashArr){
			foreach($splashArr as $row){
				$splash_id = $row->splash_id;
			//-- delete from splash dimension image --//
				if($row->dimension_id !='')
					$this->Device_model->delete_splash_dimension_image($row->dimension_id);
			//-- delete from files --//
				if($row->file_id !=''){
					$this->Device_model->delete_file($row->file_id);
			//-- delete files from folder --//
				unlink($row->absolute_path);
				}
			//-- delete from splash dimenstion --//                        
				$this->Device_model->delete_splash_dimension($uid);                    
			//-- delete from splash screen--//
				//$this->Device_model->delete_splash_screen($uid);                    
			}
		}
		$tmpFilePath = $_FILES['uploadfile']['tmp_name'];
		$originalFileName = $_FILES['uploadfile']["name"];
		$fileExt = $this->_getFileExtension($originalFileName); 
		$fileUniqueName = uniqid().".".$fileExt;
		$resizefilename = current(explode(".", $fileUniqueName));

		if(empty(array_filter($dimensions))){			
			$dimensions = unserialize(SPLASH_SCREEN_DIMENSION);
		}
	
		if (!in_array($fileExt, $this->allowedImageExt)) {
			$msg = $this->loadPo($this->config->item('error_file_format'));
			$this->session->set_flashdata('message', $this->_errormsg($msg));
			redirect('device/index');  
		} else {			
			$videoresult = $this->_upload($tmpFilePath, $fileUniqueName, true);  exit;		 
			if ($videoresult) {			 
				list($width, $height, $type, $attr) = getimagesize(REAL_PATH . SPLASH_SCREEN_PATH . $incoming_original);
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
				 //-- original file upload ---//
				$type = 'thumbnail';
				$fileData['filename'] = $incoming_original;
				$fileData['type'] = $type;
				$fileData['minetype'] = $imageType;
				$fileData['width'] = $width;
				$fileData['height'] = $height;
				$fileData['relative_path'] = SPLASH_SCREEN_PATH . $incoming_original;
				$fileData['absolute_path'] = REAL_PATH . SPLASH_SCREEN_PATH . $incoming_original;
				$fileData['status'] = '0';
				$fileData['uid'] = $this->uid;
				$fileData['created'] = date('Y-m-d');
				$data_postFile = @serialize($fileData);
				$dataFile = base64_encode($data_postFile);
				$fileData['info'] = $dataFile;
				$last_id = $this->videos_model->_saveThumb($fileData);
				$msg = $this->loadPo($this->config->item('success_file_upload'));
				$this->log($this->user, $msg);
				$this->session->set_flashdata('message', $this->_successmsg($msg));
				redirect($redirect_url);
				
				$file_id = $this->videos_model->insert_file($fileData);                            
										   
				//-- splash screen table insert --//
				if($file_id>0){
					$data_splash['user_id'] = $this->uid;
					$data_splash['file_id'] = $file_id;
					$data_splash['status'] = 1;
					if($splashArr){					
						$this->Device_model->update_splash_screen($data_splash,$uid);				     
					}else{
						$splash_id = $this->Device_model->insert_splash_screen($data_splash);                                    
					}
				}
		 
					//------------------------------//
					
				//-- thumbnails file upload --//
				foreach($dimensions as $key=>$row){
					$type = 'thumbnail';
					$fileData1['filename'] = $resizefilename.'_'.$key.'.'.$fileExt;
					$fileData1['type'] = $type;
					$fileData1['minetype'] = $imageType;
					$fileData1['width'] = $row['width'];
					$fileData1['height'] = $row['height'];
					$fileData1['relative_path'] = SPLASH_SCREEN_PATH . $resizefilename.'_'.$key.'.'.$fileExt;
					$fileData1['absolute_path'] = REAL_PATH . SPLASH_SCREEN_PATH . $resizefilename.'_'.$key.'.'.$fileExt;
					$fileData1['status'] = '0';
					$fileData1['uid'] = $this->uid;
					$fileData1['created'] = date('Y-m-d');
					$data_postFile = @serialize($fileData1);
					$dataFile = base64_encode($data_postFile);
					$fileData1['info'] = $dataFile;
					$file_id = $this->videos_model->insert_file($fileData1);                            
					
					if ($file_id>0) {
						//-- insert splash dimenstions --//
						$splash_dimension['user_id'] = $this->uid;
						$splash_dimension['width'] = $row['width'];
						$splash_dimension['height'] = $row['height'];
						$splash_dimension['dimension_name'] = $key;
						$splash_dimension['status'] = 1;
					   $dimension_id = $this->Device_model->insert_splash_dimension($splash_dimension);
						
						//-- insert dimension image --// 
					   if($dimension_id>0){
							$dimension_image['splash_id'] = $splash_id;
							$dimension_image['dimension_id'] = $dimension_id;
							$dimension_image['file_id'] = $file_id;
							$dimension_image['status'] = 1;
							$this->Device_model->insert_splash_dimension_image($dimension_image);
					   }
						//------------------------------//
					}
				}
				
				$msg = $this->loadPo($this->config->item('success_file_upload'));
				$this->log($this->user, $msg);
				$this->session->set_flashdata('message', $this->_successmsg($msg));
				redirect('device/index');
			} else {
				$msg = $this->loadPo($this->config->item('error_file_upload'));
				$data['message'] = $this->_errormsg($msg);
				$this->show_video_view('videoEditThumbnail', $data);
			}	redirect('device/index');                        
		}
	}
}