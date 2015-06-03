<?php

ini_set('display_errors', 1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Video extends MY_Controller {

    public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedVideoExt;
    public $allowedImageExt;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('videos_model');
        $this->load->model('category_model');
        $this->load->model('Genre_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('csvreader');
        $per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
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
            )
        /* array(
          'field' => 'content_channel',
          'label' => 'Content Channel',
          'rules' => 'trim|required'
          ),
          array(
          'field' => 'description',
          'label' => 'Description',
          'rules' => 'trim|required'
          ) */
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
    function s3Files() {
        if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJ4KAM6S3JS3V3GQQ');
        if (!defined('awsSecretKey')) define('awsSecretKey', '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');
        $bucketName = bucket;
        $this->load->library('S3', array('awsAccessKey'=>awsAccessKey, 'awsSecretKey'=>awsSecretKey));
        $contents = $this->s3->getBucket($bucketName);
        echo "<hr/>List of Files in bucket : {$bucketName} <hr/>";
        $n = 1;
        foreach ($contents as $p => $v):
            echo $p."<br/>";
            $n++;
        endforeach;
    }

    function google($video_temp = '', $video_name = '', $video_desc = '') {
        $OAUTH2_CLIENT_ID = '353001433162-42vrona3fi8msfve7akh857t6fk0di9v.apps.googleusercontent.com';
        $OAUTH2_CLIENT_SECRET = 'cEcHT7CkTK5GYUDmC7dgYa8r';
        $redirect = 'http://localhost/multitvfinal/index.php/video/google';
        $client = new Google_Client();
        $client->setClientId($OAUTH2_CLIENT_ID);
        $client->setClientSecret($OAUTH2_CLIENT_SECRET);
        $client->setScopes('https://www.googleapis.com/auth/youtube');
        $redirect = filter_var('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'],FILTER_SANITIZE_URL);
        $client->setRedirectUri($redirect);
        $youtube = new Google_Service_YouTube($client);
        if (isset($_GET['code'])) { 
            if (strval($this->session->userdata('state')) !== strval($_GET['state'])) {
                die('The session state did not match.');
            }
            $client->authenticate($_GET['code']);
            $this->session->set_userdata('token', $client->getAccessToken());
            header('Location: ' . $redirect);
        }
        $session_token = ($this->session->userdata('token'));
        if ($session_token) {
            $client->setAccessToken($this->session->userdata('token'));
        }
        if ($client->getAccessToken()) {
            if (isset($video_temp) && $video_temp != '') {
                // REPLACE this value with the path to the file you are uploading.
                $videoPath = $video_temp;
                // Create a snippet with title, description, tags and category ID
                // Create an asset resource and set its snippet metadata and type.
                // This example sets the video's title, description, keyword tags, and
                // video category.
                $snippet = new Google_Service_YouTube_VideoSnippet();
                $snippet->setTitle($video_name);
                $snippet->setDescription($video_desc);
                $snippet->setTags(array("globalPunjab", "Video"));
                // Numeric video category. See
                // https://developers.google.com/youtube/v3/docs/videoCategories/list 
                $snippet->setCategoryId("22");
                $snippet->setChannelTitle("GlobalPunjab");
                // Set the video's status to "public". Valid statuses are "public",
                // "private" and "unlisted".
                $status = new Google_Service_YouTube_VideoStatus();
                $status->privacyStatus = "public";
                // Associate the snippet and status objects with a new video resource.
                $video = new Google_Service_YouTube_Video();
                $video->setSnippet($snippet);
                $video->setStatus($status);
                // Specify the size of each chunk of data, in bytes. Set a higher value for
                // reliable connection as fewer chunks lead to faster uploads. Set a lower
                // value for better recovery on less reliable connections.
                $chunkSizeBytes = 1 * 1024 * 1024;
                // Setting the defer flag to true tells the client to return a request which can be called
                // with ->execute(); instead of making the API call immediately.
                $client->setDefer(true);
                // Create a request for the API's videos.insert method to create and upload the video.
                $insertRequest = $youtube->videos->insert("status,snippet", $video);
                // Create a MediaFileUpload object for resumable uploads.
                $media = new Google_Http_MediaFileUpload(
                        $client, $insertRequest, 'video/*', null, true, $chunkSizeBytes
                );
                $media->setFileSize(filesize($videoPath));
                // Read the media file and upload it chunk by chunk.
                $status = false;
                $handle = fopen($videoPath, "rb");
                while (!$status && !feof($handle)) {
                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }
                fclose($handle);
                // If you want to make other calls after the file upload, set setDefer back to false
                $client->setDefer(false);
                $htmlBody = $status['id'];
                //echo "<pre>"; print_r($status);
            }
            $htmlBody = false;
        } else {
            // If the user hasn't authorized the app, initiate the OAuth flow
            $state = mt_rand();
            $client->setState($state);
            $this->session->set_userdata('state', $state);
            //echo $this->session->userdata('state');
            $authUrl = $client->createAuthUrl();
            $htmlBody = $authUrl;
        }
        return $htmlBody;
        //spl_autoload_register('google_api_php_client_autoload'); die;
    }

    function test() {
        $data['welcome'] = $this;
        $this->show_view('test', $data);
    }
   

    function data() {
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
                    $data['show_u'] = ' ';
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
        if (isset($_GET)) {
            $this->session->set_userdata('search_form', $_GET);
        } else if (isset($_GET['reset'])) {
            $this->session->unset_userdata('search_form');
        }

        $searchterm = $this->session->userdata('search_form');
        $data['count'] = $this->videos_model->get_videocount($this->uid, $searchterm);
        $data['result'] = $this->videos_model->get_video($this->uid, 10, 1, $sort, $sort_by, $searchterm);
        $this->load->view('test', $data);
    }

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
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        // echopre($searchterm);
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "video/index/";
        $config["total_rows"] = $this->videos_model->get_videocount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->videos_model->get_video($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['category'] = $this->videos_model->get_category($this->uid);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('search_video', $data);
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
            $this->data['result'] = $this->videos_model->edit_profile($this->data['id']);
            $tab = $this->uri->segment(3);
            switch ($tab) {
                case "Basic":
                    $this->videoprofile();
                    break;
                case "Advanced":
                    $this->videoprofileadvance();
                    //$this->show_video_view('videoEditAdvance', $this->data);
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
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'video');
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
                    $post['feature_video'] = $this->input->post('feature_video') == 'on' ? 1 : 0;
                    $post_key = $_POST['tags'];
                    $this->videos_model->_saveVideo($post);
                    $this->videos_model->_setKeyword($post_key, $vid);
                    $msg = $this->loadPo($this->config->item('success_record_update'));
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', $this->_successmsg($msg));
                    redirect('video');
                } else {
                    $this->data['result'] = (array) $this->videos_model->edit_profile($vid);
                    $this->data['result']['keywords'] = $this->videos_model->_getKeyword($vid);
                    $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
                    $this->data['content_id'] = $vid;
                    $this->data['category'] = $this->videos_model->get_category($this->uid);
                    $this->data['genre'] = $this->Genre_model->getAllGenre();
                    $this->data['setting'] = $this->videos_model->getsetting($vid);
                    $this->data['countryData'] = $this->videos_model->getCountryList();
                    $this->show_video_view('videoEditBasic', $this->data);
                }
            } else {
                $this->data['result'] = (array) $this->videos_model->edit_profile($vid);
                $this->data['result']['keywords'] = $this->videos_model->_getKeyword($vid);
                $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
                $this->data['content_id'] = $vid;
                $this->data['category'] = $this->videos_model->get_category($this->uid);
                $this->data['genre'] = $this->Genre_model->getAllGenre();
                $this->data['setting'] = $this->videos_model->getsetting($vid);
                $this->data['countryData'] = $this->videos_model->getCountryList();
                $this->show_video_view('videoEditBasic', $this->data);
            }
        }
    }

    /*
      /--------------------------------------------------
      /    function used for video advance
      /--------------------------------------------------
     */

    function videoprofileadvance() {
        $id = $this->uid;
        $cid = base64_decode($_REQUEST['action']);
        $this->data['advance'] = $this->videos_model->get_videofieldadvance($id, $cid);
        // echo "<pre>";
        // print_r($this->data['advance']);
        $GetData = array();
        foreach ($this->data['advance'] as $key => $val) {
            // echo $val[$key]['field_id'];
            //print_r($val->field_id);
            $GetData[$val->field_id] = $this->videos_model->fetchvalue($cid, $val->field_id);
        }
        $value = array();
        $data1 = array();
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "Submit") {
            $curl = $_POST['curl'];
            unset($_POST['curl']);
            unset($_POST['submit']);
            foreach ($_POST as $keyadvance => $values) {
                //echo $keyadvance."==>".$values."<br>";
                $value[$keyadvance] = $values;
                $val = $this->videos_model->get_videofieldvalueadvance($keyadvance);
                if (isset($val[0]->value)) {
                    $val1[$keyadvance] = $val[0]->value;
                }
            }
            $this->videos_model->save_videofieldvalueadvance($value);
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($curl);
        }
        $this->data['fvalue'] = $GetData;
        $this->show_video_view('videoEditAdvance', $this->data);
    }

    /*
      /----------------------------------------------------------------
      /   function used for video scheduling
      /----------------------------------------------------------------
     */

    function video_scheduling() {
        $id = $this->data['id'];
        if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
            $post['content_id'] = $id;
            $post['schedule'] = $this->input->post('r2');
            $data = $this->videos_model->get_scheduling($id);
            if (isset($data[0])) {
                if ($data[0]->content_id == $id) {
                    if ($this->input->post('r2') == "Always") {
                        $post['startDate'] = '';
                        $post['endDate'] = '';
                        $this->videos_model->update_scheduling($post);
                        $msg = $this->loadPo($this->config->item('success_video_schedule'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        //redirect('video');
                    } else {
                        $post['startDate'] = date('Y-m-d', strtotime($this->input->post('datepickerstart'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerstart')));
                        $post['endDate'] = date('Y-m-d', strtotime($this->input->post('datepickerend'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerend')));
                        $this->form_validation->set_rules($this->validation_rules['video_schedule']);
                        if ($this->form_validation->run()) {
                            $this->videos_model->update_scheduling($post);
                            $msg = $this->loadPo($this->config->item('success_video_schedule'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
                            //redirect('video');	
                        } else {
                            $this->data['schedule'] = $this->videos_model->get_scheduling($id);
                            $this->show_video_view('videoEditScheduling', $this->data);
                        }
                    }
                }
            } else {
                if ($this->input->post('r2') == "Always") {
                    $this->videos_model->save_scheduling($post);
                    $msg = $this->loadPo($this->config->item('success_video_schedule'));
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', $this->_successmsg($msg));
                    //redirect('video');
                } else {
                    $post['startDate'] = date('Y-m-d', strtotime($this->input->post('datepickerstart'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerstart')));
                    $post['endDate'] = date('Y-m-d', strtotime($this->input->post('datepickerend'))) . ' ' . date('H:i:s', strtotime($this->input->post('timepickerend')));
                    $this->form_validation->set_rules($this->validation_rules['video_schedule']);
                    if ($this->form_validation->run()) {
                        $this->videos_model->save_scheduling($post);
                        $msg = $this->loadPo($this->config->item('success_video_schedule'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
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

    /*
      /--------------------------------------------------------------------------------
      /   function used for flavors section
      /--------------------------------------------------------------------------------
     */

    function flavors() {
        $sess = $this->session->all_userdata();
        $this->data['welcome'] = $this;
        $id = @$_GET['action'];
        $vid = base64_decode($id);
        if ($vid) {
            $s = $this->data['setting'] = $this->videos_model->getsetting($vid);
            $this->data['content_id'] = $vid;
            $this->show_video_view('videoEditFlavor', $this->data);
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
                    $this->data['youtube'] = $this->google();
                    $this->show_view('upload_video', $this->data);
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

    function pre_upload() {
        print_r($_POST);
        if (isset($_POST['upload_on'])) {

            if ($_POST['upload_on'] == 's3') {
                $this->session->set_userdata('upload_on', true);
            } else {
                $this->session->set_userdata('upload_on', false);
            }
        }
        if (isset($_POST['upload_youtube'])) {
            if ($_POST['upload_youtube'] == 'youtube') {
                $this->session->set_userdata('youtube_upload', true);
            } else {
                $this->session->set_userdata('youtube_upload', false);
            }
        }
        //$ss = $this->session->userdata('upload_on');
        //var_dump($ss);
    }

    function upload() {
        //print_r($_POST); die;
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
                    $youtube_session = $this->session->userdata('youtube_upload');

                    $videoresult = $this->_upload($tmpFilePath, $fileUniqueName, 'video');
                    if ($videoresult) {
                        $data = array();
                        $data['content_title'] = $originalFileName;
                        $data['uid'] = $this->uid;
                        $data['filename'] = $fileUniqueName;
                        $data['relative_path'] = serverurl . $fileUniqueName;
                        $data['absolute_path'] = REAL_PATH . serverVideoRelPath . $fileUniqueName;
                        $data['minetype'] = "video/" . $fileExt;
                        $data['type'] = $fileExt;
                        $data['status'] = '0';
                        $data['info'] = base64_encode($fileUniqueName);
                        $last_id = $this->videos_model->_saveVideo($data);
                        $msg = $this->loadPo($this->config->item('success_file_upload'));
                        $this->log($this->user, $msg);
                        $temp['id'] = base64_encode($last_id);
                        $temp['message'] = $this->_successmsg($msg);

                        if ($youtube_session) {
                            $this->google($tmpFilePath, $originalFileName, $originalFileName);
                        }

                        echo $last_id;
                        //echo json_encode($temp);
                    } else {
                        $msg = $this->loadPo($this->config->item('error_file_upload'));
                        $temp['message'] = $this->_errormsg($msg);
                        echo json_encode($temp);
                    }
                }
            } else {
                $this->show_view('upload_video', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'video');
        }
    }

    /*
     * Function for edit multiple uploaded video
     */

    function postfile(){
        print_r($_FILES); die;
    }
    
    function EditAllInvalid() {

        $query = "select id, title from contents where category is NULL and uid = $this->uid ORDER BY id DESC";
        $res = $this->db->query($query)->result();
        //print_r($res); die;
        if (count($res) == 0) {
            redirect(base_url() . 'video');
        }
        $data['record'] = $res;
        $data['category'] = $this->videos_model->get_category($this->uid);
        //echo "<pre>";
        $data['welcome'] = $this;
        $this->show_view('editall', $data);
        //print_r($res);
    }

    function submitAll() {
        //print_r($_POST);die;
        $id = $this->videos_model->_saveVideo($_POST);
        if ($id) {
            echo $id;
        } else {
            echo "Try Again";
        }
    }

    /*
      /-------------------------------------------------------------
      /   function used for video upload from youtube url
      /-------------------------------------------------------------
     */

    function youtube() {
        $videoUrl = trim($_POST['url']);
        preg_match('%https?://(?:www\.)?youtube\.com/watch\?v=([^&]+)%', $videoUrl, $matches);
        if ($matches) {
            $post = array();
            $tmp = $this->get_youtube($videoUrl);

            $youtubeData = $tmp['detail']['entry']->{'media$group'};
            $post['content_token'] = $tmp['id'];
            $post['content_title'] = $tmp['detail']['entry']->{'title'}->{'$t'};
            $post['description'] = $youtubeData->{'media$description'}->{'$t'};
            $post['duration'] = $youtubeData->{'media$content'}[0]->duration;
            $post['uid'] = $this->uid;
            $post['created'] = date('Y-m-d');
            $post['type'] = 'youtube';
            $post['filename'] = $videoUrl;
            $post['uid'] = $this->uid;
            $post['created'] = date('Y-m-d');
            $post['relative_path'] = $videoUrl;
            $post['absolute_path'] = $videoUrl;
            $post['status'] = '0';
            $post['type'] = 'youtube';
            $post['minetype'] = "";
            $post['info'] = base64_encode($videoUrl);
            //echo "<pre>";            print_r($post); exit;
            $last_id = $this->videos_model->_saveVideo($post);
            //Save keywords
            $keyWordsData = $tmp['content']['keywords'];
            $this->videos_model->_setKeyword(trim($keyWordsData), $last_id);
            $id = base64_encode($last_id);
            redirect(base_url() . 'video/videoOpr/Basic?action=' . $id . '&');
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_youtube_url'))));
            redirect($_POST['redirect_url']);
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used for video upload from other source
      /--------------------------------------------------------------------------------
     */

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
                        $msg = $this->loadPo($this->config->item('success_video_source_add'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect($redirect_url);
                    }
                } else {
                    $msg = $this->loadPo($this->config->item('warning_video_source'));
                    $this->log($this->user, $msg);
                    $this->session->set_flashdata('message', $this->_warningmsg($msg));
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
                    $this->show_view('bulkupload', $this->data);
                    break;
                case "ftp":
                    $this->data['tab'] = $tab;
                    $this->show_view('bulkupload', $this->data);
                    break;
                default:
                    $this->data['tab'] = 'csv';
                    $this->show_view('bulkupload', $this->data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'video');
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
        //print_r($_POST); die; 
        $this->data['welcome'] = $this;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $content_title = $_POST['content_title'];
            $category = $_POST['category'];
            $keywords = $_POST['keyword'];
            $url = str_replace(" ", "%20", $_POST['url']);
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
                    $fieDestPath = REAL_PATH . serverVideoRelPath . $videoFileUniqName;
                    $videoresult = $this->_uploadFileCurl($videoFileSrcPath, $fieDestPath, $videoFileUniqName);
                    if ($videoresult == '1') {
                        $contentData = array();
                        $contentData['content_title'] = isset($content_title) && $content_title != '' ? $content_title : 'content_title';
                        $contentData['description'] = isset($description) && $description != '' ? $description : 'description';
                        $contentData['type'] = 'CSV';
                        $contentData['category'] = $catId;
                        $contentData['uid'] = $this->uid;
                        $contentData['filename'] = $videoFileUniqName;
                        $contentData['relative_path'] = serverurl . $videoFileUniqName;
                        $contentData['absolute_path'] = REAL_PATH . serverVideoRelPath . $videoFileUniqName;
                        $contentData['minetype'] = "video/" . $videoFileExt;
                        $contentData['type'] = $videoFileExt;
                        $contentData['status'] = '1';
                        $contentData['info'] = base64_encode($videoFileUniqName);
                        $lastId = $this->videos_model->_saveVideo($contentData);

                        //Insert keywords
                        if (isset($keywords) && $keywords != '') {
                            $this->videos_model->_setKeyword(str_replace("|", ",", $keywords), $lastId);
                        }
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
            redirect(base_url() . 'video');
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
            $ftp_conn = @ftp_connect($ftp_server);
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
                $result = "erroe";
                $message = "Invalid login";
            }
        } else {
            $result = 'error';
            $message = $this->_warningmsg($this->loadPo($this->config->item('warning_fields_empty')));
        }
        //$response = array();
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
        //print_r($login); 
        //$redirect_url = $_POST['redirect_url'];
        $fileNameArr = $_POST['chk'];
        $filesArr = explode(",", rtrim($fileNameArr, ','));
        for ($i = 0; $i < count($filesArr); $i++) {
            $fileName = $filesArr[$i];
            $fileExt = $this->_getFileExtension($fileName);
            $fileNameUniq = uniqid() . "." . $fileExt;
            $fieSrcPath = $this->input->post('ftpPath') . $fileName;
            $filepath = 'http://'.$ftp_server.$fieSrcPath; 
            
            $dir_path = getcwd();
            $path_nw = str_replace('\\', '/', $dir_path);
            $fieDestPath = $path_nw.'/' . 'assets/upload/video/' . $fileNameUniq;
            $msg = "";
            if (!in_array($fileExt, $this->allowedVideoExt)) {
                $msg = $this->loadPo($this->config->item('error_file_format') . $fileName);
                $this->log($this->user, $msg);
                $data['message'] = $this->_errormsg($msg);
            } else {
                $videoresult = $this->_uploadFileCurl($filepath, $fieDestPath, $fileNameUniq);
                if ($videoresult) {
                    $fileInfo = $this->getFileInfo($fieDestPath);
                    $mimeType = 'test ';//$fileInfo['mime_type'];
                    $data = array();
                    $data['content_title'] = $fileNameUniq;
                    $data['uid'] = $this->uid;
                    $data['type'] = 'FTP';
                    $data['filename'] = $fileNameUniq;
                    $data['relative_path'] = serverurl . $fileNameUniq;
                    $data['absolute_path'] = REAL_PATH . serverVideoRelPath . $fileNameUniq;
                    $data['minetype'] = $mimeType;
                    $data['type'] = $fileExt;
                    $data['status'] = '0';
                    $data['info'] = base64_encode($fileNameUniq);
                    //print_r($data); die;
                    $last_id = $this->videos_model->_saveVideo($data);
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
            $fileName = $this->videos_model->get_videoInfo($id);
            $thumbInfo = $this->videos_model->getThumbsInfo($id);
            if (!empty($thumbInfo)) {
                $thumbCount = count($thumbInfo);
                for ($i = 0; $i < $thumbCount; $i++) {
                    $delResultThumb = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . serverImageRelPath);
                    $delResultThumbSmall = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_SMALL_PATH);
                    $delResultThumbMedium = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_MEDIUM_PATH);
                    $delResultThumbLarge = $this->_deleteFile($thumbInfo[$i]->name, REAL_PATH . THUMB_LARGE_PATH);
                }
            }
            $delResult = $this->_deleteFile($fileName, REAL_PATH . serverVideoRelPath);
            $videoFileExt = $this->_getFileExtension($fileName);
            $fileNameInitial = basename($fileName, $videoFileExt);
            $delResult_2g = $this->_deleteFile($fileNameInitial . '_2g.mp4', REAL_PATH . serverVideoRelPath);
            $delResult_3g = $this->_deleteFile($fileNameInitial . '_3g.mp4', REAL_PATH . serverVideoRelPath);
            $delResult_hd = $this->_deleteFile($fileNameInitial . '_hd.mp4', REAL_PATH . serverVideoRelPath);
            $result = $this->videos_model->delete_video($id);
            if ($result == '1') {
                $msg = $this->loadPo($this->config->item('success_record_delete'));
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', $this->_successmsg($msg));
                redirect($redirect_url);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'video');
        }
    }

    /*
      /********************************************************************************
      /   function used for video setting section starts
      /********************************************************************************
     */

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
      /--------------------------------------------------------------------------------
      /   function used for video player setting
      /--------------------------------------------------------------------------------
     */

    function setting_player() {
        $data['welcome'] = $this;
        $data['tab'] = 'Player';
        $settings_key = 'player_settings';
        $data['playerData'] = $this->videos_model->getPlayerData($this->uid);
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
                        $this->session->set_flashdata('message', $this->_errormsg($errors));
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

            $checkDataF = $this->videos_model->checkFlavorData($settings_key, $this->uid);

            if ($checkDataF >= 1) {
                $post['modified'] = date('Y-m-d H:i:s');
                $result = $this->videos_model->update_flavorOption($post, $settings_key, $this->uid);
            } else {
                $post['user_id'] = $this->uid;
                $post['key'] = $settings_key;
                $post['group'] = 'player';
                $post['created'] = date('Y-m-d H:i:s');
                $result = $this->videos_model->insert_flavorOption($post);
            }
            $msg = $this->loadPo($this->config->item('success_player_setting_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($redirect_url);
            exit;
        } else {
            $this->show_view('video_settings', $data);
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used for live streaming section
      /--------------------------------------------------------------------------------
     */

    function live_streaming() {
        if (isset($_POST['save'])) {
            unset($_POST['save']);
            $this->videos_model->saveUrl(json_encode($_POST['url']), $this->uid);
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url() . "video/live_streaming");
        }
        $data['url'] = $this->videos_model->getLivestream($this->uid);
        $data['welcome'] = $this;
        $this->show_view('live_streaming', $data);
    }

    function deleteLive() {
        $this->videos_model->deleteUrl($this->uid);
        $msg = $this->loadPo($this->config->item('success_record_delete'));
        $this->log($this->user, $msg);
        $this->session->set_flashdata('message', $this->_successmsg($msg));
        redirect(base_url() . "video/live_streaming");
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
            $this->data['thumbnails_info'] = $this->videos_model->get_thumbs($vid);
            $this->data['content_id'] = $vid;
            $this->data['videoInfo'] = $this->videos_model->get_videoInfo($vid);
            $this->data['defaultThumbInfo'] = $this->videos_model->get_defaultThumb($vid);
            $this->show_video_view('videoEditThumbnail', $this->data);
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
                    $this->show_video_view('videoEditThumbnail', $this->data);
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
                        $msg = $this->loadPo($this->config->item('success_file_upload'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect($redirect_url);
                    } else {
                        $msg = $this->loadPo($this->config->item('error_file_upload'));
                        $data['message'] = $this->_errormsg($msg);
                        $this->show_video_view('videoEditThumbnail', $data);
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
            $this->show_video_view('videoEditThumbnail', $this->data);
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
        $result = $this->videos_model->setDefaultImg($content_id, $file_id);
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
      /********************************************************************************
      /   PROMO SECTION STARTS
      /********************************************************************************
     */

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
        $result = $this->videos_model->video_detail($id);
        $data['result'] = $result;
        //echo "<pre>";        print_r($result); die;

        $this->show_view('videodetail', $data);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used for test log
      /--------------------------------------------------------------------------------
     */

    function testlog() {
        $user = 'admin';
        $id = $_GET['id'];
        $msg = 'Button Click on id = ' . $id;
        $this->log($user, $msg);
        redirect(base_url() . 'video/videoprofile?action=NTQ=&');
    }

    /*
      /*********************************************************************************
      /   VIDEO STATUS SECTION STARTS
      /*********************************************************************************
     */

    function video_status() {
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "video/video_status";
        $config["total_rows"] = $this->videos_model->getstatuscount($this->uid);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
        $data['welcome'] = $this;
        $data['status'] = $this->videos_model->getstatus($this->uid, $config["per_page"], $page);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('video_status', $data);
    }

    /*
      /--------------------------------------------------------------------------------
      /   function used to change status
      /--------------------------------------------------------------------------------
     */

    function changeStatus() {
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
        $id = $data['content_id'];
        if ($data['status'] == 'pending' || $data['status'] == 'inprocess') {
            //echo 'hello';
            $msg = $this->loadPo($this->config->item('success_flavor_status_change') . '=' . $id);
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($curr_url);
        }
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

    /*
      /--------------------------------------------------------------------------------
      /   function used to debug video information
      /--------------------------------------------------------------------------------
     */

    function debug() {
        $data['welcome'] = $this;
        $searchterm = '';
        if ($this->uri->segment(3) == '') {
            $this->session->unset_userdata('search_form');
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["total_rows"] = $this->videos_model->get_debugVideoInfoCount($this->uid, $searchterm);
        $config["per_page"] = $config["total_rows"];
        $this->pagination->initialize($config);
        $data['result'] = $this->videos_model->debugVideoInfo($this->uid, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('debuginfo', $data);
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */
            
