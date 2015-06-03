<?php
ini_set('display_errors', 1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dform extends MY_Controller {

    public $user = null;
    public $role_id = null;
    public $uid = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('Advance_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
        $this->uid = $s[0]->id;
    }

    protected $validation_rules = array
        (
        'add_field' => array(
            array(
                'field' => 'field_title',
                'label' => 'field_title',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'field_name',
                'label' => 'field_name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'field_type',
                'label' => 'field_type',
                'rules' => 'trim|required'
            )
        ),
        'update_Form' => array(
            array(
                'field' => 'form_name',
                'label' => 'form_name',
                'rules' => 'trim|required'
            ) 
        )
        ,
        'add_Form' => array(
            array(
                'field' => 'form_name',
                'label' => 'form_name',
                'rules' => 'trim|required'
            ) 
        )
    );
    function index(){
        $data['welcome'] = $this;
        $data['forms'] = $this->Advance_model->fetchForm($this->uid);
        $this->show_view('form', $data);
    }
    function field() {
        $searchterm='';
        if($this->uri->segment(2) ==''){                
            $this->session->unset_userdata('search_form');
        }
        $sort = $this->uri->segment(3);
        $sort_by = $this->uri->segment(4);
        switch ($sort) {
            case "category":
                $sort = 'a.category';
                if ($sort_by == 'asc')
                    $this->data['show_c'] = 'desc';
                else
                    $this->data['show_c'] = 'asc';
                break;
            case "parent":
                $sort = 'parent';
                if ($sort_by == 'asc')
                    $this->data['show_p'] = 'desc';
                else
                    $this->data['show_p'] = 'asc';
                break;
            case "status":
                $sort = 'a.status';
                if ($sort_by == 'asc')
                    $this->data['show_s'] = 'desc';
                else
                    $this->data['show_s'] = 'asc';
                break;
            default:
                $sort = 'a.category';
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } elseif (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "category/index";
        $config["total_rows"] = $this->Advance_model->getRecordCount($searchterm );
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_rows'] = $config["total_rows"];
        $this->data['form_name'] = $this->Advance_model->fetchFormName($_GET['id']);
        $this->data['field'] = $this->Advance_model->getField($config["per_page"], $page, $sort, $sort_by, $searchterm, $_GET['id']);
        $this->show_view('fields', $this->data);
    }

    /* 	Add and Edit Form	 */

    function addForm(){
        $form_id = $this->uri->segment(3);
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_GET['action'])) {
                $id = $_GET['action'];
                $cid = base64_decode($id);
            }
            
           // $this->data['fieldtype']  = array(1=>"text",2=>"textarea",3=>"radio",4=>"checkbox");
            if (isset($cid)) {                
                $this->data['formdata'] = $this->Advance_model->formdata($cid);
                //echo '<pre>';print_r($this->data['formdata']);echo '</pre>'; exit;
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_Form']);
                    if ($this->form_validation->run()) {
                        $_POST['id'] = $cid;
                        $this->Advance_model->_saveForm($_POST);
                       // die();
                        $msg = $this->loadPo($this->config->item('success_record_update'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('dform');
                    } else {                         
                        $this->show_view('edit_form', $this->data);
                    }
                } else {
                        $this->show_view('edit_form', $this->data);
                }
            } else {
           if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                    $_POST['uid'] = $this->uid;
                    unset($_POST['submit']);
                    $this->form_validation->set_rules($this->validation_rules['add_Form']);
                    if ($this->form_validation->run()) {
                            $this->Advance_model->_saveForm($_POST);
                            $msg = $this->loadPo($this->config->item('success_record_add'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
                            redirect('dform');
                    } else {
                        $this->show_view('add_form', $this->data);
                    }
                } else {
                    $this->show_view('add_form', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'dform');
        }
    }
    
     /*Add and Edit Category	 */
    function addFields(){
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per){
            if (isset($_GET['action'])) {
                $id = $_GET['action'];
                $cid = base64_decode($id);
            }
             $this->data['fieldtype']  = array(1=>"text",2=>"textarea",3=>"radio",4=>"checkbox");
            if (isset($cid)) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['add_field']);
                    if ($this->form_validation->run()){
                        $_POST['id'] = $cid;
                        $this->Advance_model->_saveFields($_POST);
                        $msg = $this->loadPo($this->config->item('success_record_update'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('dform/field/?id='.$_GET['id']);
                    } else {
                        $this->data['edit'] = $this->Advance_model->getFieldsdata($cid);
                        $this->show_view('edit_fields', $this->data);
                    }
                } else {
                    $this->data['edit'] = $this->Advance_model->getFieldsdata($cid);
                    $this->show_view('edit_fields', $this->data);
                }
            } else {
                if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                    $_POST['uid'] = $this->uid;
                    unset($_POST['submit']);
                    $this->form_validation->set_rules($this->validation_rules['add_field']);
                    if ($this->form_validation->run()) {
                        $this->Advance_model->_saveFields($_POST);
                        $msg = $this->loadPo($this->config->item('success_record_add'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('dform/field/?id='.$_GET['id']);
                    } else {
                        $this->show_view('add_fields', $this->data);
                    }
                } else {
                    $this->show_view('add_fields', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'category');
        }
    }
    /* 	Delete Field */

    function deletefield(){
        $per = $this->checkpermission($this->role_id, 'delete');
        //echo $this->role_id;
        if ($per) {
            $this->Advance_model->deleteField($_GET['id']);
            $msg = $this->loadPo($this->config->item('success_record_Deleted'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($_GET['curl']);
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'category');
        }
    }
    
    function deleteform() {
        $per = $this->checkpermission($this->role_id, 'delete');
        //echo $this->role_id;
        if ($per) {
            $this->Advance_model->deleteForm($_GET['id']);
            $msg = $this->loadPo($this->config->item('success_record_Deleted'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect($_GET['curl']);
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_permission'))));
            redirect(base_url() . 'category');
        }
    }
    function checkform(){
        $data['form_name'] = $_GET['form_name'];
        $result = $this->Advance_model->checkform($data, $this->uid);
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */    