<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Publishing extends My_Controller{
    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('publishing/publishing_model');
         $this->load->model('User_model');
         $this->load->helper('common');
        $this->load->library('session');
        $this->load->library('form_validation');
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
        $this->allowedVideoExt = array('mp4', 'mpg', 'mpeg', 'flv', 'wmv', 'avi');
        $this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');
    }
    
    protected $validation_rules = array
        (
        'add' => array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'dimension',
                'label' => 'Dimension',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'image',
                'label' => 'Image',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'skin',
                'label' => 'Skin',
                'rules' => 'trim|required'
            )
        ),
        'update' => array(
            array(
                'field' => 'category',
                'label' => 'Category name',
                'rules' => 'trim|required'
            ),
        )
    );

    function index()
    {        
        $data['welcome'] = $this;
        if (isset($_POST['save'])) {           
            $skin_id = $_POST['skin_id'];
            $this->User_model->saveskin($skin_id,$this->uid);
            $msg = $this->loadPo($this->config->item('success_record_update'));
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect('publishing');
        }
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "publishing/index/";
        $config["total_rows"] = $this->publishing_model->getSkins($searchterm,1);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->publishing_model->getSkins($searchterm,0,$config["per_page"],$page);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        //$result = $data['result'] = $this->publishing_model->getSkins();
        
        $this->show_view('publishing/skins',$data);
    }
        
        function skinlist()
        {            
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
        
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "publishing/";
        $config["total_rows"] = $this->publishing_model->getSkins($searchterm,1);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->publishing_model->getSkins($searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('publishing/list', $data);
        }
        
        function add() {
        
        $data['welcome'] = $this;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_GET['id'])) {
                $id = base64_decode($_GET['id']);
            }
            if (isset($id)) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    
                    $this->form_validation->set_rules($this->validation_rules['update']);
                   // if ($this->form_validation->run()) {
                        $_POST['id'] = $id;
                        $this->publishing_model->_save($_POST);
                        $msg = $this->loadPo($this->config->item('success_record_update'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('publishing');
                  //  } else {
                    //    $this->show_view('publishing/edit', $this->data);
                    //}
                } else {
                    $editresult = $this->publishing_model->fetchskin($id);
                    $result=$data['result']=(array)$editresult['0'];
                    $this->show_view('publishing/add', $data);
                  //  $this->show_view('add');
                }
            } else {
                $path= 'assets/upload/skins';
                    if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {                    
                    if($_FILES["skin_file"]["name"]) {
                        $filename = $_FILES["skin_file"]["name"];
                        $source = $_FILES["skin_file"]["tmp_name"];
                        $type = $_FILES["skin_file"]["type"];
                        $folder_name = explode(".", $filename);
                        $xmlname=$folder_name['0'].'/'.$folder_name['0'].'.xml';
                        $target_path = $path;
                        $image_target=$path.'/'.$folder_name['0'];
                        if(upload_compress_files($filename,$source,$target_path,$type)){
                            $_POST['path'] = $target_path.'/'.$xmlname;
                        }
                    }

                    if($_FILES["image_file"]["name"]) {
                         $filename = $_FILES["image_file"]["name"];
                         $source = $_FILES["image_file"]["tmp_name"];
                         $type = $_FILES["image_file"]["type"];
                         //$name = explode(".", $filename);
                         $target_path = $image_target.'/'.$filename;
                        if(move_uploaded_file($source, $target_path)){
                            $_POST['image'] = $target_path;
                        }
                    }
                    //$this->form_validation->set_rules($this->validation_rules['add']);
                   // if ($this->form_validation->run()) {                       
                            $this->publishing_model->_save($_POST);
                            $msg = $this->loadPo($this->config->item('success_record_add'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
                            redirect('publishing');                        
                    //} else {                       
                    //    $this->show_view('publishing/add', $data);
                    //}
                } else {
                    $this->show_view('publishing/add', $data);
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'category');
        }
    }
    function delete(){
        $id = base64_decode($_GET['id']);
        $editresult = $this->publishing_model->fetchskin($id);
        $result=$data['result']=(array)$editresult['0'];
        $path=substr($result['path'], 0, strrpos( $result['path'], '/') );
        //unlink($result['image']);
        $this->deleteDir($path);
        $editresult = $this->publishing_model->deleteskin($id);
        redirect('publishing');  
    }
}

?>