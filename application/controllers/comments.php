<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comments extends MY_Controller {

    public $user_id = null;
    public $user = null;
    public $role_id = null;

    public function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('Comment_model');
        $this->load->library('session');
        $per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
        $this->load->helper('url');
        $this->load->library('form_validation');
        $session = $this->session->all_userdata();
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
        $searchterm='';
        if($this->uri->segment(2) ==''){                
            $this->session->unset_userdata('search_form');
        }
        $data['welcome'] = $this;
        if (isset($_POST['search']) && $_POST['search'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        }
        $searchterm = $this->session->userdata('search_form');

        
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "comments/index";
        $config["total_rows"] = $this->Comment_model->getCommentCount($this->user_id, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        //$s = $this->user = $session[0]->username;
        $data['comments'] = $this->Comment_model->getComment($config["per_page"], $page, $this->user_id, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('comments', $data);
    }
    
    public function deleteComment() {
        $id = $_GET['id'];
        $last_id = $this->Comment_model->deleteComment($id);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_delete'))));
        redirect('comments');
    }


    public function editComment($post){
        $this->data['welcome'] = $this;
        if (isset($_POST['Submit']) && $_POST['Submit'] == "Update") {
            $this->form_validation->set_rules($this->validation_rules['updateComment']);
            if ($this->form_validation->run()) {
                $_POST['id'] = $this->uri->segment(3);
                $this->Comment_model->updateComment($_POST);
                $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_update'))));
                redirect('comments');
            } else {
                $id = $this->uri->segment(3);
                $this->data['result'] = $this->Comment_model->getCommentData($id);
                $this->show_view('editComments', $this->data);
            }
        } else {
            $id = $this->uri->segment(3);
            $this->data['result'] = $this->Comment_model->getCommentData($id);
            $this->show_view('editComments', $this->data);
        }
    }
    
    public function setStatus() {
        $this->Comment_model->setStatus($_POST);
        echo 1;
        exit();
    }

    public function setApprovedStatus() {
        $this->Comment_model->setApprovedStatus($_POST);
        echo 1;
        exit();
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */