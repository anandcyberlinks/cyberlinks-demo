<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comments extends MY_Controller {

    public $user_id = null;
    public $user = null;
    public $role_id = null;

    public function __construct() {
        parent::__construct();
        $this->load->model('Comment_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('form_validation');

        $session = $this->session->all_userdata();
        //echo '<pre>';	print_r($session); die;
        $this->user = $session[0]->username;
        $this->role_id = $session[0]->role_id;
        $this->user_id = $session[0]->id;
    }

    protected $validation_rules = array(
        'updateComment' => array(
            array(
                'field' => 'comment',
                'label' => 'Comment',
                'rules' => 'trim|required'
            )
        )
    );

    public function index() {
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "comments/index";
        $config["total_rows"] = $this->Comment_model->getRecord_count($this->user_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //$s = $this->user = $session[0]->username;
        $data['comments'] = $this->Comment_model->getComment($config["per_page"], $page, $this->user_id);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        //$data['mypage'] = $this->mypage ;
        //$data['menu'] = $this->menu_bar() ;
        $this->show_view('comments', $data);
    }

    public function deleteComment() {
        $id = $_GET['id'];
        $last_id = $this->Comment_model->deleteComment($id);
        $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Comment Delete successfully.</div></div></section>');
        redirect('comments');
    }

    public function setStatus() {
        $post_data = array();
        $post_data['id'] = $this->input->post('id');
        $post_data['status'] = $this->input->post('status');
        $post_data['updated_date'] = date('Y-m-d');
        $this->Comment_model->setStatus($post_data);
        echo 1;
        exit();
    }

    public function setApprovedStatus() {
        $post_data = array();
        $post_data['id'] = $this->input->post('id');
        $post_data['approved'] = $this->input->post('approved');
        $post_data['updated_date'] = date('Y-m-d');
        $this->Comment_model->setApprovedStatus($post_data);
        echo 1;
        exit();
    }

    public function searchComment(){
        $this->data['welcome'] = $this;
        if (isset($_POST['search']) && $_POST['search'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        }
        $search_for = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "comments/searchComment/";
        $config["total_rows"] = $this->Comment_model->searchComment1($search_for, $this->user_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["comments"] = $this->Comment_model->searchComment($config["per_page"], $page, $search_for, $this->user_id);
        $this->data["links"] = $this->pagination->create_links();
        $this->data["search_data"] = $_POST;
        $this->data['total_rows'] = $config["total_rows"];
        $this->show_view('comments', $this->data);
    }

    public function editComment($post){
        $this->data['welcome'] = $this;
        if (isset($_POST['Submit']) && $_POST['Submit'] == "Update") {
            $this->form_validation->set_rules($this->validation_rules['updateComment']);
            if ($this->form_validation->run()) {
                $post_data = array();
                $post_data['id'] = $this->uri->segment(3);
                $post_data['comment'] = $this->input->post('comment');
                $post_data['updated_date'] = date('Y-m-d');
                $this->Comment_model->updateComment($post_data);
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Comment Update Successfully.</div></div></section>');
                redirect('comments');
            } else {
                $id = $this->uri->segment(3);
                //	$this->data['mypage'] = $this->mypage;
                //	$this->data['menu'] = $this->menu_bar();
                $this->data['edit'] = $this->Comment_model->editComment($id);
                $this->show_view('editComments', $this->data);
            }
        } else {
            $id = $this->uri->segment(3);
            //	$this->data['mypage'] = $this->mypage;
            //	$this->data['menu'] = $this->menu_bar();
            $this->data['edit'] = $this->Comment_model->editComment($id);
            //print_r($this->data['edit']);			
            $this->show_view('editComments', $this->data);
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */