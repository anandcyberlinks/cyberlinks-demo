<?php

class User_Model extends CI_Model {

    function profile($data) {
        $this->db->where('username', $data);
        $query = $this->db->get('users');
        return $query->result();
    }

    /*
     * Function For Check user
     */

    public function CheckUser($data) {
        $username = $data['username'];
        $pass = $data['password'];
        $this->db->select('users.*, roles.name as role, roles.id as role_id');
        $this->db->from('users');
        $this->db->join('roles', 'users.role_id = roles.id');
        $this->db->where('users.username', $username);
        $this->db->where('users.password', $pass);
        $this->db->where('users.status', 'active');
        $query = $this->db->get();
        //echo $this->db->last_query(); 
        // print_r($query->result()); die;
        return $query->result();
    }

    /*
     * Check Email For Forgot Password
     */

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

    public function updateuser($data) {
        //print_r($data);
        $id = $data['id'];
        $data = array('status' => '1',);
        $this->db->where('id', $id);
        $this->db->update('users', $data);
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

    public function inseruser($data) {
        $this->db->insert('users', $data);
        //echo $this->db->last_query();
        return $this->db->insert_id();
    }

    public function fetchtoken() {
        $this->db->select('*');
        $this->db->from('token');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function fetchtokendetail($token) {
        $this->db->select('*');
        $this->db->from('token');
        $this->db->where('token', $token);
        $query = $this->db->get();
        return $query->result();
    }

    public function fetchuser($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function password($data) {
        $id = $data['id'];
        $data = array('password' => $data['password'],);
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        //return $this->db->affected_rows();
    }

    /*
     * Function for delete token after successfully password reset
     */

    public function deletetoken($user) {
        $this->db->delete('token', array('user_id' => $user));
        // echo $this->db->last_query(); exit;
    }

    function do_upload($user_id, $image, $abpath) {
        $this->db->where('uid', $user_id);
        $this->db->where('type', 'logo');
        $temp = $this->db->get('files')->result();
        if(count($temp) == '0'){
            $this->db->insert('files', array('uid'=>$user_id, 'type'=>'logo','relative_path'=>$image, 'absolute_path'=>$abpath ));
            $fileid = $this->db->insert_id();
            $this->db->where('id', $user_id);
            $this->db->set('image',$fileid);
            $this->db->update('users');
        }
        $this->db->where('id', $temp[0]->id);
        $this->db->set('relative_path', $image);
        $this->db->set('absolute_path', $abpath);
        $this->db->update('files');
        //echo $this->db->last_query(); die;
    }
    

}
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    