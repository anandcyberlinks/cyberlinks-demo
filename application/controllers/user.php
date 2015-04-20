<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user extends MY_Controller {

    public $role = null;
    public $user_id = null;
    public $user = null;
    public $role_id = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('super_model');
        $this->load->library('session');
        $this->load->helper('url');
        $s = $this->session->all_userdata();
        if ($s[0]->role != 'Superadmin' && $s[0]->role != 'Admin') {
            redirect(base_url());
        }
        $this->role = $s[0]->role;
        $this->user_id = $s[0]->id;
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
    }

    function index() {
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "user/index/";
        $config["total_rows"] = $this->super_model->countuser($this->user_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->super_model->fetchUser($this->user_id, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];


        $this->show_view('users', $data);
    }

    function customers() {
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "user/customers/";
        $config["total_rows"] = $this->super_model->countCuser($this->user_id);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->super_model->fetchcUser($this->user_id, $config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        //echo "<pre>";        print_r($data['result']); die;
        $this->show_view('cUser', $data);
    }

    public function DeleteUser() {
        $per = $this->checkpermission($this->role_id, 'delete');
        if ($per) {
            $data['id'] = $_GET['id'];
            $this->super_model->deleteuser($data);
            $msg = $this->loadPo($this->config->item('success_delete_user'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url() . 'user');
        } else {
            $this->log($this->user, 'Unauthorised Access trying to delete a user');
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'user');
        }
    }

    function changestatus() {
        $data['id'] = $_GET['id'];
        $data['status'] = $_GET['status'];

        $token = $this->super_model->updatestatus($data);
        $this->log($this->user, 'Status Changed For user id-> ' . $data['id']);
        if($token != ''){
            $email = $_GET['email'];
            $body = file_get_contents(base_url() . 'layout/token_email?token=' . $token.'&domain='.$_GET['domain']);
            $subject = 'You Application Token';

            $this->sendmail($email, $subject, $body); //mail to user
        }
        //$this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_update_user'))));
        //redirect(base_url() . 'user');
        $return = array('status' => $data['status'], 'token' => $token);
        echo json_encode($return);
    }

    

    public function register() {
        $data['owner_id'] = $this->user_id;
        $data['userrole'] = $this->role;
        $data['welcome'] = $this;
        $data['role'] = $this->super_model->Fetchrole($this->user_id);
        if ($_POST) {
            unset($_POST['submit']);
            unset($_POST['cpassword']);
            $_POST['password'] = md5($_POST['password']);
            $_POST['token'] = uniqid();
            $data = $_POST;
            $result = $this->super_model->Checkemail($data);
            if (count($result) == '0') {
                $result = $this->super_model->Checkusername($data);
                if (count($result) == '0') {
                    $_POST['token'] = uniqid();
                    $this->super_model->inseruser($_POST);
                    $this->log($this->user, 'New user successfully inserted with username-> ' . $_POST['username']);
                    $email = $this->super_model->Checkemail($data);
                    $email['action'] = 'activation';
                    $email['token'] = sha1(md5(uniqid()) . rand('1', '5000') . md5(time()));
                    $id = $this->super_model->genratetoken($email); // Genrate new token and return id
                    $token = $this->super_model->fetchtoken($id);   //fetch ganrated token
                    $to = $_POST['email'];
                    $subject = 'Activation Mail';
                    $body = base_url() . 'layout/token/?token=' . $token[0]->token;
                    $mail = $this->sendmail($to, $subject, $body);
                    if (!$mail) {
                        $this->session->set_flashdata('message', $this->_warningmsg($this->loadPo($this->config->item('success_add_user_mail_error'))));
                        redirect(base_url() . 'user');
                    } else {
                        $this->log($user, 'Activation Mail sent Successfully to ' . $_POST['email']);
                        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_add_user_mail_sent'))));
                        redirect(base_url() . 'user');
                    }
                } else {
                    echo 'username Already exist Please try To login';
                    $this->load->view('register');
                }
            } else {
                echo 'Email Already exist Please try To login';
                $this->load->view('adduser', $data);
            }
        } else {
            $this->show_view('adduser', $data);
        }
    }

    function updateprofile() {
        //echo $this->user;
        $data['welcome'] = $this;
        $data['userrole'] = $this->role;
        $data['role'] = $this->super_model->Fetchrole($this->user_id);
        if (isset($_GET['id'])) {
            if (isset($_POST['submit'])) {
                unset($_POST['submit']);
                $this->super_model->updateuser($_POST, $_GET['id']);
                $this->log($this->user, 'profile updated for user is ' . $_GET['id']);
                $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_update_user'))));

                redirect(base_url() . 'user');
            } else {
                $id = $_GET['id'];
                $data['result'] = $this->super_model->profile($id);
                $this->show_view('edituser', $data);
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'user');
        }
    }

    function checkemail() {
        $data['email'] = $_GET['email'];
        $result = $this->super_model->Checkemail($data);
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

    function checkusername() {
        $data['username'] = $_GET['username'];
        $result = $this->super_model->Checkusername($data);
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

}
