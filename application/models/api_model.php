<?php

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function checkuser($data) {
        $this->db->select('*');
        $this->db->where('username', $data['user']);
        $this->db->where('password', $data['pass']);
        $query = $this->db->get('users');
        //echo $this->db->last_query();
        return $query->result();
    }

    function checktokenvalid($data) {
        $userid = $data['id'];
        $t = time();
        $t2 = time() + 300;
        $time = date("Y-m-d h:i:s", $t);
        $this->db->select('*');
        $this->db->where('username', $user);
        $this->db->where('created <=', $time);
        $this->db->where('expiry >=', $time);
        $query = $this->db->get('token');
        //echo$this->db->last_query();
        return $query->result();
    }

    function inserttocken($id) {
        $t = time();
        $time = date("Y-m-d h:i:s", $t);
        $token = md5(uniqid()) . md5(time());
        $t = time();
        $t2 = time() + 120;
        $time = date("Y-m-d h:i:s", $t);
        $exp = date("Y-m-d h:i:s", $t2);
        $data = array('token' => $token, 'created_time' => $time, 'owner_id' => $id, 'hit_time' => $time);
        $this->db->insert('api_token', $data);
        //echo $this->db->last_query();
        return $this->db->affected_rows();
    }

    function checktoken($id) {
        $t = time() - 120;
        $time = date("Y-m-d h:i:s", $t);
        $this->db->select('*');
        $this->db->where('owner_id', $id);
        $this->db->where('hit_time >=', $time);
        $query = $this->db->get('api_token');
        //echo $this->db->last_query(); die;
        return $query->result();
    }

    function fetchtoken($token) {
        $t = time() - 120;
        $time = date("Y-m-d h:i:s", $t);
        $this->db->select('*');
        $this->db->where('token', $token);
        $this->db->where('hit_time >=', $time);
        $query = $this->db->get('api_token');
        //echo $this->db->last_query(); die;
        return $query->result();
    }

    function updatetoken($token) {
        $t = time();
        $time = date("Y-m-d h:i:s", $t);
        $this->db->set(array('hit_time' => $time));
        $this->db->where('token', $token);
        $this->db->update('api_token');

        //echo $this->db->last_query();
    }

    function fetch($data) {
        $this->db->select('*');
        $query = $this->db->get('track_url');
        return $query->result();
    }

    function deletetoken($id) {
        $this->db->where('owner_id', $id);
        $this->db->delete('api_token');
        //echo $this->db->last_query();
    }

    function get_video($uid) {
        $this->db->select('a.*, b.category , c.username');
        $this->db->from('contents a');
        $this->db->where('a.uid', $uid);
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

