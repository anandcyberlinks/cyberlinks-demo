<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

/***
 * Some basic token are reserved here
 * at : application token { pass every time in every request}
 * st : pagination start with
 * lt : limit of result
 * ob : order by field name 
 * od : asc/desc ascending and descending of result
 */ 
 
class Apis extends REST_Controller{
    
    public $app = '';
    public $user = '';
    public $start = 0;
    public $limit = 10;
    public $order_by = null;
    public $order = 'ASC';
    
    /***
     * at : Application token
     */ 
    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        
        $qString = $this->get();
        $this->start = isset($qString['st']) && $qString['st'] > 0 ? $qString['st'] : 0;
        $this->limit = isset($qString['lt']) && $qString['lt'] > 0 ? $qString['lt'] : 10;
        $this->order = isset($qString['od']) && $qString['od'] != '' ? $qString['od'] : 'ASC';
        $this->order_by = isset($qString['ob']) && $qString['ob'] != '' ? $qString['ob'] : null;
        
        if(isset($qString['at']) && $qString['at'] != ''){
            $flag = $this->validrequest($qString['at']);
            if($flag){
                
            }else{
                $this->response(array('error'=>'Invalid App Token'));
            }
        }elseif(isset($qString['ut']) && $qString['ut'] != ''){
            $flag = $this->validrequest($qString['ut'],'user');
            if($flag){
                echo 'valid user';
            }else{
                $this->response(array('error'=>'Invalid User Token'));
            }
        }else{
            if($this->uri->segment(2) == 'users' && $this->uri->segment(3) == 'applogin'){
                return true;
            }else{
                $this->response(array('error'=>'Invalid Request'));
            }
        }
    }
    
    function validrequest($token,$type = 'app'){
        $return  = false;
        switch($type){
            case 'app' :
                $this->db->select('*');
                $this->db->where('token',$token);
                $resultset = $this->db->get('users')->result();
                if(isset($resultset['0']->id) && $resultset['0']->id > 0){
                    $this->app = $resultset[0];
                    $return  = true;
                }else{
                    $return  = false;
                }
                break;
            case 'user' :
                
                $query = sprintf('select
                                 u.id as app_id,
                                 u.username as app_username,
                                 u.email as app_email,
                                 u.owner_id as app_owner_id,
                                 u.first_name as app_first_name,
                                 u.last_name as app_last_name,
                                 u.gender as app_gender,
                                 u.contact_no as app_contact_no,
                                 u.password as app_password,
                                 u.token as app_token,
                                 
                                 
                                 c.id as user_id,
                                 c.username as user_username,
                                 c.email as user_email,
                                 c.owner_id as user_owner_id,
                                 c.first_name as user_first_name,
                                 c.last_name as user_last_name,
                                 c.gender as user_gender,
                                 c.contact_no as user_contact_no,
                                 c.password as user_password,
                                 c.token as user_token,
                                 c.image
                                 
                                 from api_token at
                                 left join customers c on c.id = at.user_id
                                 left join users u on u.id = c.owner_id
                                 where at.token = "%s" ',$token);
                $dataset = $this->db->query($query)->result();
                if(count($dataset) > 0){
                    $data = reset($dataset);
                    $this->app = (object) array('id'=>$data->app_id,'username'=>$data->app_username,'email'=>$data->app_email,'owner_id'=>$data->app_owner_id,'first_name'=>$data->app_first_name,'last_name'=>$data->app_last_name);
                    $this->user = (object) array('id'=>$data->user_id,
                                                 'username'=>$data->user_username,
                                                 'email'=>$data->user_email,
                                                 'owner_id'=>$data->user_owner_id,
                                                 'first_name'=>$data->user_first_name,
                                                 'last_name'=>$data->user_last_name,
                                                 'password'=>$data->user_password,
                                                 'image'=> file_exists($data->image) ? base_url().$data->image : base_url().'assets/upload/profilepic/userdefault.png',
                                                );
                    $return  = true;
                }else{
                    $return  = false;
                }
                break;
        }
        return $return;
    }
    
    function response($data){
        header('Access-Control-Allow-Origin: *');
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
    
    function sendmail($data = array()){
        $this->load->library('email');
        $result = $this->email->from($data['from'])
                  ->reply_to($data['from'])
                  ->to($data['to'])
                  ->subject($data['subject'])
                  ->message($data['body'])->send();
                  
        return $result;          
    }
}