<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'controllers/apis/apis.php';

class Category extends Apis{
    
    public $uid = null;
    
    function __construct(){
        parent::__construct();
        $this->uid = $this->validateToken();
        $this->load->model('apis/category_model');
    }
            
    function list_get(){
        $data = $this->category_model->listCat($this->uid);
        $this->response($data);
    }
    
}