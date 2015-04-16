<?php

class Ads_analytics_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function save($post, $where = null) {
        $this->db->set($post);
        if (@$post['pause'] || @$post['complete']) {
            $this->db->set('modified', 'NOW()', false);
            $this->db->update('ads_analytics', $post, $where);
            return $this->db->affected_rows();
        } else {
            $this->db->set('created', 'NOW()', false);
            $this->db->insert('ads_analytics');
            return $this->db->insert_id();
        }
    }

    public function getCountry() {
        $this->db->select('code,name');
        $this->db->from('countries');
        $this->db->order_by('name asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getReport($param = array(), $sort, $sort_by, $limit, $start) {
        //print_r($param); die;
        //--- search val --//
        if (@$param['search']) {
            if ($param['search']['platform'] != '') {
                $this->db->like('a.platform', $param['search']['platform']);
            }

            if ($param['search']['browser'] != '') {
                $this->db->like('a.browser', $param['search']['browser']);
            }

            if ($param['search']['country'] != '') {
                $this->db->like('a.country', $param['search']['country']);
            }

            if ($param['search']['startdate'] != '' && $param['search']['enddate'] == '') {
                $this->db->where('DATE_FORMAT(a.created,"%Y-%m-%d") >', date('Y-m-d', strtotime($param['search']['startdate'])));
            }

            if ($param['date_from'] && $param['date_to']) {
                $startdate = $param['date_from'];
                $enddate = $param['date_to'];
                $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
            }
        }
        if ($param['date_from'] && $param['date_to']) {
            $startdate = $param['date_from'];
            $enddate = $param['date_to'];
            $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
        }

        $group = '';
        switch ($param['type']) {

            case 'revenue':
                $select = 'SUM(a.revenue) AS revenue,             
            count(a.id) as impression';
                $this->db->group_by('a.broadcaster');
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }


                break;
            case 'content':
                $select = 'c.ad_title,a.platform,a.browser,a.created,a.country,a.city,a.ads_id,concat(u.first_name," ",u.last_name) as content_provider,count(ads_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
                // $group = 'a.ads_id';
                // $group = 'a.id';

                if ($param['top'] == 1) {  //-- top video --//
                    $this->db->group_by('a.ads_id');
                    $this->db->order_by('a.id desc');
                } else {
                    $this->db->group_by('a.ads_id');
                }

                //  $join = "users u";
                // $cond = "a.content_provider=u.id";
                $this->db->join('users u', "a.content_provider=u.id");
                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->select('CONCAT(cu.first_name," " ,cu.last_name) AS customer_name', false);
                    $this->db->where('a.user_id', $param['id']);
                    $this->db->join('customers cu', 'cu.id=a.user_id');
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        // $join = "users u";
                        // $cond = "a.content_provider=u.id";
                    }
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                break;
            case 'useragent':
                $select = 'a.platform, a.browser, count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                //$group = 'a.platform, a.browser';            

                if ($param['top'] == 1) {  //-- top video --//               
                    $this->db->order_by('count(a.id) desc');
                }
                if ($param['mode'] == 'os') {
                    $this->db->group_by('a.platform');
                } else if ($param['mode'] == 'browser') {
                    $this->db->group_by('a.browser');
                } else {
                    $this->db->group_by('a.platform, a.browser');
                }

                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }

                break;
            case 'summary':
                $select = "count(distinct a.user_id) unique_hits,count(a.id) as total_hits,
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS total_partial,
            SUM(IF(a.complete=1,1,0)) as total_complete,
            SUM(IF(a.replay=1,1,0)) as total_replay, sum( a.watched_time ) as total_watched_time";

                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->where('a.user_id', $param['id']);
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['name'] != '') {
                        $this->db->like('cu.first_name', $param['search']['name']);
                        $join = "customers cu";
                        $cond = "a.user_id=cu.id";
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        $join = "users u";
                        $cond = "a.content_provider=u.id";
                    }
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }

                break;
            case 'map':
                $select = 'a.country,a.country_code as code,a.city,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                //$group = 'a.country_code';
                $this->db->group_by('a.city');
                break;
            case 'country':
                $select = 'a.country_code as code, a.state, a.country,a.city,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.country_code';

                if ($param['top'] == 1) {  //-- top video --//                
                    $this->db->order_by('count(a.id) desc');
                }
                if ($param['code'] != '') {
                    $this->db->where('a.country_code', $param['code']);
                }
                $this->db->group_by('a.state');
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }

                break;
            case 'region':
                $select = 'a.country_code as code,a.country, a.state,a.city,a.postal_code,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.country_code';
                $this->db->where('country_code', $param['code']);
                $this->db->group_by('a.city');
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                break;
            case 'city':
                $select = 'a.country_code as code,a.country, a.state,a.city,a.postal_code,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.country_code';
                $this->db->group_by('a.city');
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                break;
            case 'content_provider':
                $select = 'concat(u.first_name," ",u.last_name) as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.content_provider';
                $this->db->group_by('a.content_provider');
                $this->db->join('users u', 'a.content_provider=u.id');
                //$join = "users u";
                //$cond = "a.content_provider=u.id";    
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                break;
            case 'user':
                $select = 'a.ip,a.browser,a.platform,a.created,cu.id,cu.first_name as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                //$group = 'u.id';
                $this->db->group_by('cu.id');
                $this->db->join('customers cu', 'a.user_id=cu.id');
                //$join = "customers u";
                //$cond = "a.user_id=u.id";
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['name'] != '') {
                        $this->db->like('u.first_name', $param['search']['name']);
                    }
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                break;

            case 'usercontent':
                $select = 'c.ad_title,a.platform,a.browser,a.created,a.country,a.city,a.ads_id,concat(u.first_name," ",u.last_name) as content_provider,count(ads_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
                // $group = 'a.ads_id';
                $this->db->group_by('a.id');
                $this->db->join('users u', 'a.content_provider=u.id');

                // $join = "users u";
                // $cond = "a.content_provider=u.id";
                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->where('a.user_id', $param['id']);
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        //  $join = "users u";
                        //   $cond = "a.content_provider=u.id";
                    }
                }
                if ($param['date_from'] && $param['date_to']) {
                    $startdate = $param['date_from'];
                    $enddate = $param['date_to'];
                    $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                break;
        }

        if ($param['l'] > 0) {
            $this->db->limit($param['l']);
        }
        if ($join != '') {

            $this->db->join($join, $cond);
        }
        if ($type != 'content_provider') {
            // $this->db->where('a.content_provider',$this->uid);
        }

        $this->db->select($select, false);
        $this->db->from('ads_analytics a');



        if ($param['type'] == 'revenue') {
            if ($this->user == 'superadmin') {
                // Searching by content Provider i.e broadcaster.
                $this->db->where("a.broadcaster", $_POST['searchbyCP']);
            } else {
                $this->db->where("a.broadcaster", $this->uid);
            }
        } else {
            $this->db->where("a.content_provider", $this->uid);
        }
        $this->db->join('ads c', 'a.ads_id=c.id');

        //$this->db->group_by($group);

        if (!isset($param['export']) && isset($limit) && isset($start)) {
            $this->db->limit($limit, $start);
        }
        $query = $this->db->get();
        // echo '<br>'.$this->db->last_query();
        return $query->result();
    }

    public function getReportCounts($param = array(), $sort, $sort_by) {

        //--- search val --//
        if (@$param['search']) {
            if ($param['search']['platform'] != '') {
                $this->db->like('a.platform', $param['search']['platform']);
            }

            if ($param['search']['browser'] != '') {
                $this->db->like('a.browser', $param['search']['browser']);
            }

            if ($param['search']['country'] != '') {
                $this->db->like('a.country', $param['search']['country']);
            }

            if ($param['search']['startdate'] != '' && $param['search']['enddate'] == '') {
                $this->db->where('DATE_FORMAT(a.created,"%Y-%m-%d") >', date('Y-m-d', strtotime($param['search']['startdate'])));
            }

            if ($param['date_from'] && $param['date_to']) {
                $startdate = $param['date_from'];
                $enddate = $param['date_to'];
                $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
            }
        }

        $group = '';
        switch ($param['type']) {

            case 'content':
                $select = 'c.ad_title,a.platform,a.browser,a.created,a.country,a.city,a.ads_id,concat(u.first_name," ",u.last_name) as content_provider,count(ads_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
                // $group = 'a.ads_id';
                // $group = 'a.id';

                if ($param['top'] == 1) {  //-- top video --//
                    $this->db->group_by('a.ads_id');
                    $this->db->order_by('count(ads_id) desc');
                } else {
                    $this->db->group_by('a.id');
                }

                //  $join = "users u";
                // $cond = "a.content_provider=u.id";
                $this->db->join('users u', "a.content_provider=u.id");
                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->select('CONCAT(cu.first_name," " ,cu.last_name) AS customer_name', false);
                    $this->db->where('a.user_id', $param['id']);
                    $this->db->join('customers cu', 'cu.id=a.user_id');
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        // $join = "users u";
                        // $cond = "a.content_provider=u.id";
                    }
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                break;
            case 'useragent':
                die;
                //$select = 'a.platform, a.browser, count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                //$group = 'a.platform, a.browser';            

                if ($param['top'] == 1) {  //-- top video --//               
                    $this->db->order_by('count(a.id) desc');
                }
                $this->db->group_by('a.platform, a.browser');

                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }

                break;
            case 'summary':
                $select = "count(distinct a.user_id) unique_hits,count(a.id) as total_hits,
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS total_partial,
            SUM(IF(a.complete=1,1,0)) as total_complete,
            SUM(IF(a.replay=1,1,0)) as total_replay, sum( a.watched_time ) as total_watched_time";

                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->where('a.user_id', $param['id']);
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['name'] != '') {
                        $this->db->like('cu.first_name', $param['search']['name']);
                        $join = "customers cu";
                        $cond = "a.user_id=cu.id";
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        $join = "users u";
                        $cond = "a.content_provider=u.id";
                    }
                }

                break;
            case 'map':
                $select = 'a.country,a.country_code as code,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                //$group = 'a.country_code';
                $this->db->group_by('a.country_code');
                break;
            case 'country':
                $select = 'a.country_code as code,a.country,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.country_code';

                if ($param['top'] == 1) {  //-- top video --//                
                    $this->db->order_by('count(a.id) desc');
                }
                if ($param['code'] != '') {
                    $this->db->where('a.country_code', $param['code']);
                }
                $this->db->group_by('a.country_code');

                break;
            case 'region':
                $select = 'a.country_code as code,a.country, a.state,a.city,a.postal_code,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.country_code';
                $this->db->where('country_code', $param['code']);
                $this->db->group_by('a.state');
                break;
            case 'content_provider':
                $select = 'concat(u.first_name," ",u.last_name) as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                // $group = 'a.content_provider';
                $this->db->group_by('a.content_provider');
                $this->db->join('users u', 'a.content_provider=u.id');
                //$join = "users u";
                //$cond = "a.content_provider=u.id";          
                break;
            case 'user':
                $select = 'cu.id,concat(cu.first_name," ",cu.last_name) as name,count( a.id ) as total_hits , sum( a.watched_time ) as total_watched_time';
                //$group = 'u.id';
                $this->db->group_by('cu.id');
                $this->db->join('customers cu', 'a.user_id=cu.id');
                //$join = "customers u";
                //$cond = "a.user_id=u.id";
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['name'] != '') {
                        $this->db->like('u.first_name', $param['search']['name']);
                    }
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                break;

            case 'usercontent':
                $select = 'c.ad_title,a.platform,a.browser,a.created,a.country,a.city,a.ads_id,concat(u.first_name," ",u.last_name) as content_provider,count(ads_id) as total_hits,sum(watched_time) as total_watched_time,           
            SUM(IF( a.complete =1, 1, 0 )) AS complete, 
            SUM(IF( a.complete =0 && a.skip =1, 1, 0 )) AS partial, 
            SUM(IF( a.replay =1, 1, 0) ) AS replay ';
                // $group = 'a.ads_id';
                $this->db->group_by('a.id');
                $this->db->join('users u', 'a.content_provider=u.id');

                // $join = "users u";
                // $cond = "a.content_provider=u.id";
                //-- user contents --//
                if ($param['id'] > 0) {
                    $this->db->where('a.user_id', $param['id']);
                }
                //--- search val --//
                if (@$param['search']) {
                    if ($param['search']['title'] != '') {
                        $this->db->like('c.ad_title', $param['search']['title']);
                    }

                    if ($param['search']['contentprovider'] != '') {
                        $this->db->where('u.id', $param['search']['contentprovider']);
                        //  $join = "users u";
                        //   $cond = "a.content_provider=u.id";
                    }
                }
                if ($sort) {
                    $this->db->order_by($sort, $sort_by);
                }
                break;
        }

        if ($param['l'] > 0) {
            $this->db->limit($param['l']);
        }
        if ($join != '') {

            $this->db->join($join, $cond);
        }
        if ($type != 'content_provider') {
            // $this->db->where('a.content_provider',$this->uid);
        }

        $this->db->select($select, false);
        $this->db->from('ads_analytics a');
        $this->db->where("a.content_provider", $this->uid);
        if ($param['date_from'] && $param['date_to']) {
            $startdate = $param['date_from'];
            $enddate = $param['date_to'];
            $this->db->where("DATE_FORMAT(a.created,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate'");
        }
        $this->db->join('ads c', 'a.ads_id=c.id');

        //$this->db->group_by($group);
        $query = $this->db->get();
        //echo '<br>'.$this->db->last_query();
        $result = $query->result();
        return count($result);
    }

    function getContentProvider() {
        $this->db->select('id,concat(first_name," ",last_name) as name', false);
        $this->db->from('users');
        $this->db->where('status', 'active');
        $this->db->where('role_id', 24);
        $query = $this->db->get();
        return $query->result();
    }

    function getDailyReport($daterange) {
        $fieldsArr = array();
        for ($i = 0; $i < count($daterange); $i++) {
            $between = sprintf("a.created between '%s' and '%s'", date('Y-m-d 00:00:00', strtotime($daterange[$i])), date('Y-m-d 23:59:59', strtotime($daterange[$i])));
            $alias = date("M d", strtotime($daterange[$i]));
            $fieldsArr[] = "SUM(IF($between,1,0)) as '$alias'";
        }

        $fields = implode(',', $fieldsArr);
        $this->db->select("$fields", false);
        $this->db->from('ads_analytics a');
        $query = $this->db->get();
        // echo $this->db->last_query();
        $result = $query->result();
        $rst['color'] = $this->randColor(1);
        foreach ($result[0] as $key => $val) {
            $rst['data'][] = array('y' => $key, 'value' => $val);
        }
        //print_r($rst);
        return $rst;
    }

    function randColor($numColors) {
        $chars = "ABCDEF0123456789";
        $size = strlen($chars);
        $str = array();
        for ($i = 0; $i < $numColors; $i++) {
            $tmp = '#';
            for ($j = 0; $j < 6; $j++) {
                $tmp .= $chars[rand(0, $size - 1)];
            }
            $str[$i] = $tmp;
        }
        return $str;
    }

    function getContentKeywords($content_id) {
        $sql = "select b.name from `content_keywords` a join keywords b on a.keyword_id = b.id where a.ads_id = " . $content_id;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return null;
    }

    function saveUserContentKeywords($user_id, $content_ids = array()) {

        $contents = array();
        $sql = "SELECT * FROM `user_content_keywords` WHERE `user_id` =" . $user_id;
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            // Insert here the user content tage
            $contents['user_id'] = $user_id;
            $contents['keywords'] = serialize($content_ids);
            $this->db->set($contents);
            $this->db->set('created', 'NOW()', FALSE);
            $this->db->set('modified', 'NOW()', FALSE);
            $this->db->insert('user_content_keywords');
        } else {
            $update = 0;
            // Update the existing user tags
            $result = $query->row_array();

            $user_keywords = unserialize($result['keywords']);

            $user_keywords_new = array();
            foreach ($user_keywords as $val) {
                array_push($user_keywords_new, $val['name']);
            }

            foreach ($content_ids as $row) {
                //echo '<pre>'; print_r($user_keywords);
                if (in_array($row['name'], $user_keywords_new)) {
                    //echo "I Got ".$row['name'].'<br/>';
                } else {
                    $update = 1;
                    $user_keywords[]['name'] = $row['name'];
                }
            }

            if ($update == 1) {
                $this->db->set('keywords', serialize($user_keywords));
                $this->db->set('modified', 'NOW()', FALSE);
                $this->db->where('user_id', $user_id);
                $this->db->update('user_content_keywords');
            }
        }
    }

    function getstitchingReport($limit, $param) {
        //print_r($param); die;
        $switch_db = $this->load->database('stitch_report', TRUE);
        $sql = "SELECT id ,adname as Commercial,channel, TIMESTAMPDIFF(SECOND,adstart,adend) AS Duration,GREATEST(adstartusercount,adendadusercount) As UserCount,adstart As StartTime
        FROM adhistory ";
        if ($param['date_from'] && $param['date_to']) {
            $startdate = $param['date_from'];
            $enddate = $param['date_to'];
            $sql .= "where adstart BETWEEN '$startdate 00:00:00' AND '$enddate 23:59:59' ";
        }
        $sql .= "LIMIT " . $limit;
        $query = $switch_db->query($sql);
        //$switch_db->query();
        $result = $query->result();
        $this->load->database('default', TRUE);

        $modified_result = $this->getAttributesOfAds($result);
        return $modified_result;
    }

    function getStichingReportCounts() {
        $switch_db = $this->load->database('stitch_report', TRUE);
        $sql = "SELECT count(id) as total_count  FROM adhistory";
        $query = $switch_db->query($sql);

        $total_count = $query->row()->total_count;
        $this->load->database('default', TRUE);
        return $total_count;
    }

    function getAllStichingReports($limit, $start, $export = false) {
        $switch_db = $this->load->database('stitch_report', TRUE);
        $switch_db->select('id ,adname as Commercial,channel, TIMESTAMPDIFF(SECOND,adstart,adend) AS Duration,GREATEST(adstartusercount,adendadusercount) As UserCount,adstart As StartTime', false);
        $switch_db->from('adhistory');
        $switch_db->order_by("StartTime", "DESC");
        if ($export == false) {
            $switch_db->limit($limit, $start);
        }
        $query = $switch_db->get();
        $result = $query->result();
        $this->load->database('default', TRUE);

        $modified_result = $this->getAttributesOfAds($result);
        return $modified_result;
    }

    function getAttributesOfAds($result = array()) {
        if (count($result) > 0) {
            foreach ($result as $key => $val) {

                $val->Commercial = substr($val->Commercial, 12);

                $string = str_replace("_3g", "", $val->Commercial);


                $sql1 = "SELECT a.`id` AS ads_id,a.`ad_title` FROM `files` b join `ads` a on b.id=a.file_id "
                        . "WHERE b.name = '" . $string . "' LIMIT 1";
                $query1 = $this->db->query($sql1);
                if ($query1->num_rows() > 0) {
                    $row = $query1->row();
                    $val->ads_id = $row->ads_id;
                    $val->ad_title = $row->ad_title;
                }
            }
        }
        return $result;
    }

}

?>