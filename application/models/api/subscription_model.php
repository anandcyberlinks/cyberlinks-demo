<?php

class Subscription_model extends CI_Model{
       
    function __construct()
    {
       parent::__construct();	
       $this->load->database();
       $parent=array();
    }
    
    function getlist($data)
    {
        $this->db->select('c.title,d.name as subscription_name,d.days,p.id as subscription_id,p.content_id,p.content_type as type,p.price as amount,f.name as thumbnail_path');
        $this->db->from('duration d');
        $this->db->join('price p','d.id=p.duration_id','INNER');
	$this->db->join('contents c','p.content_id=c.id','INNER');
	$this->db->join('video_thumbnails vt','vt.content_id=p.content_id' ,'LEFT');
	$this->db->join('files f','vt.file_id=f.id and vt.default_thumbnail=1' ,'LEFT');
        $this->db->where('d.uid',$this->owner_id);
        $this->db->where('p.content_id',$data['content_id']);        
        $query = $this->db->get();
       //echo $this->db->last_query();
       return $query->result();
    }
    
    function validate_subscription($data)
    {
	$this->db->select('o.id');
	$this->db->from('price p');
	$this->db->join('order_details od','od.subscription_id=p.id','inner');
	$this->db->join('order o','od.order_id=o.id','inner');
	$this->db->where('o.user_id',$data['user_id']);
	$this->db->where('p.content_id',$data['content_id']);
	$this->db->where('CURDATE() BETWEEN DATE_FORMAT(od.start_date,"%Y-%m-%d") AND DATE_FORMAT(od.end_date,"%Y-%m-%d")');
	$this->db->where('o.status','completed');
	$query=$this->db->get();
	//echo $this->db->last_query();	
	$result = $query->row();
        if($result)
            return $result->id;
        else
            return 0;
    }
     
     function available_packages($data)
     {
	$this->db->select('c.title,pk.name as package_name,pk.package_type,pkv.package_id,pkv.content_id,f.name as thumbnail_path');	
	$this->db->from('package pk');	
	$this->db->join('package_video pkv','pkv.package_id=pk.id' ,'inner');
	$this->db->join('contents c','pkv.content_id=c.id','INNER');
	$this->db->join('video_thumbnails vt','vt.content_id=pkv.content_id' ,'LEFT');
	$this->db->join('files f','vt.file_id=f.id and vt.default_thumbnail=1' ,'Left');	
	$this->db->where('pk.uid',$this->owner_id);
	$this->db->where('pk.status',1);
	$this->db->where('pkv.package_id IN (select package_id  as package_id from package_video where content_id='.$data['content_id'].' )',NULL,false);
	$query = $this->db->get();
	//echo $this->db->last_query();
	return $query->result();	
     }
     
     function susbcription_packages($package_id)
     {
	 $this->db->select('d.name as subscription_name,d.days,p.duration_id as subscription_id,p.content_id,p.content_type as type,p.price as amount');
        $this->db->from('duration d');
        $this->db->join('price p','d.id=p.duration_id','inner');
	$this->db->join('package pk','p.content_id=pk.id' ,'inner');
        $this->db->where('d.uid',$this->owner_id);
	$this->db->where('pk.status',1);
        $this->db->where('pk.id',$package_id);        
        $query = $this->db->get();
       //echo $this->db->last_query();
       return $query->result();
     }
     
     function get_video_susbscriptions($data=array())
     {
	if($data['device'] !=''){
	 $this->db->where('fl.flavor_name',$data['device']);
	}
	
	$this->db->select('c.title,c.description,c.type,c.content_type,fl.device_name,fv.path as video_path,ct.id as category_id,ct.category as category_name,v.duration,v.views as total_view, d.name as subscription_name, d.days, p.id as subscription_id, p.content_id, p.content_type as type, p.price as amount, f.name as thumbnail_path');
        $this->db->from('order o');
	$this->db->join('order_details od','o.id=od.order_id ','INNER');	
        $this->db->join('price p','p.id=od.subscription_id','INNER');
	$this->db->join('duration d','d.id=p.duration_id','INNER');
	$this->db->join('contents c','p.content_id=c.id','INNER');
	$this->db->join('categories ct','c.category=ct.id','INNER');
	$this->db->join('videos v','v.content_id=c.id' ,'LEFT');
	$this->db->join('video_thumbnails vt','vt.content_id=c.id' ,'LEFT');
	$this->db->join('files f','vt.file_id=f.id and vt.default_thumbnail=1' ,'LEFT');
	$this->db->join('flavored_video fv','fv.content_id = c.id' ,'LEFT');
	$this->db->join('video_flavors vf','vf.id = fv.flavor_id' ,'LEFT');
	$this->db->join('flavors fl','vf.flavor_id = fl.id' ,'LEFT');
        $this->db->where('d.uid',$this->owner_id);
        $this->db->where('o.user_id',$data['user_id']);
        $query = $this->db->get();
	//echo '<pre>'. $this->db->last_query();
	return $query->result();
     }
     
     function get_package_susbscriptions($data=array())
     {
	if($data['device'] !=''){
	 $this->db->where('fl.flavor_name',$data['device']);
	}
	
	$this->db->select('pk.name as package_name,pk.id as package_id,c.title,c.description,c.type,c.content_type,fl.device_name,fv.path as video_path,ct.id as category_id,ct.category as category_name,v.duration,v.views as total_view, d.name as subscription_name, d.days, p.id as subscription_id, c.id as content_id, p.content_type as type, p.price as amount, f.name as thumbnail_path');
        $this->db->from('order o');
	$this->db->join('order_details od','o.id=od.order_id ','INNER');	
        $this->db->join('price p','p.id=od.subscription_id','INNER');
	$this->db->join('duration d','d.id=p.duration_id','INNER');
	$this->db->join('package pk', 'p.content_id=pk.id','INNER');
	$this->db->join('package_video pkv','pk.id=pkv.package_id','INNER');
	$this->db->join('contents c','pkv.content_id=c.id','INNER');
	$this->db->join('categories ct','c.category=ct.id','INNER');
	$this->db->join('videos v','v.content_id=c.id' ,'LEFT');
	$this->db->join('video_thumbnails vt','vt.content_id=c.id' ,'LEFT');
	$this->db->join('files f','vt.file_id=f.id and vt.default_thumbnail=1' ,'LEFT');
	$this->db->join('flavored_video fv','fv.content_id = c.id' ,'LEFT');
	$this->db->join('video_flavors vf','vf.id = fv.flavor_id' ,'LEFT');
	$this->db->join('flavors fl','vf.flavor_id = fl.id' ,'LEFT');
        $this->db->where('d.uid',$this->owner_id);	
        $this->db->where('o.user_id',$data['user_id']);
        $query = $this->db->get();
	//echo '<pre>'. $this->db->last_query();
	return $query->result();
     }

    function getSubsIdArr($invoice)
    {
	$this->db->select('order_details.subscription_id as subscription_id, order_details.order_id as order_id, duration.days as validtime');
        $this->db->from('order_details');
        $this->db->join('order','order_details.order_id = order.id', 'left');
	$this->db->join('price','order_details.subscription_id = price.id', 'left');
	$this->db->join('duration','price.duration_id = duration.id', 'left');
        $this->db->where('order.invoice', $invoice);
        $query = $this->db->get();	
        $result = $query->result();
	return $result;
    }
    
    function getorder($data)
    {
	$this->db->select('order_info as cart,user_id');
	$this->db->from('order');
	$this->db->where('invoice',base64_decode($data['o']));
	$this->db->limit(1);
	$query = $this->db->get();
	//echo $this->db->last_query();die;
	return $query->row();
    }
    
    function save_order($data){       
        $this->db->set($data);
        $this->db->set('created','NOW()',FALSE);
        $this->db->insert('order');
        return $this->db->insert_id();
    }
    
    function saveOrder($data)
    { 
	if(isset($data['txn_id'])){
	    $orderData = array(
		'trasaction_id'=>$data['txn_id'],
		'status'=>strtolower($data["payment_status"])
	    );            
	    $this->db->set('modified','NOW()',FALSE);
	    $this->db->where('invoice', base64_decode($data['invoice']));
	    $this->db->update('order', $orderData); 		
	    return base64_decode($data['invoice']);
	} else {
            //return 'ss';die;
	    $orderData = array(
		'user_id'=>$data['user_id'],
		'invoice'=>$data['invoice'],
		'total_amount'=>$data['total_amount'],
		'order_info' => serialize($data['cart']),
		'status'=>'pending'
	    );
	    $this->db->set($orderData);
	    $this->db->set('created','NOW()',FALSE);	    
	    $this->db->insert('order');
	    return $this->db->insert_id();  
	}
       // return $orderData;die;
    }    
    
    function saveOrderDetails($data)
    {
	if(isset($data['txn_id'])){
	    $subscriptionDataArr = $this->getSubsIdArr(base64_decode($data['invoice']));
	    $countData = count($subscriptionDataArr);
	    for($i=0; $i<$countData; $i++){
		$this->db->set('start_date','NOW()',FALSE);
		//echo $endDate = DATE_ADD('NOW()', 'INTERVAL '.$subscriptionDataArr[$i]->validtime. 'DAY');exit;
		$this->db->set('end_date','NOW() + INTERVAL '.$subscriptionDataArr[$i]->validtime. ' DAY',FALSE);
		$this->db->set('modified','NOW()',FALSE);
		$this->db->where('order_id', $subscriptionDataArr[$i]->order_id);
		$this->db->where('subscription_id', $subscriptionDataArr[$i]->subscription_id);
		$this->db->update('order_details');
	    }
	    return true ;
	} else {
	    $orderDetails = array(
		'order_id'=>$data['order_id'],
		'subscription_id'=>$data['subscription_id'],
		'amount'=>$data['amount']
		//'discount_amount'=>$data['discount_amount']
	    );
	    $this->db->set($orderDetails);
	    $this->db->set('created','NOW()',FALSE);	    
	    $this->db->insert('order_details');
	    return true ;  
	}
    }
    
    function updateOrderStatus($invoice_no, $status)
    {
	$this->db->set('status',$status);
	$this->db->set('modified','NOW()',FALSE);
	$this->db->where('invoice', $invoice_no);
	$this->db->update('order'); 		
	return true ;
    }
    
    function delete_order($id)
    {
        $this->db->where('order_id',$id);
        $this->db->delete('order_details');
        
        $this->db->delete('order');
    }    
}


?>