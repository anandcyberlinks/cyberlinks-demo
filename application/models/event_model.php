<?php

class Event_model extends CI_Model {

    function fetchevents($uid, $start, $limit, $data) {
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";

        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('name', $data['name']);
        }
        if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $datestart . $timeEnd;
            $this->db->where("start_date > '$dateTimeStart'");
        }
        if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $dateend . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("end_date < '$dateTimeEnd'");
        }

        $this->db->where('uid', $uid);
        $this->db->from('events');
        $this->db->limit($start, $limit);
        $result = $this->db->get()->result();
        //echo $this->db->last_query();
        //echo $this->db->last_query();
        return $result;
    }

    function countAll($uid, $data) {
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";

        if (isset($data['name']) && $data['name'] != "") {
            $this->db->where('name', $data['name']);
        }
        if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $datestart . $timeEnd;
            $this->db->where("start_date > '$dateTimeStart'");
        }
        if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $dateend . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("end_date < '$dateTimeEnd'");
        }

        $this->db->where('uid', $uid);
        $this->db->from('events');
        return $this->db->count_all_results();
    }

    function insertEvent($post) {
        if (isset($post['id'])) {
            $this->db->where('id', $post['id']);
            unset($post['id']);
            $this->db->update('events', $post);
        } else {
            $this->db->insert('events', $post);
        }
    }
    function fetchEventbyId($id) {
        $this->db->where('id', $id);
        return $this->db->get('events')->result();
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

