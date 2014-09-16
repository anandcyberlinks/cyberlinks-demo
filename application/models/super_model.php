<?php

class Super_model extends CI_Model {

    function countuser($id) {
        $this->db->where('owner_id', $id);
        $query = $this->db->get('users');

        return count($query->result());
    }

    function deleteuser($data) {
        $id = $data['id'];

        $this->db->delete('users', array('id' => $id));
    }

    /*
     * Function For Check user
     */

    public function fetchUser($id, $limit, $start, $sort = '', $sort_by = '') {
        $this->db->select('users.*, roles.name as role');
        $this->db->from('users');
        $this->db->join('roles', 'users.role_id = roles.id');
        $this->db->where('users.owner_id', $id);
        //$this->db->order_by($sort, $sort_by);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); 
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
        if ($status == 'active') {
            $data = array('status' => 'inactive',);
        }
        if ($status == 'inactive') {
            $data = array('status' => 'active',);
        }
        $this->db->where('id', $id);
        $this->db->update('users', $data);
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

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

