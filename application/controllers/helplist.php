<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Helplist extends My_Controller{
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
        $search['sort']='id';
        $search['sortby']='desc';
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
        $this->show_view('help/pages',$data);
    }    
   
}

?>