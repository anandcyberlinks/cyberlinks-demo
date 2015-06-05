<?php

class Acl extends MY_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->model('acl_model');
        
        
    }
    
    function index(){
        $uid = $this->uri->segment(3);
        $role_id = $this->acl_model->get_roleid($uid);
        if(isset($_POST['save'])){
            $this->acl_model->addpermision($uid, (isset($_POST['module']))?$_POST['module']:'');
            $msg = $this->loadPo('Premission Succefully saved');
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            //redirect(base_url().'acl')
        }
        ######## User name for specafic user
        $this->db->select('username');
        $this->db->where(array('id'=>$uid));
        $username = $this->db->get('users')->result();
        #####
        
        
        $data['username'] = $username[0]->username;
        $data['welcome'] = $this;
        $data['module'] = $this->acl_model->module($uid, $role_id);
        //echo "<pre>";        print_r($data['module']); die;
        $this->show_view('acl/acl', $data);
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

