<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Super_admin extends MY_Controller {

    public $user_id = null;
    public $user = null;
    public $role_id = null;
    public $role = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('user_model');
        $this->load->model('videos_model');
        $this->load->model('super_admin_model');
        $this->load->library('session');
        $this->load->helper('url');
        $session = $this->session->all_userdata();
        if (isset($session[0])) {
            $this->user_id = $session[0]->id;
            $this->user = $session[0]->username;
            $this->role_id = $session[0]->role_id;
            $this->role = $session[0]->role;
        }
    }
    
    /*
     * Super Admin Dashboard
     */
    
    function dashboard() {
        $data['result'] = $this->session->all_userdata();
        $data['welcome'] = $this;
        $data['videos'] = $this->videos_model->get_videocountstatus($this->user_id);
        $data['videos_count'] = $this->super_admin_model->get_totalvideos($this->user_id);
        $data['content_provider_count'] = $this->super_admin_model->count_content_provider();
        $data['advertisers_count'] = $this->super_admin_model->count_advertisers();
        $data['customers_count'] = $this->super_admin_model->count_customers();
        
       /* echo $data['videos_count'].'<br>';
        echo $data['content_provider_count'].'<br>';
        echo $data['advertisers_count'].'<br>'; exit; */
        
        $date = $this->getMonths("August 2012");
        //echo "<pre>"; print_r($date);
        foreach ($date as $key=>$val){
            $data['years'][] = $key;
        }
       //echo "<pre>"; print_r($data['years']); die;
        //$data['years'] = array(2013 => 2013, 2014 => 2014, 2015 => 2015);
        $data['months'] = array(1 => "Jan", 2 => "Feb", 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $this->show_view('super_admin_dashboard', $data);
    }
    
    /*
     * Function to get all video list
     */
    function all_video() {
        $searchterm = '';
        if ($this->uri->segment(2) == '') {
            $this->session->unset_userdata('search_form');
        }
        $sort = $this->uri->segment(3);
        $sort_by = $this->uri->segment(4);
        $data['welcome'] = $this;
        switch ($sort) {
            case "category":
                $sort = 'b.category';
                if ($sort_by == 'asc')
                    $data['show_c'] = 'desc';
                else
                    $data['show_c'] = 'asc';
                break;
            case "user":
                $sort = 'a.uid';
                if ($sort_by == 'asc')
                    $data['show_u'] = 'desc';
                else
                    $data['show_u'] = 'asc';
                break;
            case "status":
                $sort = 'a.status';
                if ($sort_by == 'asc')
                    $data['show_s'] = 'desc';
                else
                    $data['show_s'] = 'asc';
                break;
            case "created":
                $sort = 'a.created';
                if ($sort_by == 'asc')
                    $data['show_ca'] = 'desc';
                else
                    $data['show_ca'] = 'asc';
                break;
            case "title":
                $sort = 'a.title';
                if ($sort_by == 'asc')
                    $data['show_t'] = 'desc';
                else
                    $data['show_t'] = 'asc';
                break;
            default:
                $sort_by = 'desc';
                $sort = 'a.id';
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
       // echopre($searchterm);
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "video/index/";
        $config["total_rows"] = $this->super_admin_model->get_videoscount($searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->super_admin_model->get_videos(PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['category'] = $this->super_admin_model->get_categories();
        $data['total_rows'] = $config["total_rows"];
        //echo "<pre>"; print_r($data['result']); die;
        $this->show_view('superadmin/search_video', $data);
    }
    
    function test(){
        $post_data['notification_type'] = 'device_by_tag';
        //$post_data['device_id'] = 7778888888567;
        $post_data['keywords'] = array('fun','cricket');
        $post_data['device_type'][0] = 'ios';
        $post_data['device_type'][1] = 'android';
        $this->videos_model->push_notification_data($post_data);
    }

    

}
