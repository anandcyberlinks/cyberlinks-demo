<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Role extends MY_Controller {

    public $user_id = null;
    public $user = null;
    public $role_id = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('role_model');
        $this->load->library('session');
        $this->load->helper('url');
        $per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
        $session = $this->session->all_userdata();
        $this->user = $session[0]->username;
        $this->role_id = $session[0]->role_id;
        $this->user_id = $session[0]->id;
        //echo $session[0]->role;
        if ($session[0]->role != 'Admin') {
            redirect(base_url());
        }
    }

    function index() {
        $data['welcome'] = $this;
        $data['role'] = $this->role_model->fetchrole($this->user_id);
        $this->show_view('rolesetting', $data);
    }

    function addpermission() {
        $data = $_GET;
        $this->role_model->addpermision($data);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_permission_changed'))));
        redirect(base_url() . 'role');
    }

    function deletepermission() {
        $data = $_GET;
        $this->role_model->deletepermission($data);
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_permission_deleted'))));
        redirect(base_url() . 'role');
    }

    function addrole() {
        if (isset($_POST['submit'])) {
            unset($_POST['submit']);
            $_POST['owner_id'] = $this->user_id;
            $this->role_model->addrole($_POST);
            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_role_add'))));
            redirect(base_url() . 'role');
        } else {
            $data['welcome'] = $this;
            $this->show_view('addrole', $data);
        }
    }

    function checkrole() {
        $data['role'] = $_GET['name'];
        if ($data['role'] != 'admin' && $data['role'] != 'superadmin') {
            $data['owner_id'] = $this->user_id;
            $result = $this->role_model->checkrole($data);
            if (count($result) == '0') {
                echo '1';
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }
    
    function deleterole(){
        $id = $_GET['id'];
        $res = $this->role_model->checkrolefor($id);
        if($res >= '1'){
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$res." ". $this->loadPo('User(s) having this role, You Can\'t Delete this') .'</div></div></section>');
                redirect(base_url() . 'role');
        }else{
            $this->role_model->deleterole($id);
            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_role_deleted'))));
            redirect(base_url() . 'role');
        }
}
}