
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smart_analytics extends MY_Controller {
    
    
	function __construct()
	{
		parent::__construct();
		//$this->load->model('/api/Video_model');
		//$this->load->model('/ads/Ads_analytics_model');
		$this->load->model('/analytics/newanalytics_model');
		$this->load->library('User_Agent');
		$this->load->helper('common');
		//  $this->load->helper('pdf_helper');
		//  $this->load->helper('csv_helper');

		$this->load->config('messages');
		$this->data['welcome'] = $this;
		$per = $this->check_per();
        if(!$per){
          redirect(base_url() . 'layout/permission_error');
        }
		//$this->load->library('User_Agent');//--regex class to get user agent --//
		//-- get browser http_user_agent info in array --//
		//   $this->result = get_browser(null, true);

		$this->result = User_Agent::getinfo();  //--regex class to get user agent --//
		//---------------------//
		$this->load->library('session');
		$s = $this->session->all_userdata();
		$this->user = $s[0]->username;
		$this->uid = $s[0]->id;
		$this->role_id = $s[0]->role_id;
	}
	
	
	function getDateIntervel($dayDifference){
		$dayDiff  = array();
		$startDate = date("Y-m-d");
		if($dayDifference == 1)
                {
                 $endDate = $startDate; 
		}else
                {
                    $endDate =  date("Y-m-d",strtotime("-$dayDifference day", strtotime($startDate))) ;
		}
               
		 $dayDiff['startdate'] = $endDate;
		$dayDiff['enddate'] = $startDate;
		return $dayDiff ;
	}
        
	function index(){
		$days = $this->input->post('daydiff'); 
			if($days =='')
				$days =1;
		if($days === 'Today' || $days === 0)
			 $days = 1;
		
		$dayDiff = $this->getDateIntervel($days);
		// print_r($dayDiff);
		
		$prepareData = array();
                $sqlData = array();
		$sqlData['startdate'] = $dayDiff['startdate']." 00:00:00";
		$sqlData['enddate']=   $dayDiff['enddate']." 23:59:59";
		//$sqlData['startdate']	=   "2015-05-19 00:00:00";
		//$sqlData['enddate']		=   "2015-05-19 23:59:59"; 
	
		$TotalSession 			=	$this->newanalytics_model->getTotalSession($sqlData);
		$prepareData['totalsession'] = $TotalSession;
		$TotalUser =	$this->newanalytics_model->getTotalUser($sqlData);
		$prepareData['totaluser'] = $TotalUser;	
		$NewUser =	$this->newanalytics_model->getNewUser($sqlData);
		$prepareData['newuser'] = $NewUser;
		$Platform =	$this->newanalytics_model->getPlatform($sqlData);
		$prepareData['platform'] = $Platform;
		$Resolution =	$this->newanalytics_model->getResolution($sqlData);
		$prepareData['resolution'] = $Resolution;
		$Carrier =	$this->newanalytics_model->getCarrier($sqlData);
		$prepareData['carrier'] = $Carrier;
		$TopUser =	$this->newanalytics_model->getTopUser($sqlData);
		$prepareData['topuser'] = $TopUser;
		$CountryUser =	$this->newanalytics_model->getCountryUser($sqlData);
		$prepareData['country'] = $CountryUser;
		$TotalSessionDayWise 	= 	$this->newanalytics_model->getTotalSessionDayWise($sqlData);
		// echo "<pre>";
		//		print_r($TotalSessionDayWise);
			//	die; 
		$prepareData['graph']['totalsession'] = $TotalSessionDayWise;
		$TotalUserDaysWaise 	=	$this->newanalytics_model->getTotalUserDaysWaise($sqlData);
		$prepareData['graph']['totaluser'] = $TotalUserDaysWaise;
		$NewUserPerDay 			= 	$this->newanalytics_model->getNewUserPerDay($sqlData);
		$prepareData['graph']['totalnewuser'] = $TotalUserDaysWaise;
		$ReturningUserPerDay 			= $this->newanalytics_model->getReturningUserPerDay($sqlData);
		$prepareData['graph']['returninguser'] = $TotalUserDaysWaise;
		$data['welcome'] = $this;
		 // echo "<pre>";
		//		print_r($prepareData);
		//		die; 
	    $data['jsondata'] = json_encode($prepareData,true);
		if (!empty($_POST)) {
				ECHO $data['jsondata'] ;
				//echo $this->show_view('newdashboard',$data,false);
				exit;
		}else{
			$this->show_view('newdashboard',$data);
		}
	}
        
 
   function Users(){
		$days = $this->input->post('daydiff'); 
			if($days =='')
				$days =1;
		if($days === 'Today' || $days === 0)
			 $days = 1;
		 if (!empty($_POST))
               {
		//$days = 3;
		$dayDiff = $this->getDateIntervel($days);
		$prepareData = array();
        $sqlData = array();
		$startDate = $sqlData['startdate'] = $dayDiff['startdate']." 00:00:00";
		$endDate= $sqlData['enddate']=   $dayDiff['enddate']." 23:59:59";
		$TotalUserDaysWaise 	=	$this->newanalytics_model->getTotalUserDaysWaise($sqlData);
		$prepareData['graph']['totaluser'] = $TotalUserDaysWaise;
		$NewUserPerDay 			= 	$this->newanalytics_model->getNewUserPerDay($sqlData);
		$prepareData['graph']['totalnewuser'] = $NewUserPerDay;
		$ReturningUserPerDay 			= $this->newanalytics_model->getReturningUserPerDay($sqlData);
		$prepareData['graph']['returninguser'] = $ReturningUserPerDay;
		
		$gridData  =  array();
		$k=1;
		$resultData =  array();
		if($dayDiff['startdate'] === $dayDiff['enddate']){
			for($i = 1 ; $i<= 24 ; $i++)
				{
					//$gridData[$k]['hr']  = $prepareData['graph']['totaluser'][$i]['hr'];
					//$gridData[$k]['t']  =  sprintf('%02d',($prepareData['graph']['totaluser'][$i]['hr']-1)).":00";
					$gridData[$k]['hr']  =  sprintf('%02d',($prepareData['graph']['totaluser'][$i]['hr']-1)).":00";
					$h = sprintf('%02d',($prepareData['graph']['totaluser'][$i]['hr']-1)).":00";
					$gridData[$k]['h']  = sprintf('%02d', $prepareData['graph']['totaluser'][$i]['hr']).":00";
					$gridData[$k]['totaluser']  = $prepareData['graph']['totaluser'][$i]['totaluser'];
					$gridData[$k]['totalnewuser']  = $prepareData['graph']['totalnewuser'][$i]['totaluser'];
					$gridData[$k]['returninguser']  = $prepareData['graph']['returninguser'][$i]['totaluser']; 
					$gridArray = array($h, $prepareData['graph']['totaluser'][$i]['totaluser'],$prepareData['graph']['totalnewuser'][$i]['totaluser'], $prepareData['graph']['returninguser'][$i]['totaluser']);
					$resultData['grid'][] = $gridArray;
					$k++;
				
				} 
				}else{
					   for($startDate = strtotime($startDate); $startDate <= strtotime($endDate); $startDate = strtotime("+1 day", $startDate)){
					   $i= $d = date('Y-m-d',$startDate); 						
					   $m = $gridData[$k]['hr']  =$prepareData['graph']['totaluser'][$i]['month'];
					   $gridData[$k]['date']  = $prepareData['graph']['totaluser'][$i]['date'];
					   $gridData[$k]['totaluser']  = $prepareData['graph']['totaluser'][$i]['totaluser'];
					   $gridData[$k]['totalnewuser']  = $prepareData['graph']['totalnewuser'][$i]['totaluser'];
					   $gridData[$k]['returninguser']  = $prepareData['graph']['returninguser'][$i]['totaluser']; 
					   $gridArray = array($m, $prepareData['graph']['totaluser'][$i]['totaluser'],$prepareData['graph']['totalnewuser'][$i]['totaluser'], $prepareData['graph']['returninguser'][$i]['totaluser']);
					   $resultData['grid'][] = $gridArray;
					   $k++;
						}
					}
			
				echo json_encode($resultData,true);
				exit;
		}else{
			$data['welcome'] = $this;
			$this->show_view('analytics_user',$data);
		}
	}
        
        
    function Loyalty(){
		if(!empty($_POST)){
			$days = $this->input->post('daydiff'); 
			if($days =='')
				$days =1;
			if($days === 'Today' || $days === 0)
				$days = 1;
				
			$dayDiff = $this->getDateIntervel($days);
			$resultData= $this->newanalytics_model->getLoyalityUser($dayDiff);
			$grid = array();
			foreach($resultData as $key=>$val){
				$sessionuser = $this->addsuffix($val['sessionofuse']);
				$grid['grid'][$key] =  array($sessionuser,$val['totaluser'],$val['percent']);
			}
			if(empty($grid['grid']))
				$grid['grid'] =array();
				
			echo json_encode($grid,true);
			exit;
		}else{
			$data['welcome'] = $this;
			$this->show_view('analytics_loyalty',$data);
		}
	}
        
		function addsuffix($num){
			//$num =	date('j',$startDate);
			if (!in_array(($num % 100),array(11,12,13))){
			  switch ($num % 10) {
				// Handle 1st, 2nd, 3rd
				case 1:  return $num.'st';
				case 2:  return $num.'nd';
				case 3:  return $num.'rd';
			  }
			}
			return $num.'th';
		}
		
    function Sessions(){
        
		$days = $this->input->post('daydiff');
		if($days === 'Today')
                    $days =1;
                if (!empty($this->input->post())) 
		{ 
                   // print_r($_POST);die;
                   
                  
                    if($days==365)
                    {
                        
                        $TotalSession =	$this->newanalytics_model->getSessionDataGraphYear();
                        $newSession =	$this->newanalytics_model->getNewSessionDataGraphYear();                
                    }
                    else
                    {
                        
                        $sqlData = array();
                        
                        if(is_array($days))
                        {
                            $sqlData['startdate'] =$days[0]." 00:00:00";
                            $sqlData['enddate']   = $days[1]." 23:59:59";
                           // print_r($days);die;
                        }
                        else
                        {
                           $dayDiff = $this->getDateIntervel($days);                        
                         
                            $sqlData['startdate'] = $dayDiff['startdate']." 00:00:00";
                            $sqlData['enddate']=   $dayDiff['enddate']." 23:59:59";
                        }

                        $TotalSession =	$this->newanalytics_model->getSessionDataGraph($sqlData);
                        $newSession =	$this->newanalytics_model->getNewSessionDataGraph($sqlData);                
                       
                    }
                    
                  //   print_r($newSession);die;
                    $resultData['graph'] = array();
                    $resultData['grid'] = array();
                    $resultData['total'] = array();
                    
                    $ts = array();
                    $ns = array();
                    $us = array();
                    
                    $labels = array();
                    
                    $total =0;
                    $new = 0;
                    $unique = 0;
                    //print_r($TotalSession);
                    
                    foreach($TotalSession as $key => $value)
                    {            
                            $nkey=$value['date'];
                            $labels[]=$value['date'];
                            if(is_array($newSession)>0 && array_key_exists($key,$newSession) && array_key_exists('newsession',$newSession[$key]))
                            {  
                             $value['newsession']= $newSession[$key]['newsession'];
                            }
                            else
                            {
                                $value['newsession']=0;
                            }

                        //    $resultData['graph'][$nkey] = $value;
                            
                                    
                           $ts[]= $value['totalsession']=($value['totalsession']==NULL && array_key_exists('totalsession',$value))?0:$value['totalsession'];
                           $ns[]= $value['newsession']=($value['newsession']==NULL && array_key_exists('newsession',$value))?0:$value['newsession'];
                           $us[]= $value['uniquesession']=($value['uniquesession']==NULL && array_key_exists('uniquesession',$value))?0:$value['uniquesession'];
                            
                            $gridArray = array($nkey,$value['totalsession'],$value['newsession'],$value['uniquesession']);
                            
                            $resultData['grid'][] = $gridArray;
                          
                             $total +=$value['totalsession'];
                             $unique +=$value['uniquesession'];
                             $new +=$value['newsession'];
                    } 
                    $datasets[] =array(
                        'label'=>'Total Session',
                        'strokeColor'=>'rgba(136,187,200,1)',
                        'pointColor' =>'rgba(136,187,200,1)',
                        'data'=>$ts);
                    $datasets[] =array(
                        'label'=>'New Session',
                        'strokeColor'=>'rgba(237,134,98,1)',
                        'pointColor' =>'rgba(237,134,98,1)',
                        'data'=>$ns);
                    $datasets[] =array(
                        'label'=>'Unique Session',
                        'strokeColor'=>'rgba(190,235,159,0.75)',
                        'pointColor' =>'rgba(190,235,159,0.75)',
                        'data'=>$us);
                        
                            
                    $resultData['graph']['labels']=$labels;
                    $resultData['graph']['datasets']=$datasets;
                    $resultData['total']=array($total,$unique,$new);

                    echo json_encode($resultData,true);
		}
		else 
		{                
		$data['welcome'] = $this;
		$this->show_view('analytics_session',$data);
		}
	}
        
		function Frequency(){
					if(!empty($_POST)){
					$resultData['graph'] = array();
                    $resultData['grid'] = array();
                    $resultData['total'] = array();
					$prepareData = array();
					$days = $this->input->post('daydiff'); 
					if($days =='')
						$days =1;
					if($days === 'Today' || $days === 0)
						$days = 1;
					$sqlData =$dayDiff = $this->getDateIntervel($days);
					$frequencyResult =	$this->newanalytics_model->getFrequenceUser($dayDiff);
					if($dayDiff['startdate'] === $dayDiff['enddate']){
					$temp=array();
					foreach($frequencyResult as $key=>$val)
					 {
						$temp[$val['hr']]['hr'] = $val['hr'];
						$temp[$val['hr']]['totaluser'] = $val['totaluser'];
						$temp[$val['hr']]['percent'] = $val['percent'];
					}
					for($i =0 ;$i<=24;$i++){
						if(isset($temp[$i]) && is_array($temp[$i])){
							//$prepareData[$i][] = $temp[$i]['hr'];
							 $prepareData[$i][] =  sprintf('%02d',($temp[$i]['hr'])).":00";
							$prepareData[$i][] = $temp[$i]['totaluser'];
							$prepareData[$i][] = $temp[$i]['percent'];
						}else{
							$prepareData[$i][] = sprintf('%02d',$i).":00";
							$prepareData[$i][] = 0;
							$prepareData[$i][] = 0;
						}
					}
				}else{	
						$temp = array();
						foreach($frequencyResult as $key=>$val){
							$temp[$val['date']] =$frequencyResult[$key];
						}
		
						$i = 0;
						for($startDate = strtotime($dayDiff['startdate']); $startDate <= strtotime($dayDiff['enddate']); $startDate = strtotime("+1 day", $startDate)) 
						{ $d = date('Y-m-d',$startDate); 
							if(isset($temp[$d]) && is_array($temp[$d])){
									$prepareData[$i][] = $temp[$d]['day'];
									$prepareData[$i][] = $temp[$d]['totaluser'];
									$prepareData[$i][] = $temp[$d]['percent'];
								}else{
									$prepareData[$i][] = date('F d',$startDate);
									$prepareData[$i][] = 0;
									$prepareData[$i][] = 0;
								}
							$i ++;	
						} //$prepareData ;
					}
					$resultData['grid']  = $prepareData;
					 echo json_encode($resultData,true);
					 EXIT;
				}else{
						$data['welcome'] = $this;
						$this->show_view('analytics_frequency',$data);
					}
			}
        
    function Countries(){
		$data['welcome'] = $this;
		$this->show_view('analytics_countries',$data);
	}
        
    function Devices(){
		$data['welcome'] = $this;
		$this->show_view('analytics_device',$data);
	}
        
    function Versions(){
		$data['welcome'] = $this;
		$this->show_view('analytics_version',$data);
	}
        
    function Carrier(){
		$data['welcome'] = $this;
		$this->show_view('analytics_carrier',$data);
	}
        
    function Platforms(){
		$data['welcome'] = $this;
		$this->show_view('analytics_platforms',$data);
	}

}
