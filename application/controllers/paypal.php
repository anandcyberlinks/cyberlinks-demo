<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors','On');
class Paypal extends CI_Controller {

        
        function __construct() {
            parent::__construct();
	    $this->load->helper('url');
	    $this->load->config('credentials');	   
            $this->load->library('paypal_class');
	    $this->load->model('paypal_model');
        }

        
	public function index()
	{
		
	    $paymentMode = $this->config->item('PayPalMode');
            $p = new paypal_class(); // paypal class
            $p->admin_mail 	= $this->config->item('PayerEmailId'); // set notification email
            $action 		= @$_REQUEST["action"];
	    if(isset($action)) {
		switch($action){
		    case "process": // case process insert the form data in DB and process to the paypal
			    //mysql_query("INSERT INTO `purchases` (`invoice`, `product_id`, `product_name`, `product_quantity`, `product_amount`, `payer_fname`, `payer_lname`, `payer_address`, `payer_city`, `payer_state`, `payer_zip`, `payer_country`, `payer_email`, `payment_status`, `posted_date`) VALUES ('".$_POST["invoice"]."', '".$_POST["product_id"]."', '".$_POST["product_name"]."', '".$_POST["product_quantity"]."', '".$_POST["product_amount"]."', '".$_POST["payer_fname"]."', '".$_POST["payer_lname"]."', '".$_POST["payer_address"]."', '".$_POST["payer_city"]."', '".$_POST["payer_state"]."', '".$_POST["payer_zip"]."', '".$_POST["payer_country"]."', '".$_POST["payer_email"]."', 'pending', NOW())");
			    $order_id = $this->paypal_model->saveOrder($_POST); 
			    $totalItems = $_REQUEST["custom"];
			    if($order_id > 0){
					$_POST['order_id'] = $order_id;
					for($i=1; $i<=$totalItems; $i++){
						$p->add_field('item_name_'.$i, $_POST["item_name_".$i]);
						$p->add_field('item_number_'.$i, $_POST["item_number_".$i]);
						$p->add_field('amount_'.$i, $_POST["amount_".$i]);
						$p->add_field('discount_amount_'.$i,  $_POST["discount_amount_".$i]);
						$_POST['subscription_id'] =  $_POST["item_number_".$i];
						$_POST['amount'] =  $_POST["amount_".$i];
						$_POST['discount_amount'] =  $_POST["discount_amount_".$i];
						$this->paypal_model->saveOrderDetails($_POST);
					}
					
					$p->add_field('business', $this->config->item('merchantEmailId')); // Call the facilitator eaccount
					$p->add_field('cmd', $_POST["cmd"]); // cmd should be _cart for cart checkout
					$p->add_field('upload', '1');
					$p->add_field('return', $this->config->item('PayPalReturnURL')); // return URL after the transaction got over
					$p->add_field('cancel_return', $this->config->item('PayPalCancelURL')); // cancel URL if the trasaction was cancelled during half of the transaction
					$p->add_field('notify_url', $this->config->item('PayPalIpnURL')); // Notify URL which received IPN (Instant Payment Notification)
					$p->add_field('currency_code', $this->config->item('PayPalCurrencyCode'));
					$p->add_field('invoice', $_POST["invoice"]);
					$p->submit_paypal_post(); // POST it to paypal

			    }
			    
			    //$this->paypal_model->saveData($_POST);

			    //$p->dump_fields(); // Show the posted values for a reference, comment this line before app goes live
		    break;
		    
		    case "success": // success case to show the user payment got success
				//$this->paypal_model->updateOrderStatus('');
			    echo "<h1>Payment Transaction Done Successfully</h1>";
		    break;
		    
		    case "cancel": // case cancel to show user the transaction was cancelled
				echo 'in cancel block';echo "<br/>";
				echo '<pre>';print_r($_POST);echo '</pre>';exit;
				//$this->paypal_model->updateOrderStatus('');
			    echo "<h1>Transaction Cancelled";
		    break;
		    
		    case "ipn": // IPN case to receive payment information. this case will not displayed in browser. This is server to server communication. PayPal will send the transactions each and every details to this case in secured POST menthod by server to server. 
			    $trasaction_id  = $_POST["txn_id"];
			    $payment_status = strtolower($_POST["payment_status"]);
			    $invoice		= $_POST["invoice"];
			    if ($p->validate_ipn()){ // validate the IPN, do the others stuffs here as per your app logic
				    $this->paypal_model->saveOrder($_POST);
				    $subject = 'Instant Payment Notification - Recieved Payment';
				    $p->send_report($subject); // Send the notification about the transaction
			    }else{
				    $subject = 'Instant Payment Notification - Payment Fail';
				    $p->send_report($subject); // failed notification
			    }
		    break;
		}
	    $this->load->view('paypal');
	    } else {
		$this->load->view('paypal');
	    }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */