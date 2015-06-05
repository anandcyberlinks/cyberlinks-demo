<?php

class Acl_model extends CI_Model {

    function module($uid, $role_id) {
        $this->db->select('modules.id, modules.name, modules.icon-class as icon');
        $this->db->from('modules');
        $this->db->join('module_role','modules.id = module_role.module_id');
        $this->db->where('module_role.role_id', $role_id);
        $this->db->order_by('modules.order', 'ASC');
        $module = $this->db->get()->result();
        $temp = array();
        foreach ($module as $val) {
            $val->permit = $this->check_permision($val->id, $uid);
            $val->child = $this->child($val->id, $uid);
            $temp[] = $val;
        }
        return $temp;
        //echo "<pre>"; print_r($temp); die;
    }

    function child($parent_id, $uid) {
        $this->db->select('id, name');
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('name', 'ASC');
        $child = $this->db->get('modules')->result();
        if (count($child) == 0) {
            return '';
        } else {
            foreach($child as $val){
                $val->permit = $this->check_permision($val->id, $uid);
                $childs[] = $val;
            }
            return $childs;
            
        }
    }

    function addpermision($uid, $modules) {
        //print_r($modules);
        $this->db->delete('module_permission', array('user' => $uid));

        if (isset($modules) && $modules != '') {
            
            foreach ($modules as $key => $val) {
                //echo $key.'<br>';
                $parent[$val] = $val;
                $this->db->insert('module_permission', array('modules_id' => $key, 'user' => $uid));
            }
            foreach ($parent as $val) {
                $this->db->insert('module_permission', array('modules_id' => $val, 'user' => $uid));
            }
        }
    }
    
    function check_permision($module_id, $uid){
        $this->db->select('id');
        $this->db->where('modules_id', $module_id);
        $this->db->where('user', $uid);
        $per = $this->db->get('module_permission')->result();
        //echo $this->db->last_query().'<br>';
        if(count($per)==0){
            return 0;
        }else{
            return 1;
        }
    }
    
    function get_roleid($uid){
        $this->db->select('role_id');
        $this->db->where('id', $uid);
        $result = $this->db->get('users')->result();
        return $result[0]->role_id;
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

