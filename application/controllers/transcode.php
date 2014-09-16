<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transcode extends MY_Controller {

    public $user = null;
    public $role_id = null;

    function __construct() {
        parent::__construct();
        $this->load->model('Transcode_model');
        $this->load->library('form_validation');
        $this->load->library('Session');
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
    }

    protected $validation_rules = array
        (
        'add_transcode' => array(
            array(
                'field' => 'device_name',
                'label' => 'Device name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'flavor_name',
                'label' => 'Flavor Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bitrate_type',
                'label' => 'Bitrate Typr',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bitrate',
                'label' => 'Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'video_bitrate',
                'label' => 'Video Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'audio_bitrate',
                'label' => 'Audio Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'width',
                'label' => 'Width',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'height',
                'label' => 'Height',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'frame_rate',
                'label' => 'Frame Rate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'keyframe_rate',
                'label' => 'Key Frame Rate',
                'rules' => 'trim|required|number'
            )
        ),
        'update_Transcode' => array(
            array(
                'field' => 'device_name',
                'label' => 'Device name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'flavor_name',
                'label' => 'Flavor Name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bitrate_type',
                'label' => 'Bitrate Typr',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'bitrate',
                'label' => 'Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'video_bitrate',
                'label' => 'Video Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'audio_bitrate',
                'label' => 'Audio Bitrate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'width',
                'label' => 'Width',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'height',
                'label' => 'Height',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'frame_rate',
                'label' => 'Frame Rate',
                'rules' => 'trim|required|number'
            ),
            array(
                'field' => 'keyframe_rate',
                'label' => 'Key Frame Rate',
                'rules' => 'trim|required|number'
            )
        )
    );

    function index() {
        $sort = $this->uri->segment(3);
        $sort_by = $this->uri->segment(4);
        switch ($sort) {
            case "device_name":
                $sort = 'device_name';
                if ($sort_by == 'asc')
                    $this->data['show_d'] = 'desc';
                else
                    $this->data['show_d'] = 'asc';
                break;
            case "flavor_name":
                $sort = 'flavor_name';
                if ($sort_by == 'asc')
                    $this->data['show_f'] = 'desc';
                else
                    $this->data['show_f'] = 'asc';
                break;
            case "bitrate":
                $sort = 'bitrate';
                if ($sort_by == 'asc')
                    $this->data['show_b'] = 'desc';
                else
                    $this->data['show_b'] = 'asc';
                break;
            default:
                $sort = 'device_name';
        }
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "transcode/index";
        $config["total_rows"] = $this->Transcode_model->getRecord_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_rows'] = $config["total_rows"];
        $this->data['transcode'] = $this->Transcode_model->getTranscode($config["per_page"], $page, $sort, $sort_by);
        $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
        $this->show_view('transcode', $this->data);
    }

    /* 	Search Transcode  */

    function searchTranscode() {
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } elseif (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $search_for = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "transcode/searchTranscode";
        $config["total_rows"] = count($this->Transcode_model->getSearchCount($search_for));
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_rows'] = $config["total_rows"];
        $this->data['transcode'] = $this->Transcode_model->getSearchTranscode($config["per_page"], $page, $search_for);
        $this->data["search_data"] = $search_for;
        $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
        $this->show_view('transcode', $this->data);
    }

    /* 	Add Transcode	 */

    function addTranscode() {
        $per = $this->checkpermission($this->role_id, 'add');
        //echo $this->role_id;
        if ($per) {
            $id = @$_GET['action'];
            $tid = base64_decode($id);
            if ($tid) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_Transcode']);
                    if ($this->form_validation->run()) {
                        $post['id'] = $tid;
                        $post['flavor_name'] = $this->input->post('flavor_name');
                        $post['device_name'] = $this->input->post('device_name');
                        $post['bitrate_type'] = $this->input->post('bitrate_type');
                        $post['bitrate'] = $this->input->post('bitrate');
                        $post['video_bitrate'] = $this->input->post('video_bitrate');
                        $post['audio_bitrate'] = $this->input->post('audio_bitrate');
                        $post['width'] = $this->input->post('width');
                        $post['height'] = $this->input->post('height');
                        $post['frame_rate'] = $this->input->post('frame_rate');
                        $post['keyframe_rate'] = $this->input->post('keyframe_rate');
                        $post['modified'] = date('Y-m-d');
                        $this->Transcode_model->update_transcode($post);
                        $msg = $this->loadPo('Transcode Success Fully Updated');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        redirect('transcode');
                    } else {
                        $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
                        $this->data['edit'] = $this->Transcode_model->editTranscode($tid);
                        $this->show_view('edit_transcode', $this->data);
                    }
                } else {
                    $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
                    $this->data['edit'] = $this->Transcode_model->editTranscode($tid);
                    $this->show_view('edit_transcode', $this->data);
                }
            } else {
                if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                    $post['flavor_name'] = $this->input->post('flavor_name');
                    $post['device_name'] = $this->input->post('device_name');
                    $post['bitrate_type'] = $this->input->post('bitrate_type');
                    $post['bitrate'] = $this->input->post('bitrate');
                    $post['video_bitrate'] = $this->input->post('video_bitrate');
                    $post['audio_bitrate'] = $this->input->post('audio_bitrate');
                    $post['width'] = $this->input->post('width');
                    $post['height'] = $this->input->post('height');
                    $post['frame_rate'] = $this->input->post('frame_rate');
                    $post['keyframe_rate'] = $this->input->post('keyframe_rate');
                    $post['created'] = date('Y-m-d');
                    $post['modified'] = date('Y-m-d');
                    $this->form_validation->set_rules($this->validation_rules['add_transcode']);
                    if ($this->form_validation->run()) {
                        $this->Transcode_model->saveTranscode($post);
                        $sess = $this->session->all_userdata();
                        $msg = $this->loadPo('Transcode Successfully Added');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                        redirect('transcode');
                    } else {
                        $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
                        $this->show_view('add_transcode', $this->data);
                    }
                } else {
                    $this->data['allTranscode'] = $this->Transcode_model->getAllTranscode();
                    $this->show_view('add_transcode', $this->data);
                }
            }
        }else {
            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin</div></div></section>');
            redirect(base_url() . 'transcode');
        }
    }

    /* 	Delete Transcode */

    public function deleteTranscode() {
        $per = $this->checkpermission($this->role_id, 'delete');
        //echo $this->role_id;
        if ($per) {
            $id = $_GET['id'];
            $allTranscode = $this->Transcode_model->getAllTranscode();
            $this->Transcode_model->delete_transcode($id);
            $msg = $this->loadPo('Transcode Successfully Deleted');
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>',$msg));
            redirect('transcode');
        } else {
            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin</div></div></section>');
            redirect(base_url() . 'transcode');
        }
    }

    /* 	Edit Transcode	 */
}

/* End of file transcode.php */
/* Location: ./application/controllers/transcode.php */