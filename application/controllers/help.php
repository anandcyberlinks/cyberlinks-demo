<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Help extends My_Controller{
    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('help_model');
         $this->load->model('User_model');
         $this->load->helper('common');
        $this->load->library('session');
        $this->load->library('form_validation');
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->userdetail=(array)$s[0];
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
        $this->allowedVideoExt = array('mp4', 'mpg', 'mpeg', 'flv', 'wmv', 'avi');
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
    }
    
    
    function index()
    {
        $search = array();
        $search['term'] = '';
        
        if ($this->uri->segment(2) == '') {
            $this->session->unset_userdata('search_form');
        }
        $search['sort'] = $sort = $this->uri->segment(3);
        $search['sortby'] = $sort_by = $this->uri->segment(4);
        if($search['sort']==''){
            $search['sort']='id';
            $search['sortby']='desc';
        }
        switch ($sort) {
            case "status":
                $sort = 'a.status';
                if ($sort_by == 'asc')
                    $data['show_s'] = 'desc';
                else
                    $data['show_s'] = 'asc';
                break;
            case "page_description":
                $sort = 'a.page_description';
                if ($sort_by == 'asc')
                    $data['show_c'] = 'desc';
                else
                    $data['show_c'] = 'asc';
                break;
            case "page_title":
                $sort = 'a.page_title';
                if ($sort_by == 'asc')
                    $data['show_t'] = 'desc';
                else
                    $data['show_t'] = 'asc';
                break;
            default:
                $sort_by = 'desc';
                $sort = 'a.id';
        }
        
      //  if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
       //     $this->session->set_userdata('search_form', $_POST);
       // } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
       //    $this->session->unset_userdata('search_form');
       // }
       // $sterm = $this->session->userdata('search_form');
        //echopre($sterm);
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "help/index/";
        $search['user_id']=$this->userdetail['id'];
        $config["total_rows"] = $this->help_model->getpages($search,1);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->help_model->getpages($search,0,$config["per_page"],$page);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        $data['role_id']=$this->role_id;
        //$result = $data['result'] = $this->publishing_model->getSkins();
        $this->show_view('help/list',$data);
    }
    function pages()
    {
        $search = array();
        $search['term'] = '';
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "help/index/";
        $search['user_id']=$this->userdetail['id'];
        $config["total_rows"] = $this->help_model->getpages($search,1);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->help_model->getpages($search,0,$config["per_page"],$page);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        $data['role_id']=$this->role_id;
        echopre($data['result']);
        //$result = $data['result'] = $this->publishing_model->getSkins();
        $this->show_view('help/list',$data);
    }    
    function add(){
        $data['welcome'] = $this;
        if (isset($_GET['id'])) {
                $id = base64_decode($_GET['id']);
        }
        if (isset($id)) {
            if (isset($_POST['submit']) && $_POST['submit'] == 'Update'){ 
                $_POST['id']=$id;
                $_POST['user_id']=$this->userdetail['id'];
                $resid = $this->help_model->_save($_POST);
                $msg = $this->loadPo($this->config->item('success_record_update'));
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', $this->_successmsg($msg));
                redirect('help');
            }
            $editresult = $this->help_model->fetchpage($id);
            $result=$data['result']=(array)$editresult['0'];
            
            $this->show_view('help/add', $data);
        }else{
            if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') { 
                $_POST['user_id']=$this->userdetail['id'];
                $resid = $this->help_model->_save($_POST);
                $msg = $this->loadPo($this->config->item('success_record_add'));
                $this->log($this->user, $msg);
                $this->session->set_flashdata('message', $this->_successmsg($msg));
                redirect('help');  
            }
            $this->show_view('help/add', $data);
        }
    }
    function delete(){
        $id = base64_decode($_GET['id']);
        $delresult = $this->help_model->deletepage($id);
        redirect('help');  
    }
    function validfile(){
       $path='assets/upload/skins/'.$_REQUEST['fileName'];
        if (file_exists($path)) {
            echo 'exist';
        }else{
            echo 'success';
        }
        die;
    }
}

?>