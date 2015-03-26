<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Auth_model extends CI_Model {

   function __construct()
   {
       parent::__construct();
       $this->load->database();
   }
   
    function addcontent_token($data)
    {
        $this->db->set($data);       
	$this->db->insert('content_tokens', $data);
	//echo '<br>'.$this->db->last_query();die;
	return $this->db->insert_id();
    }
    
}
