<?php
class Checkout extends My_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');        
		$this->load->config('credentials');	   
        $this->load->library('paypal_class');
		$this->load->library('Session');
        $per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
    }

    function index()
    {
        $token = @$_REQUEST['token'];
        $paymentMode = $this->config->item('PayPalMode');
        $p = new paypal_class(); // paypal class
        $p->admin_mail 	= $this->config->item('PayerEmailId'); // set notification email
        $p->currency 	= $this->config->item('currency'); // set notification email
       
        $action = @$_REQUEST["action"];
        //$invoice = date("His").rand(1234, 9632);
        $_REQUEST['cmd'] = '_cart';
        //$cart = json_decode(@$_REQUEST['cart'],true);        
	    if(isset($action)) {
		switch($action){
		    case "process": // case process insert the form data in DB and process to the paypal			    
			
			//if(count($cart)>0){
                            //$_REQUEST['invoice'] = $invoice;
                            $curl = curl_init();
                            // Set some options - we are passing in a useragent too here
                            curl_setopt_array($curl, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_URL => base_url().'api/subscription/order/token/'.$token,
                                CURLOPT_USERAGENT => 'Checkout',
                                CURLOPT_POST => 1,
                                CURLOPT_POSTFIELDS => $_REQUEST
                            ));  
                            // Send the request & save response to $resp
                            $resp = curl_exec($curl);
                          
                            // Close request to clear up some resources
                            curl_close($curl);
                            $result = json_decode($resp,true);
                       
                            if($result['output']==1){
                            $i=0;
			    //-- get result array--//
			    
			    $cart = json_decode($result['result']['cart'],true);
			    $user_id = $result['result']['user_id'];			    
			    $cart = array($cart);
                            //-- post form to paypal --//
                                foreach($cart as $row){
                                    $i++;
                                    //-- fields for paypal --//
                                     $p->add_field('item_name_'.$i, $row['subscription_name']);
                                     $p->add_field('item_number_'.$i, $row['subscription_id']);
                                     $p->add_field('amount_'.$i, $row['amount']);
                                     //$p->add_field('discount_amount_'.$i,  $_POST["discount_amount_".$i]);                         
                                 }
                                 
                                 $p->add_field('business', $this->config->item('merchantEmailId')); // Call the facilitator eaccount
                                 $p->add_field('cmd', $_REQUEST["cmd"]); // cmd should be _cart for cart checkout
                                 $p->add_field('upload', '1');
                                 $p->add_field('return', $this->config->item('PayPalReturnURL')); // return URL after the transaction got over
                                 $p->add_field('cancel_return', $this->config->item('PayPalCancelURL').'&invoiceno='.$_REQUEST["o"]); // cancel URL if the trasaction was cancelled during half of the transaction
                                 //$p->add_field('cancel_return', $this->config->item('PayPalCancelURL')); // cancel URL if the trasaction was cancelled during half of the transaction
                                 $p->add_field('notify_url', $this->config->item('PayPalIpnURL')); // Notify URL which received IPN (Instant Payment Notification)
                                 $p->add_field('currency_code', $this->config->item('PayPalCurrencyCode'));
                                 $p->add_field('invoice', $_REQUEST["o"]);
                                 //$p->dump_fields();die;
                                 $p->submit_paypal_post(); // POST it to paypal
                                
                            }else{
                                //echo $result['error'];
				//echo json_encode(array('code'=>0,'result'=>$result['error']));
				redirect(base_url() . 'checkout/callback?result=error');
                            }
                        //}
                            //$this->paypal_model->saveData($_POST);

			    //$p->dump_fields(); // Show the posted values for a reference, comment this line before app goes live
		    break;
		    
		    case "success": // success case to show the user payment got success
			//echo json_encode(array('code'=>1)); // 200 being the HTTP response code
			    //echo "<h1>Payment Transaction Done Successfully</h1>";
			    redirect(base_url() . 'checkout/callback?result=success');
		    break;
		    
		    case "cancel": // case cancel to show user the transaction was cancelled
				//echo 'in cancel block';echo "<br/>";
				//echo '<pre>';print_r($_POST);echo '</pre>';exit;
                                //$invoice_no = $_REQUEST['invoiceno'];
                                
                                //-- post cancel order---//
                                    $curl = curl_init();
                                    curl_setopt_array($curl,array( CURLOPT_RETURNTRANSFER => 1,
                                        CURLOPT_URL => base_url().'api/subscription/cancel',
                                        CURLOPT_USERAGENT => 'Cancel',
                                        CURLOPT_POST => 1,
                                        CURLOPT_POSTFIELDS => $_REQUEST
                                    ));  
                                //-----------------------//                               
				 // Send the request & save response to $resp
                                $resp = curl_exec($curl);
                               
                                 // Close request to clear up some resources
                                 curl_close($curl);
                                 $result = json_decode($resp,true);                                 
                                 if($result['output']==1){
				    redirect(base_url() . 'checkout/callback?result=cancel');
				  //  echo json_encode(array('code'=>0,'result'=>'Transaction Cancelled'));
                                    //echo "<h2>Transaction Cancelled</h2>";
                                 }else{
				    //echo json_encode(array('code'=>0,'result'=>$result['error']));
                                   // echo $result['error'];
				   redirect(base_url() . 'checkout/callback?result=cancel');
                                 }
		    break;
		    
		    case "ipn": // IPN case to receive payment information. this case will not displayed in browser. This is server to server communication. PayPal will send the transactions each and every details to this case in secured POST menthod by server to server. 
			    //$trasaction_id  = $_REQUEST["txn_id"];
			    //$payment_status = strtolower($_REQUEST["payment_status"]);
			   // $invoice		= $_REQUEST["invoice"];
			    if ($p->validate_ipn()){ // validate the IPN, do the others stuffs here as per your app logic				
                                //-- post paypal ipn ---//
                                    $curl = curl_init();
                                    curl_setopt_array($curl,array( CURLOPT_RETURNTRANSFER => 1,
                                        CURLOPT_URL => base_url().'api/subscription/ipn',
                                        CURLOPT_USERAGENT => 'Completed',
                                        CURLOPT_POST => 1,
                                        CURLOPT_POSTFIELDS => $_REQUEST
                                    ));
                                //-----------------------//                               
				 // Send the request & save response to $resp
                                $resp = curl_exec($curl);
                               
                                 // Close request to clear up some resources
                                 curl_close($curl);				 
				    $subject = 'Instant Payment Notification - Recieved Payment';                                    				    
				    $this->log('paypal_log', $resp.': '.$subject);
				    $p->send_report($subject); // Send the notification about the transaction
			    }else{
				    $subject = 'Instant Payment Notification - Payment Fail';                                    
				    $this->log('paypal_log', $resp.': '.$subject);
				    $p->send_report($subject); // failed notification				    
			    }
		    break;
		}
	    //$this->load->view('paypal');
	    } else {
		//$this->load->view('paypal');
	    }               
    }
    
    function callback()
    {
	if($_REQUEST['result']=='success')
	{
	    echo "<h1>Payment Transaction Done Successfully</h1>";
	}if($_REQUEST['result']=='cancel')
	{
	    echo "<h2>Transaction Cancelled</h2>";
	}if($_REQUEST['result']=='error')
	{
	    echo "<h2>Error in checkout</h2>";
	}
	die;
    }
}

?>