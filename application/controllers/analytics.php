<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(1);
class Analytics extends MY_Controller {

	function __construct()
	{
            parent::__construct();
            $this->load->model('/api/Video_model');
	    $this->load->model('/analytics/Analytics_model');
	    $this->load->library('User_Agent');
	    $this->load->helper('common');
	    $this->load->helper('pdf_helper');
	    $this->load->helper('csv_helper');
	    
	     $this->load->config('messages');
	    $this->data['welcome'] = $this;
	    
	    //$this->load->library('User_Agent');//--regex class to get user agent --//
	    //-- get browser http_user_agent info in array --//
                    //$this->result = get_browser(null, true);
		    
		   $this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		  
                //---------------------//
		
		$this->load->library('session');
		$s = $this->session->all_userdata();
		$this->user = $s[0]->username;
		$this->uid = $s[0]->id;
		$this->role_id = $s[0]->role_id;
	}
	
	function form()
	{
		
		$this->load->view('analytics-form');
	}
	
	function index()
	{
		//-- get geocoding google api --//
		$this->data['lat'] = $lat = $_GET['lat'];
		$this->data['long'] = $lng = $_GET['lng'];
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
		$data = @file_get_contents($url);
		$result = json_decode($data,true);
		//echo '<pre>';print_r($result);die;
		$this->data['geodata'] = $result['results'][0]['address_components'];
		//------------------------------
		$this->load->helper('url');
                $id = $_GET['id'];
                $device = $_GET['device'];
                $this->data['result'] = $this->Video_model->video_play($id,$device);
                $this->data['user_id'] = $_GET['user_id'];		      
                $this->load->view('analytics',$this->data);
	}
        
        function addview()
        {
            $id = $_GET['id'];
            $this->Video_model->updateView($id);   
            echo 1;
        }
	
	function play()
	{
		$post = $_POST;                
		$post['browser'] = $this->result['browser'];
		$post['browser_version'] = $this->result['version'];
                $post['platform'] = $this->result['platform'];
               echo $this->Analytics_model->save($post);
	}
        
        function pause()
        {
            $post = $_POST;           
            $where = array('id'=>$post['id']);
           echo $this->Analytics_model->save($post,$where);
        }
        
        function complete()
        {
            $post = $_POST;           
            $where = array('id'=>$post['id']);
           echo $this->Analytics_model->save($post,$where);
        }
	
	function replay()
	{
		$post = $_POST;
		$post['browser'] = $this->result['browser'];
		$post['browser_version'] = $this->result['version'];
                $post['platform'] = $this->result['platform'];
		//$where = array('id'=>$post['id']);
		echo $this->Analytics_model->save($post);
	}
	
	function report()
	{
		$limit=5;
		$summary = $this->Analytics_model->getReport(array('type'=>'summary'));
		//echo '<pre>';print_r($summary);die;
		$this->data['summary'] = $summary[0];
		/* $url = "http://localhost:8085/solr/collection1/select?q=content_provider:".$this->uid."&wt=json&indent=true";
			$result = file_get_contents($url);
			$summary = json_decode($result);
		*/	
			//if($summary) {
			//  $this->data['summary'] = $summary->response->docs[0];            
		//}
	    
		$this->data['content'] = $this->Analytics_model->getReport(array('type'=>'content','l'=>$limit));
		$this->data['useragent'] = $this->Analytics_model->getReport(array('type'=>'useragent','l'=>$limit));
		$this->data['location'] = $this->Analytics_model->getReport(array('type'=>'location','l'=>$limit));
		$this->data['map'] = $this->Analytics_model->getReport(array('type'=>'map','l'=>$limit));
		$this->data['country'] = $this->Analytics_model->getReport(array('type'=>'country','l'=>$limit));
		$this->data['content_provider'] = $this->Analytics_model->getReport(array('type'=>'content_provider','l'=>$limit));
		$this->data['customer'] = $this->Analytics_model->getReport(array('type'=>'user','l'=>$limit));
		
		$this->show_view('analytics/report',$this->data);		
	}
	
	function ajax()
	{
		 $id = $_GET['user_id'];
		$result = $this->Analytics_model->getReport(array('id'=>$id,'type'=>'content'));
		echo json_encode($result);
	}
	
	function search_post($post)
	{
		if (isset($post['search']) && $post['search'] == 'Search') {			
			$this->session->set_userdata('search_form', $post);
		} else if (isset($post['reset']) && $post['reset'] == 'Reset') {
			$this->session->unset_userdata('search_form');
		} 
		return $this->session->userdata('search_form');	
	}
	
	function sort_input($sort_i,$sort_by)
	{
		switch ($sort_i) {
		    case "v":
			$sort = 'c.title';
			if ($sort_by == 'asc')
			    $this->data['show_c'] = 'desc';
			else
			    $this->data['show_c'] = 'asc';

			break;
		    case "p":
			$sort = 'u.first_name';
			if ($sort_by == 'asc')
			    $this->data['show_p'] = 'desc';
			else
			    $this->data['show_p'] = 'asc';

			break;
		    case "h":
			$sort = 'count(a.content_id)';
			if ($sort_by == 'asc')
			    $this->data['show_h'] = 'desc';
			else
			    $this->data['show_h'] = 'asc';

			break;
			case "t":
			$sort = 'sum(a.watched_time)';
			if ($sort_by == 'asc')
			    $this->data['show_t'] = 'desc';
			else
			    $this->data['show_t'] = 'asc';
			    			    
			break; 
		    default:
			$sort_by = 'desc';
			$sort = 'a.id';
		}
		return $sort;
	}
	
	function content()
	{
		//$this->session->unset_userdata('search_form');
		$search = $this->search_post($_POST);
		
		$sort_i = $this->uri->segment(3); 
		$sort_by = $this->uri->segment(4);
		if($sort_i !=''){
			$this->data['sort_by'] =  $sort_by;
			$this->data['sort_i'] =  $sort_i;
		}else{
			$this->data['sort_by'] =  'asc';
			$this->data['sort_i'] = 'v';
		}
		//-- sorting input --//
		$sort = $this->sort_input($sort_i,$sort_by);
		//-----//
		
		//-- summary report --//
		$summary = $this->Analytics_model->getReport(array('type'=>'summary','search'=>$search),$sort,$sort_by);
		$this->data['summary'] = $summary[0];
		//--- search form content provider --//
		$this->data['content_provider'] = $this->Analytics_model->getContentProvider();
		//-----------------------//
		
		$this->data['content'] = $this->Analytics_model->getReport(array('type'=>'content','search'=>$search),$sort,$sort_by);
				
		$this->show_view('analytics/content_report',$this->data);
	}
	
	function user()
	{
		//$this->session->unset_userdata('search_form');
		$search = $this->search_post($_POST);
		
		$sort_i = $this->uri->segment(3); 
		$sort_by = $this->uri->segment(4);
		
		if($sort_i !=''){
			$this->data['sort_by'] =  $sort_by;
			$this->data['sort_i'] =  $sort_i;
		}else{
			$this->data['sort_by'] =  'asc';
			$this->data['sort_i'] = 'v';
		}
		
		//-- sorting input --//
		$sort = $this->sort_input($sort_i,$sort_by);
		//-----//
		
		//-- summary report --//
		$summary = $this->Analytics_model->getReport(array('type'=>'summary','search'=>$search),$sort,$sort_by);
		$this->data['summary'] = $summary[0];		
		//echo '<pre>';print_r($summary);die;
		$this->data['user'] = $this->Analytics_model->getReport(array('type'=>'user','search'=>$search),$sort,$sort_by);
				
		$this->show_view('analytics/user_report',$this->data);
	}

		
	function usercontent()
	{
		//$this->session->unset_userdata('search_form');
		$search = $this->search_post($_POST);
		if($_REQUEST['id']){
			$this->data['userid'] = $id = $_REQUEST['id'];
		}elseif($this->uri->segment(6) !='')
		{
			$this->data['userid'] = $id = $this->uri->segment(6);
		}
		
		$sort_i = $this->uri->segment(3); 
		$sort_by = $this->uri->segment(4);
		
		if($sort_i !=''){
			$this->data['sort_by'] =  $sort_by;
			$this->data['sort_i'] =  $sort_i;
		}else{
			$this->data['sort_by'] =  'asc';
			$this->data['sort_i'] = 'v';
		}
		//-- sorting input --//
		$sort = $this->sort_input($sort_i,$sort_by);
		//-----//
	
		//-- summary report --//
		$summary = $this->Analytics_model->getReport(array('id'=>$id,'type'=>'summary','search'=>$search),$sort,$sort_by);
		$this->data['summary'] = $summary[0];
		//--- search form content provider --//
		$this->data['content_provider'] = $this->Analytics_model->getContentProvider();
		//-----------------------//
		
		$this->data['content'] = $this->Analytics_model->getReport(array('id'=>$id,'type'=>'content','search'=>$search),$sort,$sort_by);
				
		$this->show_view('analytics/user_content_report',$this->data);
	}
		
	function export()
	{
		$search = $this->session->userdata('search_form');
		//echo $this->uri->segment(4);die;
		//-- sorting input --//
		
		$sort_i = $this->uri->segment(5); 
		$sort_by = $this->uri->segment(6);
		
		$type = $this->uri->segment(8);
		
		$id = $this->uri->segment(10);
		
		$sort = $this->sort_input($sort_i,$sort_by);
		//-----//
		//-- result --//		
			$this->data['result'] = $this->Analytics_model->getReport(array('id'=>$id,'type'=>$type,'search'=>$search),$sort,$sort_by);
		//echo '<pre>';print_r($this->data['result']);die;
		
		if($type == 'content'){
			if($this->uri->segment(4)=='pdf'){
				 $content =  $this->load->view('templates/pdf_content',$this->data,true);
				//-- create pdf --//
				create_pdf($content, 'Content Base Report');
			}elseif($this->uri->segment(4)=='csv'){
				$heading = array('Name','Content Provider','Total Hits','Total time watched');
				//$content =  $this->load->view('templates/pdf_content',$this->data,true);				
				
				$dataRpt = array();
				$num=0;
				foreach($this->data['result'] as $p) {
				    $dataRpt[$num]['title']       = $p->title;
				    $dataRpt[$num]['content_provider']  = $p->content_provider;
				    $dataRpt[$num]['hits']        = $p->total_hits;
				    $dataRpt[$num]['watched time'] = time_from_seconds($p->total_watched_time);                 
				    $num++;
			       }
				query_to_csv($dataRpt,$heading);
				//echo query_to_csv($content);
				//exit;
			}
		}
		
		if($type == 'usercontent'){
			if($this->uri->segment(4)=='pdf'){
				 $content =  $this->load->view('templates/pdf_usercontent',$this->data,true);
				//-- create pdf --//
				create_pdf($content, 'User Content Report');
			}elseif($this->uri->segment(4)=='csv'){
				$heading = array('Name','Content Provider','Total Hits','Total time watched');
				//$content =  $this->load->view('templates/pdf_content',$this->data,true);				
				//print_r($this->data['result']);die;
				$dataRpt = array();
				$num=0;
				foreach($this->data['result'] as $p) {
				    $dataRpt[$num]['title']       = $p->title;
				    $dataRpt[$num]['content_provider']  = $p->content_provider;
				    $dataRpt[$num]['hits']        = $p->total_hits;				    
				    $dataRpt[$num]['watched time'] = time_from_seconds($p->total_watched_time);                 
				    $num++;
			       }
				query_to_csv($dataRpt,$heading);
				//echo query_to_csv($content);
				//exit;
			}
		}
		
		if($type == 'user'){
			if($this->uri->segment(4)=='pdf'){
				$user =  $this->load->view('templates/pdf_user',$this->data,true);				
				//-- create pdf --//
				create_pdf($user,'User Based Report');
			}elseif($this->uri->segment(4)=='csv'){
				$heading = array('Name','Total Hits','Total time watched');
				//$content =  $this->load->view('templates/pdf_content',$this->data,true);
				$dataRpt = array();
				$num=0;
				foreach($this->data['result'] as $p) {
				    $dataRpt[$num]['name']          = $p->name;
				    $dataRpt[$num]['hits']        = $p->total_hits;
				    $dataRpt[$num]['watched time'] = time_from_seconds($p->total_watched_time);                 
				    $num++;
			       }
				query_to_csv($dataRpt,$heading);
				//echo query_to_csv($content);
				//exit;
			}
		}		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
