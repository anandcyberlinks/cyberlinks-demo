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
        $this->load->library("pagination");
        $this->load->library('Session');
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
        $this->uid = $s[0]->id;
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
    }

    function slist() {
        if (isset($_POST['search']) && $_POST['search'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }

        //--- pagination----//

        $config = array();
        $config["base_url"] = base_url() . "livestream/slist?";
        $totalCount = $this->Livestream_model->getList(array('search' => $this->session->userdata('search_form'), 'count' => 1));
        $config["total_rows"] = $totalCount[0]->total;
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

        //$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $page = ($_GET['per_page']) ? $_GET['per_page'] : 0;
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_rows'] = $config["total_rows"];

        //-- content provider list --//
        $this->data['content_provider'] = $this->Livestream_model->getContentProvider();
        $this->data['result'] = $this->Livestream_model->getList(array('search' => $this->session->userdata('search_form'), 'l' => $config["per_page"], 'start' => $page));
        $this->show_view('livestream/list', $this->data);
    }

    function index() {
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $id = base64_decode($_GET['id']);
        } else if (isset($_POST['userid']) && $_POST['userid'] > 0) {
            $id = $_POST['userid'];
        } else {
            $id = $this->uid;
        }
        $cid = $this->uri->segment(3);
        //-- content provider list --//
        //$data['content_provider'] = $this->Livestream_model->getContentProvider(true);
        $data['channel'] = $this->Livestream_model->getChannel($cid);
        //-- stream list --//
        $data['result'] = $result = $this->Livestream_model->getStream($cid);
        //print_r($result);die;
        if (isset($_POST['save'])) {
            if (isset($_FILES['csv']['tmp_name']) && $_FILES['csv']['tmp_name'] != "") {
                if ($_FILES['csv']['type'] == 'text/csv' || $_FILES['csv']['type'] == 'application/csv') {
                    $chanel_id = $this->uri->segment(3);
                    $this->db->select('name');
                    $this->db->where('id', $chanel_id);
                    $result = $this->db->get('channels')->result();
                    //print_r($result); die;
                    $channel_name = $result[0]->name;
                    $file = $_FILES['csv']['tmp_name'];
                    //print_r($_FILES); die;
                    $fp = fopen($file, 'r') or die(json_encode(array('result' => 'error')));
                    $num = 0;
                    while ($csv_line = fgetcsv($fp, 1024)) {
            //print_r($csv_line);
            for ($i = 0, $j = count($csv_line[$i]); $i < $j; $i++) {
                if ($num == 0) {
                    if (!isset($csv_line[1]) || !isset($csv_line[3]) || !isset($csv_line[4])) {
                        echo "invalid csv";
                        die;
                    }
                    $title = $csv_line;
                } else {
                    $this->db->delete('livechannel_epg', ['channel_id' => $chanel_id]);
                    $temp = array(
                        'channel_id' => $chanel_id,
                        'channel_name' => $channel_name,
                        'date' => date('Y-m-d'),
                        'show_title' => $csv_line[4],
                        'show_time' => $csv_line[1], //($csv_line[1] == '') ? '' : date("H:i", strtotime($csv_line[1])),
                        'show_duration' => $csv_line[2],
					    'show_thumb' => "",
                        'show_language' => "",
                        'show_description' => "",
                        'show_type' => $csv_line[3],
						'media_type' => $csv_line[5],
                        'valid' => ($csv_line[1] == '' || $csv_line[4] == '') ? 'invalid' : 'valid'
                    );
                    $array[] = $temp;
                    //$this->db->insert('livechannel_epg', $temp);
                    //echo $this->db->last_query();
                }
                $num++;
            }
            //print_r($temp);
        }
                    fclose($fp);
                    foreach ($array as $val) {
                        if ($val['valid'] == 'valid') {
                            unset($val['valid']);
                            $this->db->insert('livechannel_epg', $val);
                        }
                    }
                }
            }


            //--- upload file --//
            if (isset($_FILES['chanelImage']['tmp_name']) && $_FILES['chanelImage']['tmp_name'] != "") {
                if (isset($result->thumbnail_url)) {
                    unlink(REAL_PATH . str_replace(base_url(), "", $result->thumbnail_url));
                }
                $thumbnail = $this->uploadImg($_FILES['chanelImage']["tmp_name"], $_FILES['chanelImage']["name"]);
            } else {
                //$thumbnail = $result->thumbnail_url;
            }
            $input['channel_id'] = $cid;
            $input['youtube'] = $_POST['youtube'];
            $input['ios'] = $_POST['ios'];
            $input['android'] = $_POST['android'];
            $input['windows'] = $_POST['windows'];
            $input['web'] = $_POST['web'];

            if ($thumbnail != '') {
                $input['thumbnail_url'] = base_url() . $thumbnail;
            }

            $input['user_id'] = $id;
            $input['status'] = 1;

            if (empty($result)) {
                $this->Livestream_model->saveUrl($input);
            } else {
                $this->Livestream_model->saveUrl($input, 'update');
            }
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url() . "livestream/index/" . $cid);
        }

        $data['welcome'] = $this;
        $this->show_view('livestream/add', $data);
    }

    function uploadImg($tmpFilePath, $fileName, $id = null) {
        $fileExt = $this->_getFileExtension($fileName);
        $fileUniqueName = uniqid() . "." . $fileExt;
        $filepath = 'assets/upload/thumbs/' . $fileUniqueName;
        move_uploaded_file($tmpFilePath, REAL_PATH . $filepath);
        return $filepath;
    }

    function rendervideo() {
        $data['path'] = base64_decode($_GET['path']);
        $this->load->view('rendervideo', $data);
    }

    function changestatus() {
        $data['id'] = $_GET['id'];
        $data['status'] = $_GET['status'];
        $this->Livestream_model->updatestatus($data);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_update'))));
        redirect(base_url() . 'livestream/slist');
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */    