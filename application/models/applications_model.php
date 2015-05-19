<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Applications_model extends CI_Model {

    public $uid = null;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $s = $this->session->all_userdata();
         $this->uid = $s[0]->id;
    }

    /* 	Save Application  */

    function _saveApplication($data) {
        if (isset($data['id'])) {
            $appId = $data['id'];
            $data = array(
                'app_name' => $data['application_name'],
                'app_category' => $data['application_category'],
                'timezone' => $data['timezone'],
                'app_version' => $data['application_version'],
                'app_icon' => $data['file_id'],
            );
            $this->db->set('modified', 'NOW()', FALSE);
            $this->db->where('id', $appId);
            $this->db->update('apps_registration', $data);
        } else {
            $catData = array(
                'user_id' => $this->uid,
                'app_name' => $data['application_name'],
                'app_category' => $data['application_category'],
                'timezone' => $data['timezone'],
                'app_version' => $data['application_version'],
                'app_icon' => $data['file_id'],
                'app_key' => uniqid(rand(), true)
            );
            $this->db->set($catData);
            $this->db->set('created', 'NOW()', FALSE);
            $this->db->set('modified', 'NOW()', FALSE);
            $this->db->insert('apps_registration');
            $catId = $this->db->insert_id();
        }
        return $catId;
    }

    function _saveFile($data) {
        $filename = $data['filename'];
        if (isset($filename)) {
            ###inserting file detail data in files table and return id###
            $file['name'] = $data['filename'];
            $file['type'] = $data['type'];
            $file['minetype'] = $data['minetype'];
            $file['relative_path'] = $data['relative_path'];
            $file['absolute_path'] = $data['absolute_path'];
            $file['status'] = $data['status'];
            $file['uid'] = $data['uid'];
            $file['info'] = $data['info'];
            $this->db->set($file);
            $this->db->set('created', 'NOW()', FALSE);
            $this->db->insert('files');
            $fid = $this->db->insert_id();
        }
        return $fid;
    }
    
    function get_applications_count($user_id)
    {
        $this->db->where('user_id',$user_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('apps_registration');
        return count($query->result());
    }
    
    function get_applications_history($user_id,$limit,$start)
    {
        $this->db->where('user_id',$user_id);
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get('apps_registration');
        return $query->result();
    }
    
    function get_application_detail_OLD($app_id){
        $this->db->where('id',$app_id);
        $query = $this->db->get('apps_registration');
        return $query->row();
    }
    
    function get_application_detail($app_id){
        $this->db->select('a.*, f.name as filename');
        $this->db->from('apps_registration a');
        $this->db->join('files f', 'a.app_icon = f.id', 'left');
        if ($app_id != '') {
            $this->db->where('a.id', $app_id);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function delete_application($id){
        $this->db->delete('apps_registration', array('id' => $id));
        return 1;
    }
}

?>
