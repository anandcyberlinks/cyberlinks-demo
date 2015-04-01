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
        $sort = 'channels.id';
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "advertising/index/";
        $config["total_rows"] = $this->videos_model->get_channelCount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['category'] = $this->videos_model->get_channelCategory($this->uid);
        $data['result'] = $this->videos_model->get_channels($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
  //echo '<pre>';print_r( $data['result']); exit;
        $this->show_view('advertising/cuepoints',$data);
    }
    function getlistdetail()
    {
         //$sort_by = 'desc';
        //$sort = 'd.duration';
         $sort_by = '';
        $sort = '';
        $data['result'] = $this->videos_model->get_cuepoint_channel($_REQUEST['IDs'],$sort, $sort_by);
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
       foreach($data['result'] as $key => $val){
           $new_val = json_decode($val->url);
           $val->url = $new_val['0']->{'web'}->{'3g'};
       }
       echo json_encode($data);
        /*$content = array();
$content['popup_content'] = $this->load->view('advertising/cuepoints',array(), TRUE);
$this->show_view("advertising/cuepoints", $content);*/
        
    }
    
    function getVdoListDetail()
    {
        $sort_by = '';
        $sort = '';
        $data['result'] = $this->videos_model->get_cuepoint_video($_REQUEST['IDs'],$sort, $sort_by);
        //echo 'm'; echo '<pre>'; print_r($data['result']); exit;
        $innerHtml = '';
        $json_Ids = json_encode($_REQUEST['IDs']); 
        $i=1;
        
        foreach($data['result'] as $key => $val){
           $new_val = json_decode($val->url);
           $val->url = $new_val['0']->{'web'}->{'3g'};
        }
        echo json_encode($data);
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
            $result = $this->videos_model->validate_cuepoint_duration($id,$_POST['timeInMillisec'],$_POST['vdo_cuepoint']);
            /*if($_POST['vdo_cuepoint']==1)
            {
                $result=1;
            }*/
               
          $innerArray = array();
           if($result ==1){
               $data[$i]["content_id"]= $id;
               $data[$i]["cue_points"]=$_POST['timeInMillisec'];
               $data[$i]["title"] =$_POST['cueName'];
               $data[$i]["created"] = $created;
               if($_POST['vdo_cuepoint']==1){
                    $data[$i]["type"] = "vdo";
               }
               $i++;
            }
          }
         // $editFlag = $_POST['editFlag'];
       $insertStatus = $this->videos_model->insertCuePoints($data);
       
    }
function  updateCuePoint()
    {
    //echo "<pre>";
       //print_r($_POST);
       //die();
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
               $result = $this->videos_model->validate_cuepoint_duration($id,$k);               
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
          if(count($data) > 0){
            $updateStatus = $this->videos_model->updateCuePoints($data,$_POST['IDs']);
          }
       
    }
        
   function  deleteCuePoint()
    {
    //echo "<pre>";
      // print_r($_POST);
       //die();
     // echo "<pre>";
      $post = $_POST['cuepointArr'];
      $delVal = $_POST['delval'];
    
      $updateStatus = $this->videos_model->deleteCuePoints($_POST['IDs'],$delVal);
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
    
    function channelCuePoints(){
        $channel_cue_points = $this->videos_model->getChannelCuePoints($_POST['channel_id']);
        if(count($channel_cue_points) > 0){
            $i =1;
            $html = '<table class="table table-striped"><tbody><tr><th width="5%" style="border-right: 1px solid gray;">Sr No.</th><th width="10%" style="border-right: 1px solid gray;">Title</th><th width="5%" style="border-right: 1px solid gray;">Cuepoint</th></tr>';
            foreach($channel_cue_points as $key => $val){
                $html .= '<tr>';
                $html .= '<td  width="5%" style="border-right: 1px solid gray;">'.$i.'</td>';
                $html .= '<td  width="10%" style="border-right: 1px solid gray;">'.$val->title.'</td>';
                $html .= '<td  width="10%" style="border-right: 1px solid gray;">'.  time_from_seconds($val->cue_points).'</td>';
                $html .= '</tr>';
                $i++;
                
            }
            $html .= '</tbody></table>';
            echo $html;
        }
        exit;
    }
    
    function editChannelCuePoints(){
        //$new_array = array();
        $channel_cue_points = $this->videos_model->getChannelCuePoints($_POST['channel_id']);
        if(count($channel_cue_points) > 0){
            $i = 1;
            foreach($channel_cue_points as $key => $val){
                $new_array[$i]['title'] = $val->title;
                $new_array[$i]['cuepoint'] = time_from_seconds($val->cue_points);
                $i++;
            }
        }
        echo  json_encode($new_array); exit;
    }
    
    /*
     * Function for Advertising Configuration
     */
    function configuration(){
        $data['welcome'] = $this;
        $this->load->model('ads/ads_model');
        $data['adSources'] = $this->videos_model->getAdSources();
        $data['userAdsConfig']  = $this->ads_model->getAdsConfiguration($this->uid);
        
        if(isset($_POST['submit'])){
            if(@$_POST['ad_config']!=""){
                $this->videos_model->saveUserAdConfig($this->uid,$_POST);
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Configuration successfully saved.</div></div></section>');
                redirect('advertising/configuration');
            }
        }
        $this->show_view('advertising/ad_configuration',$data);
        
    }
    
    /*
     * function to add new source for the configuration.
     */
    function add_source(){
        $data['welcome'] = $this;
        if (isset($_POST['submit']) && $_POST['submit'] = 'Submit') {
            $post['title'] = $_POST['title'];
            $post['description'] = $_POST['description'];
            $post['status'] = $_POST['status'];
            $this->videos_model->insert_sources($post);
            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Source sucessfully added.</div></div></section>');
            redirect('advertising/configuration');
        }
        $this->show_view('advertising/add_source',$data);
    }
    
    
    function vdo() {
        $searchterm = '';
        if ($this->uri->segment(2) == '') {
            $this->session->unset_userdata('search_form');
        }
        $sort = $this->uri->segment(3);
        $sort_by = $this->uri->segment(4);
        $data['welcome'] = $this;
        switch ($sort) {
            case "category":
                $sort = 'b.category';
                if ($sort_by == 'asc')
                    $data['show_c'] = 'desc';
                else
                    $data['show_c'] = 'asc';
                break;
            case "user":
                $sort = 'a.uid';
                if ($sort_by == 'asc')
                    $data['show_u'] = 'desc';
                else
                    $data['show_u'] = 'asc';
                break;
            case "status":
                $sort = 'a.status';
                if ($sort_by == 'asc')
                    $data['show_s'] = 'desc';
                else
                    $data['show_s'] = 'asc';
                break;
            case "created":
                $sort = 'a.created';
                if ($sort_by == 'asc')
                    $data['show_ca'] = 'desc';
                else
                    $data['show_ca'] = 'asc';
                break;
            case "title":
                $sort = 'a.title';
                if ($sort_by == 'asc')
                    $data['show_t'] = 'desc';
                else
                    $data['show_t'] = 'asc';
                break;
            default:
                $sort_by = 'desc';
                $sort = 'a.id';
        }
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } else if (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $searchterm = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "advertising/vdo/";
        $config["total_rows"] = $this->videos_model->get_videocount($this->uid, $searchterm);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->videos_model->get_video($this->uid, PER_PAGE, $page, $sort, $sort_by, $searchterm);
        $data["links"] = $this->pagination->create_links();
        $data['category'] = $this->videos_model->get_category($this->uid);
        $data['total_rows'] = $config["total_rows"];
        $this->show_view('vdo', $data);
    }
 }