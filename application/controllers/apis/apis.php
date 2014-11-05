<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class Apis extends REST_Controller{
    
    public $app = '';
    
    /***
     * at : Application token
     */ 
    function __construct(){
        parent::__construct();
        $qString = $this->get();
        if(isset($qString['at']) && $qString['at'] != ''){
            $flag = $this->validrequest($qString['at']);
            if($flag){
                
            }else{
                $this->response(array('error'=>'Invalid Token'));
            }
        }else{
            if($this->uri->segment(2) == 'users' && $this->uri->segment(3) == 'applogin'){
                return true;
            }else{
                $this->response(array('error'=>'Invalid Request'));
            }
        }
    }
    
    function validrequest($token){
        $this->db->select('*');
        $this->db->where('token',$token);
        $resultset = $this->db->get('users')->result();
        if(isset($resultset['0']->id) && $resultset['0']->id > 0){
            $this->app = $resultset[0];
            return true;
        }else{
            return false;
        }
    }
    
    function response($data){
        $type = isset($_GET['type']) && $_GET['type'] != '' ? $_GET['type'] : 'json';
        switch($type){
            case 'json' :
                print json_encode($data);
                break;
            case 'xml' :
                print $this->array2xml($data);
                break;
        }
        exit;
    }
    
    function array2xml($array, $xml = false){
        if($xml === false){
            $xml = new SimpleXMLElement('<result/>');
        }
        foreach($array as $key => $value){
            if(is_array($value)){
                $this->array2xml($value, $xml->addChild($key));
            }else{
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}