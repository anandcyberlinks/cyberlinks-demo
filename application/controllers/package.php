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
        $id = $this->uri->segment(3);
        $data['package'] = $this->Package_model->getPack($id);
        $data['result'] = $this->Package_model->get_video($id);
        $this->show_view('package_video', $data);
    }
    
    function price(){
        if(isset($_POST['submit'])){
            unset($_POST['submit']);
            $this->Package_model->insertprice($_POST);
            redirect(base_url().$_POST['content_type']);
        }
        $id = $this->uri->segment(3);
        
        $data['type'] = $this->Package_model->getType($id, $_GET['type']);
        $data['result'] = $this->Package_model->get_dyration($this->uid, $id, $_GET['type']);
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
    
    function addVideo() {
        $searchterm='';
        if($this->uri->segment(2) ==''){                
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
        $pid = $this->uri->segment(3);
        $list = $this->Package_model->get_video($pid);
        $ids = array('0'=>0);
        foreach ($list as $val){
            $ids[] = $val->id;
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "package/addVideo/".$pid;
        $config["total_rows"] = $this->Package_model->get_videocount($this->uid, $searchterm, $ids);
        $config["per_page"] = 10;
        $config["uri_segment"] = 4;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['result'] = $this->Package_model->get_allvideo($ids, $this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['category'] = $this->Package_model->get_category($this->uid);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('video_pack', $data);
    }
    
    function VideoPack(){
        $pid = $this->uri->segment(3);
        $cid = $this->uri->segment(4);                               
        $this->Package_model->add_video($pid, $cid);
        echo json_encode(array('success'=>TRUE,'message'=>"Article added"));
    }
    function delete(){
        $id = $this->uri->segment(3);
        $this->Package_model->delete_vid($id);
        echo json_encode(array('success'=>TRUE,'message'=>"Article deleted"));
    }
}