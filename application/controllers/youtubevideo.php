<?php
  ini_set('display_errors',1);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Youtubevideo extends MY_Controller
{
    public $user = null;
    public $role_id = null;
    public $uid = null;
    public $allowedImageExt;

	
    function __construct(){
        parent::__construct();
	$this->load->config('messages');
        $this->load->model('Youtubevideo_model');
	$this->load->library('session');
	$this->load->library('form_validation');
        $data['welcome'] = $this;
	$s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
	$this->allowedImageExt = array('gif', 'png', 'jpeg', 'jpg');

    }

	
    function index() {
        $this->data['welcome'] = $this;
	if (isset($_POST['submit']) && ($_POST['submit']=='Search')) {
	  $user = $_POST['title'];
	  $videotype = $_POST['videotype'];
	  $api = file_get_contents("http://gdata.youtube.com/feeds/api/users/" . $user . "/uploads?v=2&alt=jsonc&start-index=1&max-results=12");
	  $datayoutube = json_decode($api);
	  //echo '<pre>';print_r($datayoutube->data->items);echo '</pre>'; exit;
	  $this->data['result'] = $datayoutube->data->items;
	  $this->show_view('youtubevideo', $this->data);

	} else {
	  $this->show_view('youtubevideo', $this->data);
	}
    }
    
}