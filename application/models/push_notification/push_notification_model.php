    <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Push_notification_model extends CI_Model {

   function __construct()
   {
       parent::__construct();
	   $this->load->helper('url');
   }
   
    function push_notification_data($post_data){
        $device_data = array();
        switch ($post_data['notification_type']) {
            case "broadcast":
               
                if(count($post_data['device_type'])==2)
                {
                    $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id";
                }else{
                    $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id"
                            . " WHERE b.device_type ='".$post_data['device_type'][0]."' ";
                }				
                $query = $this->db->query($sql);
                foreach($query->result() as $key => $val){
                    if($val->device_type=='ios'){
                        $device_data['ios'][] = $val->device_unique_id;
                    }else if($val->device_type=='android'){
                        $device_data['android'][] = $val->device_unique_id;
                    }
                }
                return $device_data;
                break;
            case "device_by_tag":
                $key_words_like = "";
                $i = 1;
                $key_words_like .= " (";
                foreach($post_data['keywords'] as $keyword){
                 $key_words_like .= "a.keywords like '%".$keyword."%'";
                 if(count($post_data['keywords'])!=$i)
                     $key_words_like .= " OR ";
                 
                 $i++;
                }
                $key_words_like .= " )";
                if(count($post_data['device_type'])==2)
                {
                    $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b "
                            . "on a.id = b.user_id WHERE ".$key_words_like;
                }else{
                    $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id"
                            . " WHERE b.device_type ='".$post_data['device_type'][0]."'  AND ".$key_words_like;
                }
                
                $query = $this->db->query($sql);
                foreach($query->result() as $key => $val){
                    if($val->device_type=='ios'){
                        $device_data['ios'][] = $val->device_unique_id;
                    }else if($val->device_type=='android'){
                        $device_data['android'][] = $val->device_unique_id;
                    }
                }
                return $device_data;
                break;
            case "one_device":
                
                if(count($post_data['device_type'])==2)
                {
                    $sql = 'SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id '
                        . ' WHERE b.device_unique_id = '.$post_data['device_id'];
                }else{
                    $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id "
                        . " WHERE b.device_unique_id = ".$post_data['device_id']." AND b.device_type='".$post_data['device_type'][0]."'";
                }
                
                $query = $this->db->query($sql);
                foreach($query->result() as $key => $val){
                    if($val->device_type=='ios'){
                        $device_data['ios'][] = $val->device_unique_id;
                    }else if($val->device_type=='android'){
                        $device_data['android'][] = $val->device_unique_id;
                    }
                }
                return $device_data;
                break;
            case "device_by_segment":
                echo "Your favorite color is green!";
                break;
            default:
                echo "Your favorite color is neither red, blue, or green!";
        }
		
    }
	
	function schedule_notification($data)
	{
	  $this->db->set($data);
	  $this->db->set('created', 'NOW()',false);
	  $this->db->insert('pushnotification_scheduler');
	  return $this->db->insert_id();
	}
	
	function save($data)
	{
	  $this->db->set($data);
	  $this->db->set('date_sent', 'NOW()',false);
	  $this->db->insert('pushnotification_history');
	 // echo $this->db->last_query();
	  return $this->db->insert_id();
	}
}