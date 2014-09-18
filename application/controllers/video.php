    <?php
    
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    
    class Video extends MY_Controller {
    
        public $user = null;
        public $role_id = null;
        public $uid = null;
    
        function __construct() {
            parent::__construct();
            $this->load->model('videos_model');
            $this->load->model('category_model');
            $this->load->library('session');
            $this->load->library('form_validation');
            $this->load->library('csvreader');
            $data['welcome'] = $this;
            $s = $this->session->all_userdata();
            $this->user = $s[0]->username;
            $this->uid = $s[0]->id;
            $this->role_id = $s[0]->role_id;
        }
    
        protected $validation_rules = array
            (
            'update_video' => array(
                array(
                    'field' => 'content_title',
                    'label' => 'Content Title',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'content_category',
                    'label' => 'Content Category',
                    'rules' => 'trim|required'
                ),
                            /* array(
                  'field' => 'content_channel',
                  'label' => 'Content Channel',
                  'rules' => 'trim|required'
                  ), */ 
                array(
                    'field' => 'description',
                    'label' => 'Description',
                    'rules' => 'trim|required'
                )
            ),
            'video_schedule' => array(
                array(
                    'field' => 'datepickerstart',
                    'label' => 'Start Date',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'datepickerend',
                    'label' => 'End Date',
                    'rules' => 'trim|required'
                )
            ),
            'videosrc_info' => array(
                array(
                    'field' => 'source_url',
                    'label' => 'Source Url',
                    'rules' => 'trim|required|valid_url_format|url_exists'
                ),
                array(
                    'field' => 'content_provider',
                    'label' => 'Content Provider',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'title',
                    'label' => 'Title',
                    'rules' => 'trim|required'
                ),
                array(
                    'field' => 'category',
                    'label' => 'Category',
                    'rules' => 'trim|required'
                )
            )
        );
    
        /*  show video listing  */
    
        function index() {
            $sess = $this->session->all_userdata();
            $uid = $sess['0']->id;
            $sort = $this->uri->segment(3);
            $sort_by = $this->uri->segment(4);
            $data['welcome'] = $this;
            switch ($sort) {
                case "category":
                    $sort = 'b.category';
                    if ($sort_by == 'asc')
                        $data['show_c'] = 'desc';
                    else
                        $data['show_c'] = 'asc';
                    break;
                case "user":
                    $sort = 'a.uid';
                    if ($sort_by == 'asc')
                        $data['show_u'] = 'desc';
                    else
                        $data['show_u'] = 'asc';
                    break;
                case "status":
                    $sort = 'a.status';
                    if ($sort_by == 'asc')
                        $data['show_s'] = 'desc';
                    else
                        $data['show_s'] = 'asc';
                    break;
                case "created":
                    $sort = 'a.created';
                    if ($sort_by == 'asc')
                        $data['show_ca'] = 'desc';
                    else
                        $data['show_ca'] = 'asc';
                    break;
                case "title":
                    $sort = 'a.title';
                    if ($sort_by == 'asc')
                        $data['show_t'] = 'desc';
                    else
                        $data['show_t'] = 'asc';
                    break;
                default:
                    $sort_by = 'desc';
                    $sort = 'a.id';
            }
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = base_url() . "video/index/";
            $config["total_rows"] = $this->videos_model->get_videocount($uid);
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['result'] = $this->videos_model->get_video($uid, $config["per_page"], $page, $sort, $sort_by);
            $data["links"] = $this->pagination->create_links();
            $data['category'] = $this->videos_model->get_category($this->uid);
            $data['total_rows'] = $config["total_rows"];
            $this->show_view('search_video', $data);
        }
    
        /* --.show video listing after seaarch -- */
    
        public function search() {
            $data['welcome'] = $this;
            if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
                $this->session->set_userdata('search_form', $_POST);
            } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
                $this->session->unset_userdata('search_form');
            }
            $sess = $this->session->all_userdata();
            $uid = $sess['0']->id;
            $search_for = $this->session->userdata('search_form');
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = base_url() . "video/search/";
            $config["total_rows"] = $this->videos_model->getSearchRecord_count($search_for, $uid);
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data['result'] = $this->videos_model->getSearchVideos($uid, $config["per_page"], $page, $search_for);
            $data["links"] = $this->pagination->create_links();
            $data["search_data"] = $search_for;
            $data['category'] = $this->videos_model->get_category($this->uid);
            $data['total_rows'] = $config["total_rows"];
            $this->show_view('search_video', $data);
        }
    
        /*    shoe viedo profile for edit and Update   */
    
        function videoprofile() {
            $sess = $this->session->all_userdata();
            $this->data['welcome'] = $this;
            $id = @$_GET['action'];
            $vid = base64_decode($id);
            if ($vid) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_video']);
                    if ($this->form_validation->run()) {
                        $post = $_POST;
                        $post['content_id'] = $vid;
                        $post['status'] = $this->input->post('status') == 'on' ? 1 : 0;
			$post['feature_video'] = $this->input->post('feature_video') == 'on' ? 1 : 0;
                        $post_key = $_POST['tags'];
                        $this->videos_model->_saveVideo($post);
                        $this->videos_model->_setKeyword($post_key, $vid);
                        $msg = $this->loadPo('Video Updated successfully.');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        redirect('video');
                    } else {
                        $this->data['result'] = (array) $this->videos_model->edit_profile($vid);
                        $this->data['result']['keywords'] =  $this->videos_model->_getKeyword($vid);
                        $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
                        $this->data['content_id'] = $vid;
                        $this->data['category'] = $this->videos_model->get_category($this->uid);
                        $this->data['setting'] = $this->videos_model->getsetting($vid);
                        $this->data['countryData'] = $this->videos_model->getCountryList(); 
                        $this->show_video_view('videoEditBasic', $this->data);
                    }
                } else {
                    $this->data['result'] = (array) $this->videos_model->edit_profile($vid);
                    $this->data['result']['keywords'] =  $this->videos_model->_getKeyword($vid);
                    $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
                    $this->data['content_id'] = $vid;
                    $this->data['category'] = $this->videos_model->get_category($this->uid);
                    $this->data['setting'] = $this->videos_model->getsetting($vid);
                    $this->data['countryData'] = $this->videos_model->getCountryList(); 
                    $this->show_video_view('videoEditBasic', $this->data);
                }
            }
        }
    
        /*    delete video  from List   */
    
        function deletevideo() {
            $per = $this->checkpermission($this->role_id, 'delete');
            //echo $this->role_id;
            if ($per) {
                $sess = $this->session->all_userdata();
                $id = $_GET['id'];
                $type = $this->videos_model->gettype($id);
               // print_r($type); die;
                if($type['0']->type == 'youtube'){
                $result = $this->videos_model->deleteyoutube($id);
                $msg = $this->loadPo('Video Deleted successfully.');
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                redirect($_GET['curl']); exit;

                }
                $data['result'] = $this->videos_model->edit_profile($id);
                foreach ($data['result'] as $value) {                   
					$delResult = $this->deleteFileS3($value->file);
					$result = $this->videos_model->delete_video($id);
					if ($result == '1') {
						$msg = $this->loadPo('Video Deleted successfully.');
						$this->log($this->user, $msg);
						$this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
						redirect($_GET['curl']);
					}
                }
            } else {
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined</div></div></section>');
                redirect(base_url() . 'video');
            }
        }
    
        /*    Upload  video  on  List   */
    
        function upload() {
            $per = $this->checkpermission($this->role_id, 'add');
            if ($per) {
                $sess = $this->session->all_userdata();
                $this->data['welcome'] = $this;
                if (isset($_FILES['0']['tmp_name']) && $_FILES['0']['tmp_name'] != "") {
                    $incoming_tmp = $_FILES['0']['tmp_name'];
                    $incoming_original = $_FILES["0"]["name"];
                    $incoming_size = $_FILES["0"]["size"];
                    $content_type = $_FILES["0"]["type"];
                    $extension = end(explode(".", $incoming_original));
                    $incoming_original = uniqid().".".$extension;
                    $path = './assets/upload/video/';
                    $video_name = $incoming_original;
                    $allowed = array('mp4', 'mpg', 'mpeg', 'flv','wmv', 'avi');
                    if (!in_array($extension, $allowed)) {
                        $data['flag'] = '0';
                        $data['message'] = '<div class="alert alert-danger alert-dismissable">
                                    <i class="fa fa-ban"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    Please select files 
                                    </div> ';
                        echo json_encode($data);
                    } else {
                       $videoresult = $this->uploadVideoAS3($incoming_tmp, $incoming_original, $content_type); 
                                            if($videoresult) {
                            $data = array();
                            $data['content_title'] = $incoming_original;
                            $data['uid'] = $sess['0']->id;
                            $data['filename'] = $incoming_original;
                            $data['uid'] = $sess['0']->id;
                            $data['relative_path'] = $path . $incoming_original;
                            $data['absolute_path'] = amazonFileUrl . $incoming_original;
                            $data['minetype'] = "video/" . $extension;
                            $data['type'] = $extension;
                            $data['status'] = '0';
                            $data['info'] = base64_encode($incoming_original);
                            $last_id = $this->videos_model->_saveVideo($data);
                            if ($last_id != '') {
                                $msg = $this->loadPo('Video Uploaded successfully.');
                                $this->log($this->user, $msg);
                                $data['id'] = base64_encode($last_id);
                                $data['message'] = sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg);
                                echo json_encode($data);
                            }
                        } else {
                            $msg = $this->loadPo('Upload failed.');
                            $data['message'] = sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-warning alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>',$msg);
                            echo json_encode($data);
                        }
                    }
                                    //$videoresult = $this->upload_move_file($incoming_tmp, $incoming_original, $path);
                } else {
                    $this->show_view('upload_video', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin! You Don\'t do this, Contect Admin</div></div></section>');
                redirect(base_url() . 'video');
            }
        }
    
        public function upload_move_file($incoming_tmp, $incoming_original, $path) {
            @chmod($path . $incoming_original, 0777);
            if (move_uploaded_file($incoming_tmp, $path . $incoming_original)) {
                chmod($path . $incoming_original, 0666);
                return true;
            } else {
                return false;
            }
        }
    
    function remoteFileExists($url) {
        $curl = curl_init($url);
        //don't fetch the actual page, you only want to check the connection is ok
        curl_setopt($curl, CURLOPT_NOBODY, true);
        
        //do request
        $result = curl_exec($curl);
        $ret = false;
        //if request did not fail
        if ($result !== false) {
            //if request was ok, check response code
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
            if ($statusCode == 200) {
                $ret = true;   
            }
        }
        curl_close($curl);
        return $ret;
    }
    
    function bulkupload() {
        $this->data['welcome'] = $this;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_POST['Submit']) && $_POST['Submit'] == 'Upload') {
                $sucess_arr = array();
                $failuar_arr = array();
                $incoming_tmp = $_FILES['csv_file']['tmp_name']; 
                $incoming_original = $_FILES["csv_file"]["name"];
                $extension = end(explode(".", $incoming_original));
                $extension = strtolower($extension);
                $dir_path = getcwd();
                $path_nw = str_replace('\\', '/', $dir_path); // Replace backslashes with forwardslashes
                $emsg = '';
                if ($extension == 'csv') {
                    $csv_data = $this->csvreader->parse_file($incoming_tmp);
                    //print_r($csv_data); die;
                    foreach ($csv_data as $row) {
                        if($this->remoteFileExists($row['url'])){
                            $fileSrcPath = $row['url'];
                            $videoFileExt = end(explode(".", $row['fileName']));
                            $videoFileUniqName = uniqid() . "." . $videoFileExt;
                            $post['source_url'] = $videoFileUniqName;
                            $post['content_provider'] = 'selfvideo';
                            $post['uid'] = $this->uid;
                            $post['created'] = date('Y-m-d');
                            $uploadPath = './assets/upload/video/';
                            $catId = $this->category_model->getCatId($row['category']);
                            $allowed = array('mp4', 'mpg', 'mpeg', 'flv', 'wmv','3gp');
                            if (in_array($videoFileExt, $allowed)) {							
                                $urlExt = current(explode(":", $fileSrcPath));
                                if ($urlExt == 'http' || $urlExt == 'https') {
                                    //$destPath = './assets/upload/video/' . $videoFileUniqName;
                                    $destPath = $path_nw . '/assets/upload/video/' . $videoFileUniqName;
                                    //Here is the file we are downloading, replace spaces with %20
                                    $ch = curl_init(str_replace(" ", "%20", $fileSrcPath));
                                    //File to save the contents to
                                    $fp = fopen($destPath, 'wb');
                                    //give curl the file pointer so that it can write to it
                                    curl_setopt($ch, CURLOPT_FILE, $fp);
                                    curl_setopt($ch, CURLOPT_HEADER, 0);
                                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                    $data = curl_exec($ch); //get curl response
                                    curl_close($ch);
                                    fclose($fp);
                                    $status = '1';
                                } else {
                                    
                                    $destPath = $path_nw . '/assets/upload/video/' . $videoFileUniqName;
                                    if (file_exists($fileSrcPath)) {
                                        $status = '1';
                                    } else {
                                        $status = '0';
                                    }
                                }
                                
                                if ($status == '1') {
                                    
                                        $finfo = new finfo; 
                                        $fileinfo = @$finfo->file($fileSrcPath, FILEINFO_MIME);
                                        @list($content_type,$binary) = @explode(";",$fileinfo);
                                        $videoresult = $this->uploadVideoAS3($destPath, $videoFileUniqName, trim($content_type)); 
                                        
                                        if ($videoresult) {
                                                $contentData = array();
                                                $contentData['content_title'] = isset($row['content_title']) && $row['content_title'] != '' ? $row['content_title'] : 'content_title';
                                                $contentData['description'] = isset($row['description']) && $row['description'] != '' ? $row['description'] : 'description';
                                                $contentData['type'] = 'CSV';
                                                $contentData['category'] = $catId;
                                                $contentData['uid'] = $this->uid;
                                                //$contentData['created'] = date('Y-m-d');
                                                $contentData['filename'] = $videoFileUniqName;
                                                $contentData['uid'] = $this->uid;
                                                //$fileData['created'] = date('Y-m-d');
                                                $contentData['relative_path'] = $uploadPath . $videoFileUniqName;
                                                $contentData['absolute_path'] = amazonFileUrl . $videoFileUniqName;
                                                $contentData['minetype'] = "video/" . $videoFileExt;
                                                $contentData['type'] = $videoFileExt;
                                                $contentData['status'] = '1';
                                                $contentData['info'] = base64_encode($videoFileUniqName);
                                                $lastId = $this->videos_model->_saveVideo($contentData);
                                               
                                                //Insert keywords
                                                if(isset($row['keyword']) && $row['keyword'] != ''){
                                                    $this->videos_model->_setKeyword(str_replace("|",",",$row['keyword']), $lastId);
                                                }
                                                if ($lastId != '') {
                                                    $success_video[] =$row;
                                                        $msg = '';
                                                        foreach ($success_video as $val) {
                                                                $msg .= $val['content_title'] . '&nbsp;&nbsp;';
                                                                $msg .= $val['fileName'] . '&nbsp;&nbsp;';
                                                                $msg .= $val['category'] . '&nbsp;';
                                                                $msg .= '</br>';
                                                        }
                                                        $message = $this->loadPo('Video content saved successfully in database') . " : <br>" . $msg;
                                                        $this->log($this->user, $message);
                                                        $this->data['s_error'] = sprintf('<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                                                } else {
                                                        $failuar_save_video[] = $row;
                                                        $msg = '';
                                                        foreach ($failuar_save_video as $val) {
                                                                $msg .= $val['content_title'] . '&nbsp;&nbsp;';
                                                                $msg .= $val['fileName'] . '&nbsp;&nbsp;';
                                                                $msg .= $val['category'] . '&nbsp;';
                                                                $msg .= '</br>';
                                                        }
                                                        $message = $this->loadPo('This video content is not saved in database') . " : <br>" . $msg;
                                                        $this->log($this->user, $message);
                                                        $this->data['f_error_s'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                                                }
                                                if (isset($destPath)) {
                                                        unlink($destPath);
                                                }
                                        } else {
                                            $failuar_save_video[] = $row;
                                            $msg = '';
                                            foreach ($failuar_save_video as $val) {
                                                    $msg .= $val['content_title'] . '&nbsp;&nbsp;';
                                                    $msg .= $val['fileName'] . '&nbsp;&nbsp;';
                                                    $msg .= $val['category'] . '&nbsp;';
                                                    $msg .= '</br>';
                                            }
                                            $message = $this->loadPo('Upload failed.') . " : <br>" . $msg;
                                            $this->log($this->user, $message);
                                            $this->data['f_error_s'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                                        }
                                } else {
                                        $failuar_move_video[] = $row;
                                        $msg = '';
                                        foreach ($failuar_move_video as $val) {
                                                $msg .= $val['content_title'] . '&nbsp;&nbsp;';
                                                $msg .= $val['fileName'] . '&nbsp;&nbsp;';
                                                $msg .= $val['category'] . '&nbsp;';
                                                $msg .= '</br>';
                                        }
                                        $message = $this->loadPo('This video content is not available in directory') . " : <br>" . $msg;
                                        $this->log($this->user, $message);
                                        $this->data['f_error_m'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                                }
                            } else {
                                $message = $this->loadPo('Video file format is incorrect.');
                                $this->log($this->user, $message);
                                $this->data['f_error_m'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                            }
                        }else{
                            $emsg .= $row['url'].' file not found<br/>';
                        }
                        
                        if($emsg!=''){
                            $this->data['f_error_m'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $emsg);
                        }
                    }
                    $this->show_view('bulkupload', $this->data);
                } else {
                    $message = $this->loadPo('Please upload csv files only.');
                    $this->log($this->user, $message);
                    $this->data['error'] = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
                    $this->show_view('bulkupload', $this->data);
                }
            } else {
                $this->show_view('bulkupload', $this->data);
            }
        } else {
            $message = $this->loadPo('Access Denined ! Contact Admin!');
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $message));
            redirect(base_url() . 'video');
        }
    }

   public function ftpLogin() {
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['ftpserver'])) {

            $conn_id = @ftp_connect($_POST['ftpserver']);
            if ($conn_id) {
                $response['message'][] = 'Successfully connect with server';
                //Try to login
                if (@ftp_login($conn_id, $_POST['username'], $_POST['password'])) {
                    $_POST['ftpPath'] = $_POST['ftpPath'] != '' ? $_POST['ftpPath'] : '/';
					ftp_pasv($conn_id, true);
					ftp_chdir($conn_id,$_POST['ftpPath']);
                    $rawfiles = ftp_rawlist($conn_id, $_POST['ftpPath']); //true being for recursive					
                    $tmpData = array();
					$response['data'] = array();
                    if ($_POST['ftpPath'] != '/') {
                        $tmp = explode('/', substr($_POST['ftpPath'], 0, -1));
                        array_pop($tmp);
                        $tmp = implode('/', $tmp) . '/';
						$tmpData['dir'][] = array('name' => '..', 'path' => $tmp);
                    }
                    foreach ($rawfiles as $rawfile) {
                        $info = preg_split("/[\s]+/", $rawfile, 9);
                        if (substr($info[0], 0, 1) == 'd') {
                            $tmp = $_POST['ftpPath'] == '/' ? '/' . $info[8] . '/' : $_POST['ftpPath'] . $info[8] . '/';
                            $tmpData['dir'][] = array('name' => $info[8], 'path' => $tmp);
                        } else {
                            $tmpData['file'][] = $info;
                        }
                    }
                    $response['result'] = 'true';
                    $response['data'] = '<div class="table-responsive"><table class="table table-mailbox">';
                    $response['data'] .= '<tr class="unread">
								<th class="small-col">&nbsp;</th>
								<th class="small-col">Filename</th>
								<th class="small-col">FileSize</th>
								<th class="small-col">FileType</th>
								<th class="small-col">Last Modified</th>
								<th class="small-col">Permission</th></tr>';
                    if (isset($tmpData['dir'])) {

                        foreach ($tmpData['dir'] as $val) {

                            if (isset($val)) {
                                $response['data'] .= '<tr class="unread">';
                                $response['data'] .= '<td class="small-col"><i class="fa fa-fw fa-folder-o"></i></td>';
                                $response['data'] .= '<td class="small-col"><a href="' . $val['path'] . '" class="ftpdir">' . $val['name'] . '</a></td>';
                                $response['data'] .= '<td class="small-col">--</td>';
                                $response['data'] .= '<td class="small-col">--</td>';
                                $response['data'] .= '<td class="small-col">--</td>';
                                $response['data'] .= '<td class="small-col">--</td>';
                                $response['data'] .= '</tr>';
                            }
                        }
                    }

                    $validExt = array('mp4', 'swf');
                    if (isset($tmpData['file'])) {
                        foreach ($tmpData['file'] as $key => $val) {
                            $fileext = end(explode('.', $val[8]));
                            if (in_array($fileext, $validExt)) {
                                $size = round((intval(trim($val[4])) / 1048576), 2);
                                $modified = date('d/m/Y', strtotime($info[6] . ' ' . $info[5] . ' ' . $info[7]));
                                $response['data'] .= '<tr class="unread">';
                                $response['data'] .= '<td class="small-col"><input alt="' . $size . '"class="ftpcheck" name="files[]" type="checkbox" value="' . $_POST['ftpPath'] . $val[8] . '" /></td>';
                                $response['data'] .= '<td class="small-col">' . $val[8] . '</td>';
                                $response['data'] .= '<td class="small-col">' . $size . '&nbsp;MB</td>';
                                $response['data'] .= '<td class="small-col">' . $fileext . '</td>';
                                $response['data'] .= '<td class="small-col">' . $modified . '</td>';
                                $response['data'] .= '<td class="small-col" id="td_' . $size . '">' . $val[0] . '</td>';
                                $response['data'] .= '</tr>';
                            }
                        }
                    }
                    $response['data'] .= '</table></div>';
                    echo json_encode($response['data']);
                    die;
                } else {
                    $data['flag'] = '0';
                    $data['error'] = '<div class="alert alert-danger alert-dismissable">
					<i class="fa fa-ban"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Username and password is wrong
					</div> ';
                    echo json_encode($data);
                }
            } else {
                $data['flag'] = '0';
                $data['error'] = '<div class="alert alert-danger alert-dismissable">
					<i class="fa fa-ban"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					Could not connect with server
					</div> ';
                echo json_encode($data);
            }
        } else {
            $data['flag'] = '0';
            $data['error'] = '<div class="alert alert-danger alert-dismissable">
				<i class="fa fa-ban"></i>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				Please fill all Field
				</div> ';
            echo json_encode($data);
        }
    }

    public function uploadFtp() {
        // connect and login to FTP server
		$ftp_server = $_POST['ftpserver'];
        $ftp_username = $_POST['username'];
        $ftp_userpass = $_POST['password'];
        $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
		ftp_pasv($ftp_conn, true);
        $redirect_url = $_POST['redirect_url'];
        $fileNameArr = $_POST['chk'];
        $filenameNew = explode(",", rtrim($fileNameArr, ','));
        for ($i = 0; $i < count($filenameNew); $i++) {
            $fileName = $filenameNew[$i];
            $fileExt = end(explode(".", $fileName));
            $fileNameUniq = uniqid() . "." . $fileExt;
            $server_file = $this->input->post('ftpPath') . $fileName;
            $uploadPath = './assets/upload/video/';
			$dir_path = getcwd();
			$path_nw = str_replace('\\', '/', $dir_path);
            $local_file = $path_nw."/assets/upload/video/" . $fileNameUniq;
            $allowed = array('mp4', 'mpg', 'mpeg', 'flv', 'swf', 'wmv');
            $msg = "";
            $errmsg = "";
            if (!in_array($fileExt, $allowed)) {
                $errmsg .= $fileName . '&nbsp;&nbsp;';
                $errmsg .= '</br>';
                $errmesg = $this->loadPo('This is not a Video File. Please Select Video File Only!') . " : <br>" . $errmsg;
                $this->log($this->user, $errmesg);
            } else {
                //$fsize = ftp_size($ftp_conn, $server_file);
                $data = ftp_get($ftp_conn, $local_file, $server_file, FTP_BINARY, 0);
				if ($data == 1) {
					//if($this->amazons3){
						$finfo = new finfo; 
						$fileinfo = $finfo->file($local_file, FILEINFO_MIME);
						list($content_type,$binary) = explode(";",$fileinfo);
						$videoresult = $this->uploadVideoAS3($local_file, $fileNameUniq, trim($content_type));
					/* } else {
						$videoresult ='1';
					} */
					if($videoresult) {
						$fileData = array();
						$fileData['title'] = $fileNameUniq;
						$fileData['uid'] = $this->uid;
						$fileData['type'] = 'FTP';
						$fileData['category'] = '0';
						//$contentData['created'] = date('Y-m-d');
						$fileData['filename'] = $fileNameUniq;
						$fileData['uid'] = $this->uid;
						//$fileData['created'] = date('Y-m-d');
						$fileData['relative_path'] = $uploadPath . $fileNameUniq;
						$fileData['absolute_path'] = amazonFileUrl . $fileNameUniq;
						$fileData['minetype'] = "video/" . $fileExt;
						$fileData['type'] = $fileExt;
						$fileData['status'] = '0';
						$fileData['info'] = base64_encode($fileNameUniq);
						$lastId = $this->videos_model->_saveVideo($fileData);
						$lastFileId = $this->videos_model->insert_file($fileData);
							$msg = "";
							$msg .= $fileName . '&nbsp;&nbsp;';
							$msg .= '</br>';
							$message = $this->loadPo('This video content is not available in directory') . " : <br>" . $msg;
							$this->log($this->user, $message);

						//if($this->amazons3){
							if (isset($local_file)) {
								unlink($local_file);
							}
						//}
					} else {
						$msg = $this->loadPo('Upload failed.');
						$data['message'] = sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-warning alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>',$msg);
						echo json_encode($data);
					}
                }
            }
        }
        // close connection and file handler
        ftp_close($ftp_conn);
        //$data['success'] = '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$message.'</div></div></section>';
        //$data['error'] = '<section class="content"><div class="col-xs-12"><div class="alert alert-warning alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$errmesg.'</div></div></section>';
        echo json_encode($data);

        //exit;
    }

       public function Size_file() {
            $incoming_original = $this->input->post('chk');
            $incoming_size = $this->input->post('files');
            $extension = end(explode(".", $incoming_original));
            $video_name = $incoming_original;
            $local_file = base_url()."assets/videos/" . $incoming_original;
            $Size1 = filesize($local_file);
            $Size = round((intval(trim($Size1)) / 1048576), 2);
            echo $Size;
        }
    
        function video_scheduling() {
            $id = $this->data['id'];
            if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                $post['content_id'] = $id;
                $post['schedule'] = $this->input->post('r2');
                $data = $this->videos_model->get_scheduling($id);
                if(isset($data[0])){
                if ($data[0]->content_id == $id) {
                    if ($this->input->post('r2') == "Always") {
                        $post['startDate'] = '';
                        $post['endDate'] = '';
                        $this->videos_model->update_scheduling($post);
                        $msg = $this->loadPo('Video Scheduling save successfully.');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        //redirect('video');
                    } else {
                        $post['startDate'] = date('Y-m-d', strtotime($this->input->post('datepickerstart'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerstart')));
                        $post['endDate'] = date('Y-m-d', strtotime($this->input->post('datepickerend'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerend')));
                        $this->form_validation->set_rules($this->validation_rules['video_schedule']);
                        if ($this->form_validation->run()) {
                            $this->videos_model->update_scheduling($post);
                            $msg = $this->loadPo('Video Scheduling save successfully.');
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            //redirect('video');	
                        } else {
                            $this->data['schedule'] = $this->videos_model->get_scheduling($id);
                            $this->show_video_view('videoEditScheduling', $this->data);
                        }
                    }
            }} else {
                    if ($this->input->post('r2') == "Always") {
                        $this->videos_model->save_scheduling($post);
                        $msg = $this->loadPo('Video Scheduling save successfully.');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        //redirect('video');
                    } else {
                        $post['startDate'] = date('Y-m-d', strtotime($this->input->post('datepickerstart'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerstart')));
                        $post['endDate'] = date('Y-m-d', strtotime($this->input->post('datepickerend'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerend')));
                        $this->form_validation->set_rules($this->validation_rules['video_schedule']);
                        if ($this->form_validation->run()) {
                            $this->videos_model->save_scheduling($post);
                            $msg = $this->loadPo('Video Scheduling save successfully.');
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            //redirect('video');	
                        } else {
                            $this->data['schedule'] = $this->videos_model->get_scheduling($id);
                            $this->show_video_view('videoEditScheduling', $this->data);
                        }
                    }
                }
                $this->data['schedule'] = $this->videos_model->get_scheduling($id);
                $this->show_video_view('videoEditScheduling', $this->data);
            } else {
                $this->data['schedule'] = $this->videos_model->get_scheduling($id);
                $this->show_video_view('videoEditScheduling', $this->data);
            }
        }
    
        /* function used for video setting section starts */
    
        function setting() {
            $this->data['welcome'] = $this;
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $tab = $this->uri->segment(3);
            $this->data['tab'] = $tab;
            $this->data['flavorData'] = $this->videos_model->getFlavorData();
            $optionData = $this->videos_model->getOptionData($uid);
            $this->data['optionData'] = @unserialize(strip_slashes($optionData));
            switch ($tab) {
                case "Flavors":
                    $this->show_view('video_settings', $this->data);
                    break;
                case "Player":
                    $this->data['playerData'] = $this->videos_model->getPlayerData($uid);
                    $this->show_view('video_settings', $this->data);
                    break;
                            case "country":
                    $this->data['countryData'] = $this->videos_model->getCountryList();
                    $this->show_view('video_settings', $this->data);
                    break;
                default:
                    $this->data['tab'] = 'Flavors';
                    $this->show_view('video_settings', $this->data);
            }
        }
    
        function setting_flavor() {
            $data['welcome'] = $this;
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $user = $sess['0']->username;
            $data['tab'] = 'Flavor';
            $data['flavorData'] = $this->videos_model->getFlavorData();
            $settings_key = 'flavor_settings';
            $msg = $this->loadPo('Flavors Updated successfully.');
            if (isset($_POST['submit']) && $_POST['submit'] == 'Save') {
                $redirect_url = $_REQUEST['redirect_url'];
                $checkDataF = $this->videos_model->checkFlavorData($settings_key, $uid);
                $post['value'] = serialize($_POST['flavors']);
                if ($checkDataF >= 1) {
                    $post['modified'] = date('Y-m-d H:i:s');
                    $result = $this->videos_model->update_flavorOption($post, $settings_key, $uid);
                } else {
                    $post['user_id'] = $uid;
                    $post['key'] = $settings_key;
                    $post['group'] = 'flavors';
                    $post['created'] = date('Y-m-d H:i:s');
                    $result = $this->videos_model->insert_flavorOption($post);
                }
                $this->log($user, $msg);
                $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                redirect($redirect_url);
                exit;
            } else {
                //$data['allCategory'] = $this->category_model->getAllCategory();
                $this->show_view('video_settings', $data);
            }
        }
    
        function setting_player() {
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $user = $sess['0']->username;
            $data['welcome'] = $this;
            $data['tab'] = 'Player';
            $settings_key = 'player_settings';
            $msg = $this->loadPo('Player Settings Updated successfully.');
            $data['playerData'] = $this->videos_model->getPlayerData($uid);
            if (isset($_POST['submit'])) {
                $redirect_url = $_REQUEST['redirect_url'];
                $logo_imghidden = $_REQUEST['logo_imghiddennw'];
                unset($_POST['redirect_url']);
                unset($_POST['logo_imghiddennw']);
                if ($_POST['submit'] == 'Save') {
                    if ($_FILES['logo_image']['name'] != "") {
                        $new_file_name = uniqid();
                        $path = './assets/upload/logo/';
                        $config['upload_path'] = $path;
                        $config['allowed_types'] = 'png';
                        $config['file_name'] = $new_file_name;
                        $this->load->library('upload', $config);
                        if (!$this->upload->do_upload('logo_image')) {
                            $errors = $this->upload->display_errors();
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $errors));
                            redirect($redirect_url);
                        } else {
                            $datafile = $this->upload->data();
                            $_POST['file'] = $datafile['file_name'];
                        }
                    } else {
                        $_POST['file'] = $logo_imghidden;
                    }
                    $post['value'] = serialize($_POST);
                } else if ($_POST['submit'] == 'Set as default') {
                    $player = array(
                        'player_type' => 'html5',
                        'player_skin' => 'bekle',
                        'height' => '400',
                        'width' => '598',
                        'player_aspectration' => '4:3',
                        'related_video' => '0',
                        'social_sharing' => '0',
                        'repeat_video' => '0',
                        'auto_start' => '1',
                        'controls' => '1',
                        'google_analytics' => '1',
                        'mute' => '0',
                        'androidhls' => '1',
                        'logo_position' => '',
                        'logo_margin' => '2',
                        'logo_hide' => '1',
                        'caption_color' => '#000000',
                        'caption_fontsize' => '16',
                        'caption_fontfamily' => 'html5',
                        'caption_fontopacity' => '1',
                        'caption_backgroundcolor' => '#ffffff',
                        'caption_backgroundopacity' => '3',
                        'caption_windowcolor' => '#e3e3e3',
                        'caption_windowopacity' => '5',
                        'about_text' => 'cyberlinks',
                        'about_link' => 'cyberlinks'
                    );
                    $data = array(
                        'player' => $player,
                        'file' => 'test.png'
                    );
                    $post['value'] = serialize($data);
                }
    
                $checkDataF = $this->videos_model->checkFlavorData($settings_key, $uid);
    
                if ($checkDataF >= 1) {
                    $post['modified'] = date('Y-m-d H:i:s');
                    $result = $this->videos_model->update_flavorOption($post, $settings_key, $uid);
                } else {
                    $post['user_id'] = $uid;
                    $post['key'] = $settings_key;
                    $post['group'] = 'player';
                    $post['created'] = date('Y-m-d H:i:s');
                    $result = $this->videos_model->insert_flavorOption($post);
                }
                $this->log($user, $msg);
                $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                redirect($redirect_url);
                exit;
            } else {
                $this->show_view('video_settings', $data);
            }
        }
    
            function setting_country() {
            
            
            }	
            
        /* function used for video setting section starts */
    
        function video_status() {
            $this->load->library("pagination");
            $config = array();
            $config["base_url"] = base_url() . "video/video_status";
            $config["total_rows"] = $this->videos_model->getstatuscount();
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["links"] = $this->pagination->create_links();
            $data['welcome'] = $this;
            $data['status'] = $this->videos_model->getstatus($config["per_page"], $page);
            $data['total_rows'] = $config["total_rows"];
            $this->show_view('video_status', $data);
        }
		function live_streaming()
	{
        $data['welcome'] = $this;
        $this->show_view('live_streaming', $data);
	}
    
        function testlog() {
            $user = 'admin';
            $id = $_GET['id'];
            $msg = 'Button Click on id = ' . $id;
            $this->log($user, $msg);
            redirect(base_url() . 'video/videoprofile?action=NTQ=&');
        }
    
        /*  video profile slection page */
    
        function videoOpr() {
            $this->data['welcome'] = $this;
            $this->data['id'] = base64_decode(@$_GET['action']);
            $this->data['result'] = $this->videos_model->edit_profile($this->data['id']);
            $tab = $this->uri->segment(3);
            switch ($tab) {
                case "Basic":
                    $this->videoprofile();
                    break;
                case "Advanced":
                    $this->show_video_view('videoEditAdvance', $this->data);
                    break;
                case "Scheduling":
                    $this->video_scheduling();
                    break;
                case "Thumbnail":
                    $this->thumbnails();
                    break;
                case "Flavor":
                    $this->flavors();
                    //	$this->show_video_view('videoEditFlavor',$this->data);
                    break;
                case "Promo":
                    $this->promo();
                    //	$this->show_video_view('videoEditFlavor',$this->data);
                    break;
                default:
                    $this->show_video_view('videoEditBasic', $this->data);
            }
        }
    
        /*    Upload  thumbnail images(4/8/14 ry)  */
    
        function thumbnails() {
            $sess = $this->session->all_userdata();
            $this->data['welcome'] = $this;
            $id = @$_GET['action'];
            $vid = base64_decode($id);
            if ($vid) {
                $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
                $this->data['content_id'] = $vid;
                $this->data['videoInfo'] = $this->videos_model->get_videoInfo($vid);
                $this->data['defaultThumbInfo'] = $this->videos_model->get_defaultThumb($vid);
                $this->show_video_view('videoEditThumbnail', $this->data);
            }
        }
    
        function upload_thumb() {
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $this->data['welcome'] = $this;
            $content_id = $_REQUEST['content_id'];
            $redirect_url = $_REQUEST['redirect_url'];
            $tab = $this->uri->segment(3);
            if ($content_id != "") {
                if (isset($_FILES['thumb_img']['tmp_name']) && $_FILES['thumb_img']['tmp_name'] != "") {
                    $incoming_tmp = $_FILES['thumb_img']['tmp_name']; 
                    $incoming_original = $_FILES['thumb_img']["name"];				
                                    $content_type = $_FILES['thumb_img']['type'];
                    $extension = end(explode(".", $incoming_original));
                    $incoming_original = uniqid().".".$extension;
                    $allowed = array('gif', 'png', 'jpeg', 'jpg');
                    if (!in_array($extension, $allowed)) {
                                                    $msg = $this->loadPo('File type not supported.');
                                                    $this->log($this->user, $msg);
                                                    $this->data['error'] = $msg;
                                                    $this->show_video_view('videoEditThumbnail', $this->data);                
                                    } else {
                       $videoresult = $this->uploadVideoAS3($incoming_tmp, $incoming_original, $content_type); 
                                            if($videoresult) {
                                                    list($width, $height, $type, $attr) = getimagesize($_FILES['thumb_img']['tmp_name']);
                                                    switch ($type) {
                                                            case "1":
                                                                    $imageType = 'image/gif';
                                                                    $type = 'thumbnail';
                                                                    break;
                                                            case "2":
                                                                    $imageType = 'image/jpg';
                                                                    $type = 'thumbnail';
                                                                    break;
                                                            case "3":
                                                                    $imageType = 'image/png';
                                                                    $type = 'thumbnail';
                                                                    break;
                                                            default:
                                                                    $imageType = 'image';
                                                                    $type = 'thumbnail';
                                                    }
                                                    $post['filename'] = $incoming_original;
                                                    $post['type'] = $type;
                                                    $post['minetype'] = $imageType;
                                                    $post['width'] = $width;
                                                    $post['height'] = $height;
                                                    $post['relative_path'] = amazonFileUrl.$incoming_original;
                                                    $post['absolute_path'] = amazonFileUrl.$incoming_original;
                                                    $post['status'] = '0';
                                                    $post['uid'] = $uid;
                                                    $post['created'] = date('Y-m-d');
                                                    $data_postThumb = @serialize($post);
                                                    $data_thumb = base64_encode($data_postThumb);
                                                    $post['info'] = $data_thumb;
                                                    $file_id = $this->videos_model->insert_file($post);
                                                    if ($file_id) {
                                                            $thumbdata['content_id'] = $content_id;
                                                            $thumbdata['file_id'] = $file_id;
                                                            $thumbdata['created'] = date('Y-m-d');
                                                            $file_id = $this->videos_model->insert_thumb($thumbdata);
                                                            $msg = $this->loadPo('Default Image Uploaded successfully.');
                                                            $this->log($this->user, $msg);
                                                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                                                            redirect($redirect_url);
                                                    }
                        } else {
                                                    $msg = $this->loadPo('Upload failed.');
                                                    $this->log($this->user, $msg);
                                                    $this->data['error'] = $msg;
                                                    $this->show_video_view('videoEditThumbnail', $this->data);
                                            }
                    }
                                    //$videoresult = $this->upload_move_file($incoming_tmp, $incoming_original, $path);
                } else {
                    $this->show_video_view('videoEditThumbnail', $this->data);
                }		
            } else {
                redirect($redirect_url);
            }
        }
    
        function crop_thumb() {
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $thumb_imgName = $_REQUEST['thumb_imgName'];
            $dir_path = getcwd();
            $path_nw = str_replace('\\', '/', $dir_path); // Replace backslashes with forwardslashes
            $content_id = $_REQUEST['content_id'];
            $redirect_url = $_REQUEST['redirect_url'];
            $ext = end(explode(".", $thumb_imgName));
            $fileName = basename($thumb_imgName, "." . $ext);
            $fileName_nw = time() . "." . $ext;
            $fileName_dest = $path_nw . '/assets/upload/thumbs/' . $fileName_nw;
            $this->data['welcome'] = $this;
            $targ_w = $targ_h = 170;
            $jpeg_quality = 90;
    
            $src = amazonFileUrl.$thumb_imgName;       
    
            switch ($ext) {
                case "1":
                    $imageType = 'image/gif';
                    $img_r = imagecreatefromgif($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                    header('Content-type: image/gif');
                    imagegif($dst_r, $fileName_dest, $jpeg_quality);
                    $type = 'thumbnail';
                    break;
                case "2":
                    $imageType = 'image/jpg';
                    $img_r = imagecreatefromjpeg($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                    header('Content-type: image/jpeg');
                    imagejpeg($dst_r, $fileName_dest, $jpeg_quality);
                    $type = 'thumbnail';
                    break;
                case "3":
                    $imageType = 'image/png';
                    $img_r = imagecreatefrompng($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                    header('Content-type: image/png');
                    imagepng($dst_r, $fileName_dest, $jpeg_quality);
                    $type = 'thumbnail';
                    break;
                case "3":
                    $imageType = 'image/jpeg';
                    $img_r = imagecreatefromjpeg($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                    header('Content-type: image/jpeg');
                    imagejpeg($dst_r, $fileName_dest, $jpeg_quality);
                    $type = 'thumbnail';
                    break;
                default:
                    $imageType = 'image';
                    $img_r = imagecreatefromjpeg($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                    header('Content-type: image/jpeg');
                    imagejpeg($dst_r, $fileName_dest, $jpeg_quality);
                    $type = 'thumbnail';
            }
                    $videoresult = $this->uploadVideoAS3($fileName_dest, $fileName_nw, $imageType); 
                    if($videoresult) {
                            $post['filename'] = $fileName_nw;
                            $post['type'] = $type;
                            $post['minetype'] = $imageType;
                            $post['width'] = $targ_w;
                            $post['height'] = $targ_h;
                            $post['relative_path'] = 'upload/thumbs/' . $fileName_nw;
                            $post['absolute_path'] = amazonFileUrl . $fileName_nw;
                            $post['status'] = '0';
                            $post['uid'] = $uid;
                            $post['created'] = date('Y-m-d');
                            $data_postThumb = @serialize($post);
                            $data_thumb = base64_encode($data_postThumb);
                            $post['info'] = $data_thumb;
    
                            $file_id = $this->videos_model->insert_file($post);
                            if ($file_id) {
                                    $thumbdata['content_id'] = $content_id;
                                    $thumbdata['file_id'] = $file_id;
                                    $thumbdata['created'] = date('Y-m-d');
                                    $result = $this->videos_model->insert_thumb($thumbdata);
                                    $msg = $this->loadPo('Image Croped successfully.');
                                    $this->log($this->user, $msg);
                                    $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            }
                if (isset($fileName_dest)) {
                    unlink($fileName_dest);
                            }
                    } else {
                            $msg = $this->loadPo('Upload failed.');
                            $this->log($this->user, $msg);
                            $this->data['message'] = $msg;
                            $this->show_video_view('videoEditThumbnail', $this->data);
                    }
            redirect($redirect_url);
        }
    
        public function set_defltimage() {
            $sess = $this->session->all_userdata();
            $content_id = @$_GET['cid'];
            $file_id = @$_GET['fid'];
            $redirect_url = @$_GET['redirect_url'];
            $result = $this->videos_model->update_defaultImg($content_id, $file_id);
            $msg = $this->loadPo('Default Image Updated successfully.');
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
            redirect($redirect_url);
        }
    
        public function delele_thumbimage() {
            $sess = $this->session->all_userdata();
            $content_id = @$_GET['cid'];
            $file_id = @$_GET['fid'];
            $redirect_url = @$_GET['redirect_url'];
            $fileInfo = $this->videos_model->get_thumbImgName($file_id);
            foreach ($fileInfo as $filename) {
                            $delResult = $this->deleteFileS3($filename->name);
                if ($delResult) {
                    $result = $this->videos_model->delele_thumbImg($content_id, $file_id);
                    $msg = $this->loadPo('Image deleted successfully.');
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                    redirect($redirect_url);
                } else {
                                    $msg = $this->loadPo('File is not available in directory');
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                    redirect($redirect_url);
                            }
            }
        }
    
        function download_thumb() {
            $sess = $this->session->all_userdata();
            $fileName = @$_GET['fileName'];
            $redirect_url = @$_GET['redirect_url'];
            $fullPath = amazonFileUrl.$fileName;
                    $downloadResultS3 = $this->downloadFileS3($fileName, $fullPath);
                    if($downloadResultS3)
                    {
    
                    } else {
                $msg = $this->loadPo('File Not Found.');
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                redirect($redirect_url);
            }
        }
    
        /*    Upload  thumbnail images(4/8/14 ry)  */
        /*    Edit Flavors Settings(sd)  */
    
        function flavors() {
            $sess = $this->session->all_userdata();
            $this->data['welcome'] = $this;
            $id = @$_GET['action'];
            $vid = base64_decode($id);
            if ($vid) {
                $s = $this->data['setting'] = $this->videos_model->getsetting($vid);
                //echo '<pre>'; print_r($s); die;
                $this->data['content_id'] = $vid;
                $this->show_video_view('videoEditFlavor', $this->data);
            }
        }
    
        function changeStatus() {
            $sess = $this->session->all_userdata();
            $curr_url = $_GET['redirecturl'];
            unset($_GET['redirecturl']);
            $data = $_GET;
            if ($data['status'] == 'pending') {
                $this->videos_model->changestatus($data);
            }
            $s = $this->videos_model->checkstatus($data);
            if ($data['status'] == 'inprocess') {
                $this->videos_model->deletestatus($s);
            }
            $user = $sess[0]->username;
            $id = $data['content_id'];
            $msg = $this->loadPo('Flower status change for Content id') . '=' . $id;
            $this->log($user, $msg);
            if ($data['status'] == 'pending' || $data['status'] == 'inprocess') {
                //echo 'hello';
    
                $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                redirect($curr_url);
            }
        }
    
        /*    Edit Flavors Settings(sd)  */
    
        /* function for video upload from other source starts */
    
        function videoUploadSrc() {
            $per = $this->checkpermission($this->role_id, 'add');
            if ($per) {
                $this->data['welcome'] = $this;
                //$this->data['result'] = $this->videos_model->edit_profile($this->data['id']);
                $tab = $this->uri->segment(3);
                switch ($tab) {
                    case "Upload":
                        //$this->upload();
                        $this->data['tab'] = $tab;
                        $this->show_view('upload_video', $this->data);
                        break;
                    case "Other":
                        //$this->upload_other(); 
                        $this->data['tab'] = $tab;
                        $this->data['allCategory'] = $this->category_model->getAllCategory();
                        $this->show_view('upload_video', $this->data);
                        break;
                    default:
                        $this->data['tab'] = 'Upload';
                        $this->show_view('upload_video', $this->data);
                }
            } else {
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin! You Don\'t do this, Contect Admin</div></div></section>');
                redirect(base_url() . 'video');
            }
        }
    
        function upload_other() {
            $data['welcome'] = $this;
            $sess = $this->session->all_userdata();
            $uid = $sess[0]->id;
            $data['tab'] = 'Other';
            if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                $redirect_url = $_REQUEST['redirect_url'];
                $post['source_url'] = $this->input->post('source_url');
                $post['content_provider'] = $this->input->post('content_provider');
                $post['uid'] = $uid;
                $post['created'] = date('Y-m-d');
                $this->form_validation->set_rules($this->validation_rules['videosrc_info']);
                if ($this->form_validation->run()) {
                    $result = $this->videos_model->checkVideoSrc($post['source_url']);
                    if ($result == 0) {
                        $datacontent['title'] = $this->input->post('title');
                        $datacontent['description'] = $this->input->post('description');
                        $datacontent['type'] = 'Source Url';
                        $datacontent['category'] = $this->input->post('category');
                        $datacontent['uid'] = $uid;
                        $datacontent['created'] = date('Y-m-d');
                        $last_id = $this->videos_model->upload_video($datacontent);
                        if ($last_id != '') {
                            $post['content_id'] = $last_id;
                            $this->videos_model->saveVideoSrc($post);
                            $msg = $this->loadPo('Video Source Added successfully.');
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            redirect($redirect_url);
                        }
                    } else {
                        $msg = $this->loadPo('Video Source Already exists.');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-warning alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        redirect($redirect_url);
                    }
                } else {
                    $data['allCategory'] = $this->category_model->getAllCategory();
                    $this->show_view('upload_video', $data);
                }
            } else {
                $data['allCategory'] = $this->category_model->getAllCategory();
                $this->show_view('upload_video', $data);
            }
        }
        /* function for video upload from other source ends */
        /* function used for promo section starts */
        function promo() {
            $sess = $this->session->all_userdata();
            $this->data['welcome'] = $this;
            $id = @$_GET['action'];
            $vid = base64_decode($id);
            $dir_path = getcwd();
            $path_nw = str_replace('\\', '/', $dir_path);
            $path = $path_nw . '/assets/upload/video/';
            if ($vid) {
                $this->data['promo_info'] = $this->videos_model->get_promos($vid);
                $this->data['content_id'] = $vid;
                $this->data['playerData'] = $this->videos_model->getPlayerData($this->uid);
                $videoFileName = $this->videos_model->get_videoInfo($vid);
                if (file_exists($path . $videoFileName)) {
                    $videoFileInfo = $this->getVideoFileSize($videoFileName, $path);
                    $this->data['videoFileSize'] = $videoFileInfo['playtime_seconds'];
                    $this->data['videoFileName'] = $videoFileName;
                } else {
                    $this->data['videoFileSize'] = '';
                    $this->data['videoFileName'] = '';
                }
                $this->data['defaultPromoInfo'] = $this->videos_model->get_defaultPromo($vid);
                $this->show_video_view('videoPromo', $this->data);
            }
        }
    
        function promoInfo() {
            $sess = $this->session->all_userdata();
            $this->data['welcome'] = $this;
            echo $start_time = $_REQUEST['start_time_video'];
            echo $end_time = $_REQUEST['end_time_video'];
            exit;
        }
    /* function used for promo section ends */    
        function detail(){
            $data['welcome'] = $this;
            $id = $this->uri->segment(3);
            if($id == ""){
                redirect(base_url().'video');
            }
            $result = $this->videos_model->video_detail($id);
            $data['result'] = $result;
         
            $this->show_view('videodetail', $data);
        }
    /* FUNCTION FOR YOUTUBE */
        function youtube(){
            $videoUrl = $_POST['url'];
            preg_match('%https?://(?:www\.)?youtube\.com/watch\?v=([^&]+)%', $videoUrl, $matches);
            if($matches){
                $post = array();
                $tmp = $this->get_youtube($videoUrl);
                
                $youtubeData = $tmp['detail']['entry']->{'media$group'};
                $post['content_title'] = $tmp['content']['title'];
                $post['description'] = $youtubeData->{'media$description'}->{'$t'};
                $post['uid'] = $this->uid;
                $post['created'] = date('Y-m-d');
                $post['type'] = 'youtube';
                $post['filename'] = $videoUrl;
                $post['uid'] = $this->uid;
                $post['created'] = date('Y-m-d');
                $post['relative_path'] = $videoUrl;
                $post['absolute_path'] = $videoUrl;
                $post['status'] = '0';
                $post['type'] = 'Youtube';
                $post['minetype'] = "";
                $post['info'] = base64_encode($videoUrl);
                $last_id = $this->videos_model->_saveVideo($post);
                //Save keywords
                $post_keydata = $tmp['content']['keywords'];
                $this->videos_model->_setKeyword(trim($post_keydata),$last_id);
                $id = base64_encode($last_id);
                redirect(base_url().'video/videoOpr/Basic?action='.$id. '&');
                }else{
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Invalid Youtube URL</div></div></section>');
                redirect($_POST['redirect_url']);
            }
        }
    
    }
    
    
    
    /* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */
            
