<?php

class Panchang_model extends CI_Model{
    
    function insert($data){
        $this->db->where('date', $data['date']);
        $this->db->from('panchang');
        $res = $this->db->get()->result();
        if(count($res) == 0){
            $this->db->insert('panchang',$data);
        }
    }
    function get_count($uid){
        $this->db->where('u_id', $uid);
        $result = $this->db->get('panchang')->result();
        return count($result);
    }
    function get_pan($uid, $limit, $start){
        $this->db->where('u_id', $uid);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        $result = $this->db->get('panchang')->result();
        return $result;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

