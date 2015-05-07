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
    

}
