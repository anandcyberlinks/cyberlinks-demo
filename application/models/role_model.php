<?php

class Role_Model extends CI_Model{
    
    function fetchrole($id){
        //$this->db->where_not_in('name', array('admin', 'superadmin'));
        $this->db->where('owner_id', $id);
        $query = $this->db->get('roles');
        //echo $this->db->last_query();
        return $query->result();
    }
    
    function addpermision($data){
        $this->db->insert('permission', $data);
    }
    
    function deletepermission($data){
        $this->db->delete('permission', $data);
    }
    
    function checkrole($data){
        $this->db->where('owner_id', $data['owner_id']);
        $this->db->where('name', $data['role']);
        $query = $this->db->get('roles');
        return $query->result();       
    }
    
    function addrole($data){
        $this->db->insert('roles', $data);
    }
    function checkrolefor($id){
        $this->db->where('role_id', $id);
        $query = $this->db->get('users');
       // echo $this->db->last_query();
        return count($query->result());
    }
    function deleterole($id){
        $this->db->delete('roles', array('id'=>$id));
    }
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

