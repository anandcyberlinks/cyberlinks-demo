<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal_model extends CI_Model {
    
    function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	
    function saveOrder($data)
    {
	if(isset($data['txn_id'])){
	    $orderData = array(
		'trasaction_id'=>$data['txn_id'],
		'status'=>strtolower($data["payment_status"])
	    );
	    $this->db->set('modified','NOW()',FALSE);
	    $this->db->where('invoice', $data['invoice']);
	    $this->db->update('order', $orderData); 		
	    return $data['order_id'];
	} else {
	    $orderData = array(
		'user_id'=>$data['user_id'],
		'invoice'=>$data['invoice'],
		'total_amount'=>$data['total_amount'],
		'total_discount'=>$data['discount_amount'],
		'status'=>'pending'
	    );
	    $this->db->set($orderData);
	    $this->db->set('created','NOW()',FALSE);	    
	    $this->db->insert('order');
	    $order_id = $this->db->insert_id();
	    return $order_id ;  
	}
    }
    
    function saveOrderDetails($data)
    {
	if(isset($data['txn_id'])){
	    echo "order details cmplete time"; exit;

	    $this->db->set('start_date','NOW()',FALSE);
	    $this->db->set('end_date','NOW()',FALSE);
	    $this->db->set('modified','NOW()',FALSE);
	    $this->db->where('id', $data['order_id']);
	    $this->db->update('order_details', $orderData); 		
	    return true ;
	} else {
	    $orderDetails = array(
		'order_id'=>$data['order_id'],
		'subscription_id'=>$data['subscription_id'],
		'amount'=>$data['amount'],
		'discount_amount'=>$data['discount_amount']
	    );
	    $this->db->set($orderDetails);
	    $this->db->set('created','NOW()',FALSE);	    
	    $this->db->insert('order_details');
	    return true ;  
	}
  
    }
	
    function updateOrderStatus($order_id, $status)
    {
	$this->db->set('status',$status);
	$this->db->set('modified','NOW()',FALSE);
	$this->db->where('id', $order_id);
	$this->db->update('order', $orderData); 		
	return true ;
    }
}