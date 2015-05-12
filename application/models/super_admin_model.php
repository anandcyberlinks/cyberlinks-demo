<?php
ini_set('display_errors', 'On');
class Super_admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }
    
    /*
     * Function to get total number of videos
     */
    function get_totalvideos($uid){
        $query = sprintf('SELECT
                                SUM(if(c.id is null,0,1)) as total_videos 
                                FROM `users` u 
                                left join contents c on c.uid = u.id
                                where u.id = %d OR u.owner_id = %d
                                ', $this->user_id, $this->user_id);
        $res = $this->db->query($query);
        return $res->row()->total_videos;
    }
    
    /*
     * Function to get total number of content provider
     */
    function count_content_provider(){
        $query = "SELECT count(id) as content_providers FROM `users` WHERE `role_id` = 1";
        $res = $this->db->query($query);
        return $res->row()->content_providers;
    }
    
    
    /*
     * Function to get total number of advertiser
     */
    function count_advertisers(){
        $query = "SELECT count(id) as total_advertisers FROM `users` WHERE `role_id` = 24";
        $res = $this->db->query($query);
        return $res->row()->total_advertisers;
    }
    
    /*
     * Function to get total number of customers
     */
    function count_customers(){
        $query = "SELECT count(id) as total_customers FROM `customers`";
        $res = $this->db->query($query);
        return $res->row()->total_customers;
    }
    /*
     * Get all users videos count
     */
    function get_videoscount($data=''){
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        
        $this->db->select('contents.*');
        $this->db->from('contents');
        $this->db->join('categories', 'contents.category = categories.id', 'left');
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('contents.category', $data['category']);
        }
        if (isset($_GET['filter']) && $_GET['filter'] != '') {
            $this->db->where('contents.type', $_GET['filter']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("contents.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        return count($query->result());
    }
    
    /*
     * Function to get all user's videos to superadmin
     */
    function get_videos($limit, $start, $sort = '', $sort_by = '', $data) {
        $timeStart = " 00:00:00";
        $timeEnd = " 23:59:59";
        
        $this->db->select('a.*, b.category , c.username, e.relative_path as file,e.minetype,d.duration');        
        $this->db->from('contents a');
        $this->db->join('categories b', 'a.category = b.id', 'left');
        $this->db->join('users c', 'a.uid = c.id', 'left');
        $this->db->join('videos d', 'a.id = d.content_id', 'left');
        $this->db->join('files e', 'd.file_id = e.id');
        //$this->db->join('video_rating f', 'a.id = f.content_id', 'left');
        if (isset($data['content_title']) && $data['content_title'] != '') {
            $this->db->like('title', trim($data['content_title']));
        }
        if (isset($data['category']) && $data['category'] != '') {
            $this->db->where('a.category', $data['category']);
        }
        if (isset($_GET['filter']) && $_GET['filter'] != '') {
            $this->db->where('a.type', $_GET['filter']);
        }
        if ((isset($data['datepickerstart']) && $data['datepickerstart'] != '') && (isset($data['datepickerend']) && $data['datepickerend'] != '')) {
            $date = str_replace('/', '-', $data['datepickerstart']);
            $datestart = date('y-m-d', strtotime($date));
            $date = str_replace('/', '-', $data['datepickerend']);
            $dateend = date('y-m-d', strtotime($date));
            $dateTimeStart = $datestart . $timeStart;
            $dateTimeEnd = $dateend . $timeEnd;
            $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
        } else {
            if (isset($data['datepickerstart']) && $data['datepickerstart'] != '') {
                $date = str_replace('/', '-', $data['datepickerstart']);
                $datestart = date('y-m-d', strtotime($date));
                $dateTimeStart = $datestart . $timeStart;
                $dateTimeEnd = $datestart . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
            if (isset($data['datepickerend']) && $data['datepickerend'] != '') {
                $date = str_replace('/', '-', $data['datepickerend']);
                $dateend = date('y-m-d', strtotime($date));
                $dateTimeStart = $dateend . $timeStart;
                $dateTimeEnd = $dateend . $timeEnd;
                $this->db->where("a.created BETWEEN '$dateTimeStart' and '$dateTimeEnd'", NULL, FALSE);
            }
        }

        $this->db->group_by('a.id');
        $this->db->order_by($sort, $sort_by);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $data = $query->result();
        
        return $data;
    }
    
    /*
     * Function to get all categories
     */
    function get_categories($relation = false) {
        $this->db->select('child.id,child.category,child.parent_id,parent.category as parent');
        $this->db->from('categories child');
        $this->db->join('categories parent', 'child.parent_id = parent.id', 'left');
        $this->db->where('child.status', 1);
        $this->db->order_by('child.category', 'asc');
        $query = $this->db->get();
        $result = $query->result();
        
        $category = array();
        if($relation === false){
            foreach($result as $key=>$val){
                $category[$val->id] = ucfirst(strtolower($val->category));
            }
        }else{
            foreach($result as $key=>$val){
                if($val->parent_id > 0){
                    $category[$val->parent][$val->id] = ucfirst(strtolower($val->category));
                }else{
                    $category[$val->id] = ucfirst(strtolower($val->category));
                }
            }
        }
        return $category;
    }
    

}
