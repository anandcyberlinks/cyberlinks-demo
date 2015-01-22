<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
 
 class Advertising extends MY_Controller{
    
    function __construct()
    {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('videos_model');
        $this->load->helper('common');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
    }
    
    function index()
    {
        $data['welcome'] = $this;
        $sort_by = 'desc';
        $sort = 'a.id';
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "advertising/index/";
        $config["total_rows"] = $this->videos_model->get_videocount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->videos_model->get_video($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
  //echo '<pre>';print_r( $data['result']);
        $this->show_view('advertising/cuepoints',$data);
    }
 }