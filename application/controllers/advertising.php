<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
 
 class Advertising extends MY_Controller{
    
    function __construct()
    {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('videos_model');
        $this->load->helper('common');
        $this->load->library('session');
        $this->load->library('form_validation');
       
        $data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
    }
    
    function index()
    {
        $data['welcome'] = $this;
        $sort_by = 'desc';
        $sort = 'a.id';
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "advertising/index/";
        $config["total_rows"] = $this->videos_model->get_videocount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['category'] = $this->videos_model->get_category($this->uid);
        $data['result'] = $this->videos_model->get_video($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
  //echo '<pre>';print_r( $data['result']);
        $this->show_view('advertising/cuepoints',$data);
    }
    function getlistdetail()
    {
         $sort_by = 'desc';
        $sort = 'd.duration';
        $data['result'] = $this->videos_model->get_cuepoint_video($_REQUEST['IDs'],$sort, $sort_by);
       $innerHtml = '';
       //echo "<pre>";
       //print_r($data['result']);
       $json_Ids = json_encode($_REQUEST['IDs']); 
       $i=1;
        // foreach ($data['result'] as $v)
          //{
            // $className = "main_tr";
              // echo $v->title."<br>"; 
             //$innerHtml .= '<tr  class="'.$className.'"><td style="border-right: 1px solid gray;padding: 0px">'.$i.'</td><td style="border-right: 1px solid gray;padding: 0px"><img src="'.$v->thumbnail.'"></td><td style="border-right: 1px solid gray;padding: 0px" class="loading"></td><input type="hidden" class="video_id" name="video_id" value="'.$v->id.'"><input type="hidden" class="duration" name="duration" value="'.$v->duration.'"></tr>';            
            //$i++;
               
          //}
        //echo $innerHtml;
       echo json_encode($data);
        /*$content = array();
$content['popup_content'] = $this->load->view('advertising/cuepoints',array(), TRUE);
$this->show_view("advertising/cuepoints", $content);*/
        
    }
    function setcuepoint()
    {
         $sort_by = 'asc';
        $sort = 'a.cue_points';
        $data['result'] = $this->videos_model->getCuePointInfo($_REQUEST['IDs'],$sort, $sort_by);
        echo json_encode($data);
        
    }
   function  inserCuePoint()
    {
       //print_r($_POST);
      // die();
     // echo "<pre>";
     $created = date('Y-m-d h:i:s');
      $data = array();
      $i=0;
       foreach($_POST['IDs'] as $key=>$id)
       {
           //-- check if cue point is greater than video duration --//
            $result = $this->videos_model->validate_cuepoint_duration($id,$_POST['timeInMillisec']);
               
          $innerArray = array();
           if($result ==1){  
               $data[$i]["content_id"]= $id;
               $data[$i]["cue_points"]=$_POST['timeInMillisec'];
               $data[$i]["title"] =$_POST['cueName'];
               $data[$i]["created"] = $created;
               $i++;
            }
          }
         // $editFlag = $_POST['editFlag'];
       $insertStatus = $this->videos_model->insertCuePoints($data);
       
    }
function  updateCuePoint()
    {
   // echo "<pre>";
     //  print_r($_POST);
      // die();
     // echo "<pre>";
         
      $created = date('Y-m-d h:i:s');
      $data = array();
      $i=0;
       foreach($_POST['IDs'] as $key=>$id)
       {         
          $innerArray = array();
          
          foreach ($_POST['cuepointArr'] as $k=>$v)
              {
               
               //-- check if cue point is greater than video duration --//
               $result = $this->videos_model->validate_cuepoint_duration($id,$v);
               if($result ==1){              
                $data[$i]['cue_points'] = $k;
                 $data[$i]['title'] = $v;
                $data[$i]["content_id"]= $id;
                $data[$i]["created"] = $created;
                $i++;
               }
               } 
          }
          //echo "<pre>";
          //print_r($data);
          //die();
          //$editFlag = $_POST['editFlag'];
       $updateStatus = $this->videos_model->updateCuePoints($data,$_POST['IDs']);
       
    }
        
   function  deleteCuePoint()
    {
    //echo "<pre>";
       //print_r($_POST);
       //die();
     // echo "<pre>";
      $post = $_POST['cuepointArr'];
      $delVal = $_POST['delval'];
      
      if(count($post) >1){
         unset($post[array_search($delVal, $post)]);    
      }
      $data = array();
      $i=0;
       foreach($_POST['IDs'] as $key=>$id)
       {         
          $innerArray = array();
          
          foreach ($post as $k=>$v)
              {               
               //-- check if cue point is greater than video duration --//
               $result = $this->videos_model->validate_cuepoint_duration($id,$v);
                  if($result ==1){                 
                   $data[$i]['cue_points'] = $v;
                   $data[$i]["content_id"]= $id;
               
                   $i++;
                  }
               } 
          }
          //echo "<pre>";
          //print_r($data);
          //die();
          //$editFlag = $_POST['editFlag'];
          if(!empty($data)){
       $updateStatus = $this->videos_model->updateCuePoints($data,$_POST['IDs'],"del");
          }
       
    }
    
    function live_stream()
    {
        $data['welcome'] = $this;
        $sort_by = 'desc';
        $sort = 'a.id';
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "advertising/live_stream/";
        $config["total_rows"] = $this->videos_model->get_livestreamcount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['category'] = $this->videos_model->get_channel_categories();
        $data['result'] = $this->videos_model->get_livestream($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
  //echo '<pre>';print_r( $data['result']);
        $this->show_view('advertising/live_stream',$data);
    }
    
    function  inserCuePointLiveStream()
    {
      $data = array();
      $created = date('Y-m-d h:i:s');
      
       $this->videos_model->deleteCuePointsLivestream($_POST['channel_ids']);
       foreach($_POST['channel_ids'] as $key=>$val)
       {
          $i=0;
          
          foreach($_POST['cue_points'] as $key1 => $val1)
          {
              $data[$i]["content_id"]= $val;
              $data[$i]["title"] =$_POST['cue_name'];
              $data[$i]["type"]='live';
              $data[$i]["cue_points"]=$val1;
              $data[$i]["created"]=$created;
              $i++;
          }
          //echo '<pre>';          print_r($data);
          $insertStatus = $this->videos_model->insertCuePointsLivestream($data);
        }
       
    }
 }