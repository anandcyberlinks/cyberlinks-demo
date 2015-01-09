<?php
error_reporting(1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ads extends MY_Controller {

    public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedVideoExt;
    public $allowedImageExt;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('ads/Ads_model');
        $this->load->model('category_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('csvreader');
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
        $this->allowedVideoExt = array('mp4', 'mpg', 'mpeg', 'flv', 'wmv', 'avi');
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
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

    /*
      /
      /--------------------------------------------------------------------------------
      /   function to show list of videos
      /--------------------------------------------------------------------------------
      /
     */

    function index() {
        $searchterm = '';
        if ($this->uri->segment(2) == '') {
            $this->session->unset_userdata('search_form');
        }
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
                $sort = 'a.ad_title';
                if ($sort_by == 'asc')
                    $data['show_t'] = 'desc';
                else
                    $data['show_t'] = 'asc';
                break;
            default:
                $sort_by = 'desc';
                $sort = 'a.id';
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "ads/index/";
        $config["total_rows"] = $this->Ads_model->get_videocount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->Ads_model->get_video($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['category'] = $this->Ads_model->get_category($this->uid);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('ads/search_video', $data);
    }

    /*
      /
      /********************************************************************************
      /   Video Edit Section Starts
      /********************************************************************************
      /
     */

    function videoOpr() {
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $this->data['welcome'] = $this;
            $this->data['id'] = base64_decode(@$_GET['action']);
            $this->data['result'] = $this->Ads_model->edit_profile($this->data['id']);
            $tab = $this->uri->segment(3);
            switch ($tab) {
                case "Basic":
                    $this->videoprofile();
                    break;
                case "Advanced":
                    $this->videoprofileadvance();
                    //$this->show_ads_view('videoEditAdvance', $this->data);
                    break;
                case "Scheduling":
                    $this->video_scheduling();
                    break;
                case "Thumbnail":
                    $this->thumbnails();
                    break;
                case "Flavor":
                    $this->flavors();
                    //	$this->show_ads_view('videoEditFlavor',$this->data);
                    break;
                case "Promo":
                    $this->promo();
                    //	$this->show_ads_view('videoEditFlavor',$this->data);
                    break;
                default:
                    $this->show_ads_view('ads/videoEditBasic', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'ads');
        }
    }

    /*
      /----------------------------------------------------------------
      /   function to show video information or update video details
      /----------------------------------------------------------------
     */

    function videoprofile() {
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
                 //   $post['feature_video'] = $this->input->post('feature_video') == 'on' ? 1 : 0;
                  //  $post_key = $_POST['tags'];
                    $this->Ads_model->_saveVideo($post);
                   // $this->Ads_model->_setKeyword($post_key, $vid);
                    $msg = $this->loadPo($this->config->item('success_record_update'));
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', $this->_successmsg($msg));
                    redirect('ads');
                } else {
                    $this->data['result'] = (array) $this->Ads_model->edit_profile($vid);                    
                    $this->data['thumbnails_info'] = $this->Ads_model->get_thumbs($vid);
                    $this->data['content_id'] = $vid;
                    $this->data['category'] = $this->Ads_model->get_category($this->uid);
                    //$this->data['genre'] = $this->Genre_model->getAllGenre();
                   // $this->data['setting'] = $this->videos_model->getsetting($vid);
                   // $this->data['countryData'] = $this->videos_model->getCountryList();
                    $this->show_ads_view('ads/videoEditBasic', $this->data);
                }
            } else {
                $this->data['result'] = (array) $this->Ads_model->edit_profile($vid);               
                $this->data['thumbnails_info'] = $this->Ads_model->get_thumbs($vid);
                $this->data['content_id'] = $vid;
                $this->data['category'] = $this->Ads_model->get_category($this->uid);                
                //$this->data['setting'] = $this->Ads_model->getsetting($vid);
                //$this->data['countryData'] = $this->Ads_model->getCountryList();
                $this->show_ads_view('ads/videoEditBasic', $this->data);
            }
        }
    }

   

    function flavors() {
        $sess = $this->session->all_userdata();
        $this->data['welcome'] = $this;
        $id = @$_GET['action'];
        $vid = base64_decode($id);
        if ($vid) {
            $s = $this->data['setting'] = $this->videos_model->getsetting($vid);
            $this->data['content_id'] = $vid;
            $this->show_ads_view('videoEditFlavor', $this->data);
        }
    }

    /*
      /********************************************************************************
      /   VIDEO UPLOAD(FORM/YOUTUBE/OTHER) SECTION STARTS
      /********************************************************************************
     */

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
                    $this->show_view('ads/upload_video', $this->data);
                    break;
                case "Other":
                    //$this->upload_other(); 
                    $this->data['tab'] = $tab;
                    $this->show_view('upload_video', $this->data);
                    break;
                case "Youtube":
                    //$this->upload_other(); 
                    $this->data['tab'] = $tab;
                    $this->show_view('upload_video', $this->data);
                    break;
                default:
                    $this->data['tab'] = 'Upload';
                    $this->show_view('upload_video', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'video');
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to upload video file using form
      /--------------------------------------------------------------------------------
     */

    function upload() {
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $this->data['welcome'] = $this;
            if (isset($_FILES['0']['tmp_name']) && $_FILES['0']['tmp_name'] != "") {
                $tmpFilePath = $_FILES['0']['tmp_name'];
                $originalFileName = $_FILES["0"]["name"];
                $fileExt = $this->_getFileExtension($originalFileName);
                $fileUniqueName = uniqid() . "." . $fileExt;
                if (!in_array($fileExt, $this->allowedVideoExt)) {
                    $data['flag'] = '0';
                    $message = $this->loadPo($this->config->item('error_file_format'));
                    $data['message'] = $this->_errormsg($message);
                    echo json_encode($data);
                } else {
                    $videoresult = $this->_upload($tmpFilePath, $fileUniqueName, 'ads');
                    if ($videoresult) {
                        $data = array();
                        $data['content_title'] = $fileUniqueName;
                        $data['uid'] = $this->uid;
                        $data['filename'] = $fileUniqueName;
                        $data['relative_path'] = serverAdsRelPath . $fileUniqueName;
                        $data['absolute_path'] = REAL_PATH . serverAdsRelPath . $fileUniqueName;
                        $data['minetype'] = "video/" . $fileExt;
                        $data['type'] = $fileExt;
                        $data['status'] = '0';
                        $data['info'] = base64_encode($fileUniqueName);
                        $last_id = $this->Ads_model->_saveVideo($data);                                               
            
                        $msg = $this->loadPo($this->config->item('success_file_upload'));
                        $this->log($this->user, $msg);
                        $data['id'] = base64_encode($last_id);
                        $data['message'] = $this->_successmsg($msg);
                        echo json_encode($data);
                    } else {
                        $msg = $this->loadPo($this->config->item('error_file_upload'));
                        $data['message'] = $this->_errormsg($msg);
                        echo json_encode($data);
                    }
                }
            } else {
                $this->show_view('ads/upload_video', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'ads');
        }
    }

    /*
      / ********************************************************************************
      /   BULK UPLOAD(CSV/FTP) SECTION STARTS
      / ********************************************************************************
     */

    function bulkupload() {
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $this->data['welcome'] = $this;
            //$this->data['result'] = $this->videos_model->edit_profile($this->data['id']);
            $tab = $this->uri->segment(3);
            switch ($tab) {
                case "csv":
                    $this->data['tab'] = $tab;
                    $this->show_view('ads/bulkupload', $this->data);
                    break;
                case "ftp":
                    $this->data['tab'] = $tab;
                    $this->show_view('ads/bulkupload', $this->data);
                    break;
                default:
                    $this->data['tab'] = 'csv';
                    $this->show_view('ads/bulkupload', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'ads');
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to upload video files usnig csv
      /--------------------------------------------------------------------------------
      /
      /
     */

    function csvupload() {
        $this->data['welcome'] = $this;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $content_title = $_POST['content_title'];
            $category = $_POST['category'];
           // $keywords = $_POST['keyword'];
            $url = $_POST['url'];
            $description = $_POST['description'];
            $rowID = $_POST['rowId'];
            if ($this->remoteFileExists($url)) {
                $videoFileSrcPath = $url;
                $videoFileExt = $this->_getFileExtensionUrl($videoFileSrcPath);
                $videoFileUniqName = uniqid() . "." . $videoFileExt;
                $post['source_url'] = $videoFileUniqName;
                $post['content_provider'] = 'selfvideo';
                $post['uid'] = $this->uid;
                $post['created'] = date('Y-m-d');
                $catId = $this->category_model->getCatId($category, $this->uid);
                if (in_array($videoFileExt, $this->allowedVideoExt)) {
                    $fieDestPath = REAL_PATH . serverAdsRelPath . $videoFileUniqName;
                    $videoresult = $this->_uploadFileCurl($videoFileSrcPath, $fieDestPath, $videoFileUniqName);
                    if ($videoresult == '1') {
                        $contentData = array();
                        $contentData['content_title'] = isset($content_title) && $content_title != '' ? $content_title : 'content_title';
                        $contentData['description'] = isset($description) && $description != '' ? $description : 'description';
                        $contentData['type'] = 'CSV';
                        $contentData['category'] = $catId;
                        $contentData['uid'] = $this->uid;
                        $contentData['filename'] = $videoFileUniqName;
                        $contentData['relative_path'] = serverAdsRelPath . $videoFileUniqName;
                        $contentData['absolute_path'] = REAL_PATH . serverAdsRelPath . $videoFileUniqName;
                        $contentData['minetype'] = "video/" . $videoFileExt;
                        $contentData['type'] = $videoFileExt;
                        $contentData['status'] = '1';
                        $contentData['info'] = base64_encode($videoFileUniqName);
                        $lastId = $this->Ads_model->_saveVideo($contentData);
                       
                        if ($lastId != '') {
                            $message = $this->loadPo($this->config->item('success_file_upload'));
                            $this->log($this->user, $message);
                            //$data['message'] = $this->_successmsg($message);
                            $data['message'] = 'success';
                            $data['rowId'] = $rowID;
                        }
                    } else {
                        $message = $this->loadPo($this->config->item('error_file_upload'));
                        $this->log($this->user, $message);
                        //$data['message'] = $this->_errormsg($message);
                        $data['message'] = 'error';
                        $data['rowId'] = $rowID;
                    }
                } else {
                    $message = $this->loadPo($this->config->item('error_file_format'));
                    $this->log($this->user, $message);
                    //$data['message'] = $this->_errormsg($message);
                    $data['message'] = 'error';
                    $data['rowId'] = $rowID;
                }
            } else {
                //$data['message'] = $this->_errormsg($this->loadPo($row['url'].$this->config->item('error_file_found')));
                $data['message'] = 'error';
                $data['rowId'] = $rowID;
            }
            echo json_encode($data);
            //$this->show_view('bulkupload', $this->data);
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'ads');
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to connect with ftp
      /--------------------------------------------------------------------------------
      /
      / 	$ftp_server            = server host name
      / 	$ftp_username          = username
      /   $ftp_userpass          = Password
      /
     */

    public function ftpLogin() {
        $ftp_server = $_POST['ftpserver'];
        $ftp_username = $_POST['username'];
        $ftp_userpass = $_POST['password'];
        $ftp_path = $_POST['ftpPath'];
        if ($ftp_server != "" && $ftp_username != "" && $ftp_userpass != "") {
            $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
            if ($ftp_conn) {
                $ftpLogin = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
                if ($ftpLogin) {
                    $ftp_path = $ftp_path != '' ? $ftp_path : '/';
                    $rawfiles = ftp_rawlist($ftp_conn, $ftp_path); //true being for recursive					
                    $tmpData = array();
                    $result = '';
                    $message = '';
                    $response['data'] = array();
                    if ($ftp_path != '/') {
                        $tmp = explode('/', substr($_POST['ftpPath'], 0, -1));
                        array_pop($tmp);
                        $tmp = implode('/', $tmp) . '/';
                        $tmpData['dir'][] = array('name' => '..', 'path' => $tmp);
                    }
                    foreach ($rawfiles as $rawfile) {
                        $info = preg_split("/[\s]+/", $rawfile, 9);
                        if (substr($info[0], 0, 1) == 'd') {
                            $tmp = $ftp_path == '/' ? '/' . $info[8] . '/' : $ftp_path . $info[8] . '/';
                            $tmpData['dir'][] = array('name' => $info[8], 'path' => $tmp);
                        } else {
                            $tmpData['file'][] = $info;
                        }
                    }
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


                    if (isset($tmpData['file'])) {
                        foreach ($tmpData['file'] as $key => $val) {
                            $fileExt = $this->_getFileExtension($val[8]);
                            if (in_array($fileExt, $this->allowedVideoExt)) {
                                $size = round((intval(trim($val[4])) / 1048576), 2);
                                $modified = date('d/m/Y', strtotime($info[6] . ' ' . $info[5] . ' ' . $info[7]));
                                $response['data'] .= '<tr class="unread">';
                                $response['data'] .= '<td class="small-col"><input alt="' . $size . '"class="ftpcheck" name="files[]" type="checkbox" value="' . $_POST['ftpPath'] . $val[8] . '" /></td>';
                                $response['data'] .= '<td class="small-col">' . $val[8] . '</td>';
                                $response['data'] .= '<td class="small-col">' . $size . '&nbsp;MB</td>';
                                $response['data'] .= '<td class="small-col">' . $fileExt . '</td>';
                                $response['data'] .= '<td class="small-col">' . $modified . '</td>';
                                $response['data'] .= '<td class="small-col" id="td_' . $size . '">' . $val[0] . '</td>';
                                $response['data'] .= '</tr>';
                            }
                        }
                    }
                    $result = 'success';
                    $response['data'] .= '</table></div>';
                } else {
                    $result = 'error';
                    $message = $this->_warningmsg($this->loadPo($this->config->item('warning_username_password')));
                }
            } else {
                $result = 'error';
                $message = $this->_warningmsg($this->loadPo($this->config->item('error_server_connect')));
            }
        } else {
            $result = 'error';
            $message = $this->_warningmsg($this->loadPo($this->config->item('warning_fields_empty')));
        }
        $response['status'] = $result;
        $response['message'] = $message;
        echo json_encode($response);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to download files from ftp server
      /--------------------------------------------------------------------------------
      /
      / 	$ftp_server            = server host name
      / 	$ftp_username          = username
      /   $ftp_userpass          = Password
      /
     */

    public function uploadFtp() {
        // connect and login to FTP server
        $ftp_server = $_POST['ftpserver'];
        $ftp_username = $_POST['username'];
        $ftp_userpass = $_POST['password'];
        $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
        $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
        //$redirect_url = $_POST['redirect_url'];
        $fileNameArr = $_POST['chk'];
        $filesArr = explode(",", rtrim($fileNameArr, ','));
        for ($i = 0; $i < count($filesArr); $i++) {
            $fileName = $filesArr[$i];
            $fileExt = $this->_getFileExtension($fileName);
            $fileNameUniq = uniqid() . "." . $fileExt;
            $fieSrcPath = $this->input->post('ftpPath') . $fileName;
            $fieDestPath = REAL_PATH . serverAdsRelPath . $fileNameUniq;
            $msg = "";
            if (!in_array($fileExt, $this->allowedVideoExt)) {
                $msg = $this->loadPo($this->config->item('error_file_format') . $fileName);
                $this->log($this->user, $msg);
                $data['message'] = $this->_errormsg($msg);
            } else {
                $videoresult = $this->_downloadFileFtp($fieSrcPath, $fieDestPath, $ftp_conn);
                if ($videoresult) {
                    $fileInfo = $this->getFileInfo($fieDestPath);
                    $mimeType = $fileInfo['mime_type'];
                    $data = array();
                    $data['content_title'] = $fileNameUniq;
                    $data['uid'] = $this->uid;
                    $data['type'] = 'FTP';
                    $data['filename'] = $fileNameUniq;
                    $data['relative_path'] = serverAdsRelPath . $fileNameUniq;
                    $data['absolute_path'] = REAL_PATH . serverAdsRelPath . $fileNameUniq;
                    $data['minetype'] = $mimeType;
                    $data['type'] = $fileExt;
                    $data['status'] = '0';
                    $data['info'] = base64_encode($fileNameUniq);
                    $last_id = $this->Ads_model->_saveVideo($data);
                    $msg = $this->loadPo($this->config->item('success_file_upload'));
                    $this->log($this->user, $msg);
                    $data['id'] = base64_encode($last_id);
                    $data['message'] = $this->_successmsg($msg);
                } else {
                    $msg = $this->loadPo($this->config->item('error_file_upload'));
                    $this->log($this->user, $msg);
                    $data['message'] = $this->_errormsg($msg);
                }
            }
        }
        ftp_close($ftp_conn);
        echo json_encode($data);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to check remote file existance
      /--------------------------------------------------------------------------------
      /
      /   $url = remote file path
     */

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

    /*
      /--------------------------------------------------------------------------------
      /   function used to get video file size
      /--------------------------------------------------------------------------------
     */

    public function Size_file() {
        $incoming_original = $this->input->post('chk');
        $incoming_size = $this->input->post('files');
        $extension = end(explode(".", $incoming_original));
        $video_name = $incoming_original;
        $local_file = base_url() . "assets/videos/" . $incoming_original;
        $Size1 = filesize($local_file);
        $Size = round((intval(trim($Size1)) / 1048576), 2);
        echo $Size;
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to delete video(from server/database)
      /--------------------------------------------------------------------------------
     */

    function deletevideo() {
        $per = $this->checkpermission($this->role_id, 'delete');
        $redirect_url = $_GET['curl'];
        if ($per) {
            $sess = $this->session->all_userdata();
            $id = $_GET['id'];
            $fileName = $this->Ads_model->get_videoInfo($id);
            $thumbInfo = $this->Ads_model->getThumbsInfo($id);
            if (!empty($thumbInfo)) {
                $thumbCount = count($thumbInfo);
                for ($i = 0; $i < $thumbCount; $i++) {
                    $delResultThumb = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . serverImageRelPath);
                    $delResultThumbSmall = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_SMALL_PATH);
                    $delResultThumbMedium = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_MEDIUM_PATH);
                    $delResultThumbLarge = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_LARGE_PATH);
                }
            }
            $delResult = $this->_deleteFile($fileName, REAL_PATH . serverAdsRelPath);
            $videoFileExt = $this->_getFileExtension($fileName);
            $fileNameInitial = basename($fileName, $videoFileExt);
            $delResult_2g = $this->_deleteFile($fileNameInitial . '_2g.mp4', REAL_PATH . serverAdsRelPath);
            $delResult_3g = $this->_deleteFile($fileNameInitial . '_3g.mp4', REAL_PATH . serverAdsRelPath);
            $delResult_hd = $this->_deleteFile($fileNameInitial . '_hd.mp4', REAL_PATH . serverAdsRelPath);
            $result = $this->Ads_model->delete_video($id);
            if ($result == '1') {
                $msg = $this->loadPo($this->config->item('success_record_delete'));
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', $this->_successmsg($msg));
                redirect($redirect_url);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'ads');
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used for video flavor setting
      /--------------------------------------------------------------------------------
     */

    function setting_flavor() {
        $data['welcome'] = $this;
        $sess = $this->session->all_userdata();
        $uid = $sess[0]->id;
        $user = $sess['0']->username;
        $data['tab'] = 'Flavor';
        $data['flavorData'] = $this->videos_model->getFlavorData();
        $settings_key = 'flavor_settings';
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
            $msg = $this->loadPo($this->config->item('success_flavor_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($redirect_url);
            exit;
        } else {
            //$data['allCategory'] = $this->category_model->getAllCategory();
            $this->show_view('video_settings', $data);
        }
    }

    /*
      / ********************************************************************************
      /   Thumbnail Section Starts
      / ********************************************************************************
     */

    function thumbnails() {
        $sess = $this->session->all_userdata();
        $this->data['welcome'] = $this;
        $id = @$_GET['action'];
        $vid = base64_decode($id);
        if ($vid) {
            $this->data['thumbnails_info'] = $this->Ads_model->get_thumbs($vid);
            $this->data['ads_id'] = $vid;
            $this->data['videoInfo'] = $this->Ads_model->get_videoInfo($vid);
            $this->data['defaultThumbInfo'] = $this->Ads_model->get_defaultThumb($vid);
            $this->show_ads_view('ads/videoEditThumbnail', $this->data);
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to upload thumbnail
      /--------------------------------------------------------------------------------
     */

    function upload_thumb() {
        $data['welcome'] = $this;
        $content_id = $_REQUEST['content_id'];
        $redirect_url = $_REQUEST['redirect_url'];
        if ($content_id != "") {
            if (isset($_FILES['thumb_img']['tmp_name']) && $_FILES['thumb_img']['tmp_name'] != "") {
                $tmpFileName = $_FILES['thumb_img']['tmp_name'];
                $fileExt = $this->_getFileExtension($_FILES['thumb_img']["name"]);
                $fileUniqueName = uniqid() . "." . $fileExt;
                if (!in_array($fileExt, $this->allowedImageExt)) {
                    $msg = $this->loadPo($this->config->item('error_file_format'));
                    $this->log($this->user, $msg);
                    $this->data['error'] = $msg;
                    $this->show_ads_view('videoEditThumbnail', $this->data);
                } else {
                    $videoresult = $this->_upload($tmpFileName, $fileUniqueName, 'thumb');
                    if ($videoresult) {
                        list($width, $height, $type, $attr) = getimagesize(REAL_PATH . serverImageRelPath . $fileUniqueName);
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
                        $fileData['ads_id'] = $content_id;
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
                        $last_id = $this->Ads_model->_saveThumb($fileData);
                        $msg = $this->loadPo($this->config->item('success_file_upload'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect($redirect_url);
                    } else {
                        $msg = $this->loadPo($this->config->item('error_file_upload'));
                        $data['message'] = $this->_errormsg($msg);
                        $this->show_ads_view('videoEditThumbnail', $data);
                    }
                }
                //$videoresult = $this->upload_move_file($incoming_tmp, $incoming_original, $path);
            } else {
                $this->show_ads_view('videoEditThumbnail', $this->data);
            }
        } else {
            redirect($redirect_url);
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to crop thumb
      /--------------------------------------------------------------------------------
     */

    function crop_thumb() {
        $this->data['welcome'] = $this;
        $thumb_imgName = $_REQUEST['thumb_imgName'];
        $content_id = $_REQUEST['content_id'];
        $redirect_url = $_REQUEST['redirect_url'];
        $fileExt = $this->_getFileExtension($thumb_imgName);
        $fileUniqueName = uniqid() . "." . $fileExt;
        $fileDestPath = REAL_PATH . serverImageRelPath . $fileUniqueName;
        $targ_w = $targ_h = 170;
        $jpeg_quality = 90;
        $fileSrcPath = REAL_PATH . serverImageRelPath . $thumb_imgName;

        switch ($fileExt) {
            case "1":
                $imageType = 'image/gif';
                $img_r = imagecreatefromgif($fileSrcPath);
                $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                header('Content-type: image/gif');
                imagegif($dst_r, $fileDestPath, $jpeg_quality);
                break;
            case "2":
                $imageType = 'image/jpg';
                $img_r = imagecreatefromjpeg($fileSrcPath);
                $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                header('Content-type: image/jpeg');
                imagejpeg($dst_r, $fileDestPath, $jpeg_quality);
                break;
            case "3":
                $imageType = 'image/png';
                $img_r = imagecreatefrompng($fileSrcPath);
                $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                header('Content-type: image/png');
                imagepng($dst_r, $fileDestPath, $jpeg_quality);
                break;
            case "3":
                $imageType = 'image/jpeg';
                $img_r = imagecreatefromjpeg($fileSrcPath);
                $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x'], $_POST['y'], $targ_w, $targ_h, $_POST['w'], $_POST['h']);
                header('Content-type: image/jpeg');
                imagejpeg($dst_r, $fileDestPath, $jpeg_quality);
                break;
        }
        $result = $this->_upload($fileSrcPath, $fileUniqueName, 'thumb');
        if ($result) {
            $type = 'thumbnail';
            $fileData = array();
            $fileData['content_id'] = $content_id;
            $fileData['filename'] = $fileUniqueName;
            $fileData['type'] = $type;
            $fileData['minetype'] = $imageType;
            $fileData['width'] = $targ_w;
            $fileData['height'] = $targ_h;
            $fileData['relative_path'] = serverImageRelPath . $fileUniqueName;
            $fileData['absolute_path'] = REAL_PATH . serverImageRelPath . $fileUniqueName;
            $fileData['status'] = '0';
            $fileData['uid'] = $this->uid;
            $fileData['created'] = date('Y-m-d');
            $data_postThumb = @serialize($fileData);
            $data_thumb = base64_encode($data_postThumb);
            $fileData['info'] = $data_thumb;

            $file_id = $this->videos_model->_saveThumb($fileData);
            $msg = $this->loadPo($this->config->item('success_image_crop'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
        } else {
            $msg = $this->loadPo($this->config->item('error_file_upload'));
            $this->log($this->user, $msg);
            $this->data['message'] = $msg;
            $this->show_ads_view('videoEditThumbnail', $this->data);
        }
        redirect($redirect_url);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to set default thumb
      /--------------------------------------------------------------------------------
     */

    public function set_defltimage() {
        $sess = $this->session->all_userdata();
        $content_id = @$_GET['cid'];
        $file_id = @$_GET['fid'];
        $redirect_url = @$_GET['redirect_url'];
        $result = $this->Ads_model->setDefaultImg($content_id, $file_id);
        $msg = $this->loadPo($this->config->item('success_default_image_set'));
        $this->log($this->user, $msg);
        $this->session->set_flashdata('message', $this->_successmsg($msg));
        redirect($redirect_url);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to delete thumb
      /--------------------------------------------------------------------------------
     */

    public function delele_thumbimage() {
        $sess = $this->session->all_userdata();
        $content_id = @$_GET['cid'];
        $file_id = @$_GET['fid'];
        $redirect_url = @$_GET['redirect_url'];
        $fileName = $this->videos_model->getThumbImgName($file_id);
        if ($fileName) {
            $delResultThumb = $this->_deleteFile($fileName, REAL_PATH . serverImageRelPath);
            $delResultThumbSmall = $this->_deleteFile($fileName, REAL_PATH . THUMB_SMALL_PATH);
            $delResultThumbMedium = $this->_deleteFile($fileName, REAL_PATH . THUMB_MEDIUM_PATH);
            $delResultThumbLarge = $this->_deleteFile($fileName, REAL_PATH . THUMB_LARGE_PATH);
            $result = $this->videos_model->deleleThumb($content_id, $file_id);
            $msg = $this->loadPo($this->config->item('success_record_delete'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($redirect_url);
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to download thumb
      /--------------------------------------------------------------------------------
     */

    function download_thumb() {
        $sess = $this->session->all_userdata();
        $fileName = @$_GET['fileName'];
        $redirect_url = @$_GET['redirect_url'];
        $fullPath = REAL_PATH . serverImageRelPath . $fileName;
        $downloadResult = $this->_downloadFile($fileName, $fullPath);
        if (!$downloadResult) {
            $msg = $this->loadPo($this->config->item('error_file_found'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_errormsg($msg));
            redirect($redirect_url);
        }
    }

    
    /*
      /--------------------------------------------------------------------------------
      /   function used to show video detail page
      /--------------------------------------------------------------------------------
     */

    function detail() {
        $data['welcome'] = $this;
        $id = $this->uri->segment(3);
        if ($id == "") {
            redirect(base_url() . 'video');
        }
        $result = $this->Ads_model->video_detail($id);
        $data['result'] = $result;

        $this->show_view('ads/videodetail', $data);
    }

    /*
      /*********************************************************************************
      /   VIDEO STATUS SECTION STARTS
      /*********************************************************************************
     */

    function video_status() {
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "ads/video_status";
        $config["total_rows"] = $this->Ads_model->getstatuscount($this->uid);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['welcome'] = $this;
        $data['status'] = $this->Ads_model->getstatus($this->uid, $config["per_page"], $page);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('ads/video_status', $data);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to render video
      /--------------------------------------------------------------------------------
     */

    function rendervideo() {
        $data['path'] = base64_decode($_GET['path']);
        $this->load->view('rendervideo', $data);
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */
            
