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
        $this->load->model('super_admin_model');
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
    function all_video(){
        echo 'test';
    }

    

}
