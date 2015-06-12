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
    
    function categoryEvents($uid, $start, $limit, $data){
        if($param){
            $this->db->limit($param['limit'],$param['offset']);            
        }
        
        $this->db->select("a.id,a.status,a.name,a.keywords as tags,b.category as category_name,a.customer_id,d.first_name AS user_name,d.image as user_thumbnail,c.thumbnail_url as thumbnail,c.ios,c.android,c.windows,c.web,a.status",FALSE);
        $this->db->from('channels a');
        $this->db->join('channel_categories b','a.category_id=b.id','RIGHT');
        $this->db->join('livestream c','c.channel_id=a.id');
        //$this->db->join('livechannel_epg e','e.channel_id=a.id','left');
        $this->db->join('customers d','a.customer_id=d.id','LEFT');
        if($cid!=''){
            $this->db->where('a.category_id',$cid);
        }
        if($userid !=''){
            $this->db->where('a.customer_id',$userid);
        }
        $this->db->where('uid',$uid);
        $this->db->order_by('a.id','desc');
       $query = $this->db->get();       
     //  echo $this->db->last_query();die;
       $result = $query->result();
       
       if($result){
       /*array_walk ( $result, function (&$key) {                
                //-- epg ---//
               $key->epg = $this->getLivechannelEpg($key->channel_id);               
               //-- Vast file --//
              $key->vast = $this->getAdsRevive($lat,$lng,$age,$key->keywords,$gender);             
            } );*/
       }       
       return $result;
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

