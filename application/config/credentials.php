<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$this_script        = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//require_once APPPATH . 'config/config.php';echo "hi"; 

$config['PayPalMode']         = 'sandbox'; // sandbox or live
$config['PayerEmailId']       = 'rekha.cyberlinks@gmail.com'; //Payer Email id on PayPal 
$config['merchantEmailId']    = 'rekha.cyberlinks-facilitator@gmail.com'; //Merchant Id on PayPal 
$config['PayPalCurrencyCode'] = 'INR'; //Paypal Currency Code
$config['PayPalIpnURL']    = base_url().'checkout?action=ipn'; //PayPal ipn url
$config['PayPalReturnURL']    = base_url().'checkout?action=success'; //Point to process.php page
$config['PayPalCancelURL']    = base_url().'checkout?action=cancel'; //Cancel URL
$config['sandboxpaypalurl']   = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$config['livepaypalurl']      = 'https://www.paypal.com/cgi-bin/webscr';

