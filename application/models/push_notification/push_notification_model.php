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
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
                   // $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id";
                }else{
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
				  $this->db->where_in('b.device_type',array_filter($post_data['device_type']));
				  
                  //  $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id"
                   //         . " WHERE b.device_type ='".$post_data['device_type'][0]."' ";
                }
				$query = $this->db->get();
				
                //$query = $this->db->query($sql);
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
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
				  $this->db->where($key_words_like,"",false);
                    //$sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b "
                  //          . "on a.id = b.user_id WHERE ".$key_words_like;
                }else{
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
				  $this->db->where($key_words_like,"",false);
				  $this->db->where_in('b.device_type',array_filter($post_data['device_type']));
				  
                  //  $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id"
                    //        . " WHERE b.device_type ='".$post_data['device_type'][0]."'  AND ".$key_words_like;
                }
				$query = $this->db->get();
				
				// $query = $this->db->query($sql);
                //echo $sql;die;
               // $query = $this->db->query($sql);
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
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
				  $this->db->where_in('b.device_unique_id',array_filter($post_data['device_id']));				 
                }else{
				  $this->db->select('b.device_unique_id,b.device_type');
				  $this->db->from('customers a');
				  $this->db->join('customer_device b','a.id = b.user_id');
				  $this->db->where_in('b.device_unique_id',array_filter($post_data['device_id']));
				  $this->db->where_in('b.device_type',array_filter($post_data['device_type']));				  
                  //  $sql = "SELECT b.device_unique_id,b.device_type FROM `customers` a JOIN `customer_device` b on a.id = b.user_id "
                 //       . " WHERE b.device_unique_id = ".$post_data['device_id']." AND b.device_type='".$post_data['device_type'][0]."'";
                }
				$query = $this->db->get();
				//echo $this->db->last_query();die;
               // echo $sql;die;
                //$query = $this->db->query($sql);
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
        
        function get_total_push_notifications($record_time,$specific_date){
            $where = '';
            if($record_time=='today'){
                $where = " WHERE date(`date_sent`) = CURDATE()";
            }else if($record_time=='date'){
                $where = " WHERE date(`date_sent`) = '".$specific_date."' ";
            }else if($record_time=='all'){
                $where = '';
            }
            
            $sql =  "SELECT sum(`sent_count`) as total_sent FROM `pushnotification_history` ".$where;
            $query = $this->db->query($sql);
            return $query->row()->total_sent;
        }
        
        function get_direct_open_notifications($record_time,$specific_date){
            $where = '';
            if($record_time=='today'){
                $where = " WHERE date(`open_datetime`) = CURDATE()";
            }else if($record_time=='date'){
                $where = " WHERE date(`open_datetime`) = '".$specific_date."' ";
            }else if($record_time=='all'){
                $where = '';
            }
            
            $sql =  "SELECT count(`id`) as total_open FROM `push_notification_open` ".$where;
            $query = $this->db->query($sql);
            return $query->row()->total_open;
        }
        
        function total_open_over_time($record_time,$specific_date){
            $where = '';
            if($record_time=='today'){
                $where = " WHERE date(`open_datetime`) = CURDATE()";
            }else if($record_time=='date'){
                $where = " WHERE date(`open_datetime`) = '".$specific_date."' ";
            }else if($record_time=='all'){
                $where = '';
            }
            
            $sql = "SELECT `open_datetime`,DATE_FORMAT(`open_datetime`,'%H') open_time, count(*) total_push_open FROM push_notification_open ";
            $sql .= $where;
            $sql .= " GROUP BY hour( `open_datetime` )";
            
            $query = $this->db->query($sql);
            return $query->result();
        }
        
        function get_push_count()
        {
            $this->db->order_by('id', 'desc');
            $query = $this->db->get('pushnotification_history');
            return count($query->result());
        }
        
        function get_push_history($limit,$start)
        {
            $this->db->order_by('id', 'desc');
            $this->db->limit($limit, $start);
            $query = $this->db->get('pushnotification_history');
            return $query->result();
        }
        
        function get_push_history_detail($push_id){
            $this->db->where('id',$push_id);
            $query = $this->db->get('pushnotification_history');
            return $query->row();
        }
}