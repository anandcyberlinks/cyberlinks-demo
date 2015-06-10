<?php

class Super_model extends CI_Model {

    function __construct()
    {
       parent::__construct();
       $this->load->database();
       
    }
    function countuser($id, $searchterm) {
        $this->db->where('owner_id', $id);
        if(isset($searchterm['status']) && $searchterm['status'] !=''){
            $this->db->where('users.status',trim($searchterm['status']));
        }
        if(isset($searchterm['username']) && $searchterm['username'] !=''){
            $this->db->like('users.username',trim($searchterm['username']));
        }
        if(isset($searchterm['name']) && $searchterm['name'] !=''){
            $this->db->like('users.first_name',trim($searchterm['name']));
        }
        if(isset($searchterm['email']) && $searchterm['email'] !=''){
            $this->db->where('users.email',trim($searchterm['email']));
        }
        $query = $this->db->get('users');
        //$query = $this->db->get('customers');

        return count($query->result());
    }
    
    function getowner() {
        $this->db->select('id,username');
        $this->db->from('users');
        $query = $this->db->get();
        return $query->result();
    }
    function countCuser($searchterm) {
        $this->db->select('*');
        $this->db->from('customers');
        if(isset($searchterm['status']) && $searchterm['status'] !=''){
            $this->db->where('status',trim($searchterm['status']));
        }
        if(isset($searchterm['username']) && $searchterm['username'] !=''){
            $this->db->like('username',trim($searchterm['username']));
        }
        if(isset($searchterm['owner_id']) && $searchterm['owner_id'] !=''){
            $this->db->where('owner_id',trim($searchterm['owner_id']));
        }
        if(isset($searchterm['email']) && $searchterm['email'] !=''){
            $this->db->where('email',trim($searchterm['email']));
        }
        //$this->db->where('owner_id', $id);
        $query = $this->db->get();
        return count($query->result());
    }


    function deleteuser($data) {
        $id = $data['id'];
        $this->db->delete('users', array('id' => $id));
    }

    /*
     * Function For Check user
     */

    public function fetchUser($id, $limit, $start, $searchterm, $sort = '', $sort_by = '') {
        //print_r($searchterm);
        $this->db->select('users.*, roles.name as role');
        $this->db->from('users');
        $this->db->join('roles', 'users.role_id = roles.id');
        $this->db->where('users.owner_id', $id);
        if(isset($searchterm['status']) && $searchterm['status'] !=''){
            $this->db->where('users.status',trim($searchterm['status']));
        }
        if(isset($searchterm['username']) && $searchterm['username'] !=''){
            $this->db->like('users.username',trim($searchterm['username']));
        }
        if(isset($searchterm['name']) && $searchterm['name'] !=''){
            $this->db->like('users.first_name',trim($searchterm['name']));
        }
        if(isset($searchterm['email']) && $searchterm['email'] !=''){
            $this->db->where('users.email',trim($searchterm['email']));
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); 
        //print_r($query->result()); die;
        return $query->result();
    }
    public function fetchcUser($searchterm, $limit, $start, $sort = '', $sort_by = '') {
        $this->db->select('a.id, a.username, a.email, a.first_name, a.last_name, a.gender, a.dob, a.contact_no, a.image, a.status, a.created, a.owner_id, a.online, b.username as owner_name');
        $this->db->from('customers a');
        $this->db->join('users b','a.owner_id=b.id','left');
        if(isset($searchterm['status']) && $searchterm['status'] !=''){
            $this->db->where('a.status',trim($searchterm['status']));
        }
        if(isset($searchterm['username']) && $searchterm['username'] !=''){
            $this->db->like('a.username',trim($searchterm['username']));
        }
        if(isset($searchterm['owner_id']) && $searchterm['owner_id'] !=''){
            $this->db->where('a.owner_id',trim($searchterm['owner_id']));
        }
        if(isset($searchterm['email']) && $searchterm['email'] !=''){
            $this->db->where('a.email',trim($searchterm['email']));
        }
        //$this->db->where('owner_id', $id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //  echo $this->db->last_query(); 
        // print_r($query->result()); die;
        return $query->result();
    }
    
    

    function Fetchrole($id) {
        $this->db->select('id, name');
        $this->db->where('owner_id', $id);
        $this->db->from('roles');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    public function updatestatus($data) {
        $id = $data['id'];
        $status = $data['status'];
        $token = '';
        $this->db->where('id', $id);
        if ($status == 'active') {
            $data = array('status' => 'inactive',);
            $this->db->update('users', array('token'=>$token));
        }
        if ($status == 'inactive') {
            $data = array('status' => 'active',);
            $token = uniqid();
            $this->db->update('users', array('token'=>$token));
        }
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return $token;
    }
    public function updatestatuscustomer($data) {
        $id = $data['id'];
        $status = $data['status'];
        $this->db->where('id', $id);
        if ($status == 'active') {
            $data = array('status' => 'inactive');
        }
        if ($status == 'inactive') {
            $data = array('status' => 'active');
        }
        $this->db->where('id', $id);
        $res = $this->db->update('customers', $data);
        //echo $this->db->last_query();die;
        return $res;
    }
    function cprofile($data) {
        $this->db->where('id', $data);
        $query = $this->db->get('customers');
        return $query->result();
    }
    public function Checkemail($data) {
        $email = $data['email'];
        $this->db->select('*');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function Checkusername($data) {
        $username = $data['username'];
        $this->db->select('*');
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->result();
    }

    public function inseruser($data) {
        $t = time();
        $time = date("Y-m-d h:i:s", $t);
        $data['created'] = $time;
        $this->db->insert('users', $data);
        //echo $this->db->last_query();
        return $this->db->insert_id();
    }

    public function genratetoken($data) {
        $action = $data['action'];
        $id = $data[0]->id;
        $token = $data['token'];
        $t2 = time() + 120;
        $exp = date("Y-m-d h:i:s", $t2);
        $data = array('user_id' => $id, 'token' => $token, 'action' => $action, 'expiry' => $exp);
        $this->db->insert('token', $data);
        //echo $this->db->last_query();
        return $this->db->insert_id();
    }

    public function fetchtoken($id) {
        $this->db->select('*');
        $this->db->from('token');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function profile($data) {
        $this->db->where('id', $data);
        $query = $this->db->get('users');
        return $query->result();
    }
    
    function updateuser($data, $id){
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        
    }
    function do_upload($user_id, $image, $abpath) {
            $this->db->where('id', $user_id);
            $this->db->set('image', $image);
            $this->db->update('customers');
        //echo $this->db->last_query(); die;
    }
     public function fetchdata($id) {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

