<?php
ini_set('display_errors', 'On');
class Package extends MY_Controller {
    
    public $user = null;
    public $role_id = null;
    public $uid = null;
    
    function __construct(){
    parent:: __construct();
    $this->load->model('Package_model');
    $s = $this->session->all_userdata();
    $this->user = $s[0]->username;
    $this->uid = $s[0]->id;
    $this->role_id = $s[0]->role_id;
    }
    
    function index(){
        if(isset($_POST['submit'])){
            unset($_POST['submit']);
            $this->Package_model->videoPackage($_POST);
        }
        $data['welcome'] = $this;
        $data['result'] = $this->Package_model->getPackage($this->uid);
        $this->show_view('package',$data);
    }
    function creatpackage(){
        if(isset($_POST['submit'])){
            unset($_POST['submit']);
            $_POST['name'] = $_POST['package_name'];
            unset($_POST['package_name']);
            $_POST['uid'] = $this->uid;
            $this->Package_model->insertPackage($_POST);
            redirect(base_url().'package');
        }
        //$data['result'] = $this->Package_model->fetchDuration();
        //echo '<pre>';print_r($data['result']);echo '</pre>'; exit;
        $data['welcome'] = $this;
        $this->show_view('addpackage', $data);
    }
    function changeStatus(){
        $data['id'] = $this->uri->segment(3);
        $data['status'] = $this->uri->segment(4);
        $this->Package_model->changeStatus($data);
        redirect(base_url().'package');
    }
    function video_detail(){
        $data['welcome'] = $this;
        $data['result'] = $this->Package_model->get_video($this->uid);
        $this->load->view('managevideopackage', $data);

    }
    
    function price(){
        if(isset($_POST['submit'])){
            unset($_POST['submit']);
            $this->Package_model->insertprice($_POST);
            redirect(base_url().'package');
        }
        $id = $this->uri->segment(3);
        $data['result'] = $this->Package_model->get_dyration($this->uid, $id);
        $this->load->view('price', $data);
    }
    
    function deletePackage(){
        $id = $this->uri->segment(3);
        $this->Package_model->deletePac($id);
        redirect(base_url().'package');
    }
    
    function checkpackage() {
        $data['package_name'] = $_GET['package_name'];
        $result = $this->Package_model->Checkemail($data);
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }
}
