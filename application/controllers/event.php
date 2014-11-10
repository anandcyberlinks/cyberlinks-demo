<?php

ini_set('display_errors', 'On');

class Event extends MY_Controller {

    public $uid = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('event_model');
        $s = $this->session->all_userdata();
        $this->uid = $s[0]->id;
    }

    function index() {
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $data['welcome'] = $this;
        $this->load->library("pagination");
        $config["base_url"] = base_url() . "event/index/";
        $config["total_rows"] = $this->event_model->countAll($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->event_model->fetchevents($this->uid, PER_PAGE, $page, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $this->show_view('events', $data);
    }

    function addevent() {
        $data['welcome'] = $this;
        if (isset($_POST['submit']) && $_POST['submit'] = 'Submit') {
            $post['name'] = $_POST['name'];
            $date = explode('-', $_POST['start_date']);
            $post['start_date'] = str_replace('/', '-', $date['0']);
            $post['end_date'] = str_replace('/', '-', $date['1']);
            $post['url'] = $_POST['url'];
            $post['uid'] = $this->uid;
            $post['description'] = $_POST['description'];
            $post['status'] = $_POST['status'];
            $this->event_model->insertEvent($post);
            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_add'))));
            redirect(base_url() . 'event');
        }
        $this->show_view('add_event', $data);
    }

    function deleteevent() {
        $this->db->delete('events', array('id' => $_GET['id']));
        $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_delete'))));
        redirect($_GET['curl']);
    }

    function edit() {
        $data['welcome'] = $this;
        $id = base64_decode($_GET['action']);
        if (isset($_POST['submit']) && $_POST['submit'] = 'Submit') {
            $post['id'] = $id;
            $post['name'] = $_POST['name'];
            $date = explode('-', $_POST['start_date']);
            $post['start_date'] = str_replace('/', '-', $date['0']);
            $post['end_date'] = str_replace('/', '-', $date['1']);
            $post['url'] = $_POST['url'];
            $post['uid'] = $this->uid;
            $post['description'] = $_POST['description'];
            $post['status'] = $_POST['status'];
            $this->event_model->insertEvent($post);
            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_record_update'))));
            redirect(base_url() . 'event');
            
        }
        $data['value'] = $this->event_model->fetchEventbyId($id);
        $this->show_view('add_event', $data);
    }

}
