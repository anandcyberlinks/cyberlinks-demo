<?php

class Audio extends MY_Controller {

    public $uid = null;
    public $role_id = null;
    public $user = null;

    function __construct() {
        parent::__construct();
        $this->load->model('audio_model');
        $s = $this->session->all_userdata();
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
        $this->user = $s[0]->username;
    }

    function index() {
        $data['welcome'] = $this;
        $data['result'] = $this->audio_model->getAudio($this->uid);
        //print_r($data['result']);
        $this->show_view('audio_list', $data);
    }

    function upload() {
        $data['welcome'] = $this;
        $this->show_view('upload_audio', $data);
    }

    function uploader() {
        //print_r($_FILES); die;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            $this->data['welcome'] = $this;
            if (isset($_FILES['0']['tmp_name']) && $_FILES['0']['tmp_name'] != "") {
                $tmpFilePath = $_FILES['0']['tmp_name'];
                $originalFileName = $_FILES["0"]["name"];
                $fileExt = $this->_getFileExtension($originalFileName);
                $fileUniqueName = uniqid() . "." . $fileExt;
                $videoresult = $this->_upload($tmpFilePath, $fileUniqueName, 'audio');
                if ($videoresult) {
                    $temp = array();
                    $temp['title'] = $fileUniqueName;
                    $temp['u_id'] = $this->uid;
                    $temp['file_path'] = base_url() . serverAudioRelPath . $fileUniqueName;
                    $temp['absalute_path'] = serverAudioRelPath . $fileUniqueName;
                    $temp['status'] = '0';
                    $last_id = $this->audio_model->_saveAudio($temp);
                    $msg = 'File uploaded Suceessfully';
                    $this->log($this->user, $msg);
                    $temp['id'] = base64_encode($last_id);
                    $temp['message'] = $this->_successmsg($msg);
                    echo json_encode($temp);
                } else {
                    $msg = $this->loadPo($this->config->item('error_file_upload'));
                    $data['message'] = $this->_errormsg($msg);
                    echo json_encode($data);
                }
            } else {
                redirect(base_url() . 'audio');
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'audio');
        }
    }

    function edit() {
        $data['welcome'] = $this;
        $id = base64_decode($_GET['action']);
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $_POST['id'] = $id;
            $last_id = $this->audio_model->_saveAudio($_POST);
            $msg = 'File updated Suceessfully';
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($msg)));
            redirect(base_url() . 'audio');
        }
        $data['result'] = $this->audio_model->audioProfile($id);
        $data['cat'] = $this->audio_model->fetchCat($this->uid);
        $this->show_view('edit_audio', $data);
    }

    function delete() {
        $id = base64_decode($_GET['action']);
        $this->db->delete('audio', array('id' => $id));
        unlink($_GET['file']);
        $msg = 'File Deleted Suceessfully';
        $this->log($this->user, $msg);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($msg)));
        redirect(base_url() . 'audio');
    }

}
