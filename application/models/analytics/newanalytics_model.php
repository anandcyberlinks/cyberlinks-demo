<?php

class Newanalytics_model extends CI_Model{
    	//echo $this->db->last_query();die;
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
	public function getTotalSession($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$query = $this->db->get('app_session');
		return $totalSession = $query->num_rows();
		
    }
	
	public function getTotalSessionDayWise($data= array()){
		
		//echo date("Y-m-d",strtotime($data['startdate']); die;
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			$this->db->select('count(app_session.id) as totaluser,HOUR(app_session.session_start) as hr');
			$this->timeInterval($data,"app_session.session_start");
			$this->timeInterval($data,"app_session.session_start");
                        $this->db->group_by('HOUR(app_session.session_start)'); 
			$this->db->order_by('hr', 'ASC'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			return $this->perHourData($result,$data,'totaluser');
			
		}else{
			$this->db->select("count(id) as totaluser,DATE_FORMAT(app_session.session_start,'%Y-%m-%d') as date",false);
			$this->timeInterval($data,"app_session.session_start");
			$this->timeInterval($data,"app_session.session_start");
                        $this->db->group_by('Day(app_session.session_start)');
			$this->db->order_by('totaluser','desc');
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			return $this->dayWiseData($result,$data,'totaluser');
		}
	 }
	 
	
	public function getTotalUser($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$this->db->distinct();
		$this->db->select('customer_device_id');
		$query = $this->db->get('app_session');
		return $totalUser = $query->num_rows();
	}
	
	
	public function getTotalUserDaysWaise($data= array()){
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			
			$this->timeInterval($data,"app_session.session_start");
			$this->db->select("count(distinct(customer_device_id)) as totaluser,HOUR(app_session.session_start) as hr",false);
			$this->db->group_by('HOUR(app_session.session_start)'); 
			$this->db->order_by('totaluser', 'desc'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			return $this->perHourData($result,$data,'totaluser');
		}else{
		
			$this->timeInterval($data,"app_session.session_start");
			//$this->db->distinct();
			$this->db->select("count(distinct(customer_device_id)) as totaluser,DATE_FORMAT(app_session.session_start,'%Y-%m-%d') as date",false);
			$this->db->group_by('Day(app_session.session_start)'); 
			$this->db->order_by('totaluser', 'desc'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			 
			
			return $this->dayWiseData($result,$data,'totaluser');
		}
		
	}

	
	public function getNewUser($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$this->db->where('status',1);
		$query = $this->db->get('app_session');
		return $newUser = $query->num_rows();
    }
	
	
	public function getNewUserPerDay($data= array()){
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			$this->timeInterval($data,"app_session.session_start");
			$this->db->select("count(distinct(customer_device_id)) as totaluser,HOUR(app_session.session_start) as hr",false);
			$this->db->where('status',1);
			$this->db->group_by('HOUR(app_session.session_start)');
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			//echo $this->db->last_query();die;
			return	$this->perHourData($result,$data,'totaluser');
		}else{
			$this->timeInterval($data,"app_session.session_start");
			$this->db->select("count(distinct(customer_device_id)) as totaluser,DATE_FORMAT(app_session.session_start,'%Y-%m-%d') as date",false);
			$this->db->where('status',1);
			$this->db->group_by('Day(app_session.session_start)'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			//echo $this->db->last_query();die('fff');
			return $this->dayWiseData($result,$data,'totaluser');
		}
	 }
	 
	 public function getReturningUserPerDay($data= array()){
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			$this->timeInterval($data,"app_session.session_start");
			$this->db->select("count(distinct(customer_device_id)) as totaluser,HOUR(app_session.session_start) as hr",false);
			$this->db->where('status',2);
			$this->db->group_by('HOUR(app_session.session_start)');
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			//echo $this->db->last_query();die;
			return	$this->perHourData($result,$data,'totaluser');
		}else{
			$this->timeInterval($data,"app_session.session_start");
			$this->db->select("count(distinct(customer_device_id)) as totaluser,DATE_FORMAT(app_session.session_start,'%Y-%m-%d') as date",false);
			$this->db->where('status',2);
			$this->db->group_by('Day(app_session.session_start)'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			return $this->dayWiseData($result,$data,'totaluser');
		}
	 }
	
	
	public function getPlatform($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$this->db->select('platform, count(customer_device.platform) as topplatform');
		$this->db->from('app_session');
		$this->db->join('customer_device','app_session.customer_device_id = customer_device.id', 'INNER');
		$this->db->group_by('customer_device.platform'); 
		$this->db->order_by('topplatform', 'desc'); 
		$query = $this->db->get();
		RETURN $result = $query->result_array();
		//RETURN $query->result();
		
	}
	
	public function getResolution($data= array()){
	    $this->timeInterval($data,"app_session.session_start");
		$this->db->select('screen_resolution, count(screen_resolution) as topresolution');
		$this->db->from('app_session');
		$this->db->join('customer_device','app_session.customer_device_id = customer_device.id', 'INNER');
		$this->db->group_by('customer_device.screen_resolution'); 
		$this->db->order_by('topresolution', 'desc'); 
		$query = $this->db->get();
		RETURN $query->result_array();
		//RETURN $query->result();
		
	}
	
	public function getCarrier($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$this->db->select('analytics.network_provider, count(analytics.network_provider) as topcarrier');
		$this->db->from('app_session');
		$this->db->join('analytics','app_session.id = analytics.app_session_id', 'INNER');
		$this->db->group_by('analytics.network_provider'); 
		$this->db->order_by('topcarrier', 'desc'); 
		$query = $this->db->get();
		RETURN $query->result_array();
		//RETURN $query->result();
	}
	

	public function getTopUser($data= array()){
		$this->timeInterval($data,"app_session.session_start");
		$this->db->select('app_session.session_start,count(customer_device_id) as totaluser ,HOUR(app_session.session_start) as hr');
		$this->db->from('app_session');
		$this->db->join('customer_device','app_session.customer_device_id = customer_device.id', 'INNER');
		$this->db->group_by('HOUR(app_session.session_start)'); 
		$this->db->order_by('totaluser', 'desc'); 
		$query = $this->db->get();
		RETURN $query->result_array();
		//RETURN $query->result();
	}

	public function getCountryUser($data= array()){
		$this->db->select('count(customer_device_id) as countryUser,country');
		$this->db->where('country !="null" AND country !=""');
		$this->db->group_by('country'); 
		$this->db->order_by('countryUser', 'desc'); 
		$query = $this->db->get('analytics');
		RETURN $query->result_array();
		//RETURN $query->result();
	}
	
	public function timeInterval($data =array(),$column){
		$startdate =$data['startdate'];
		$enddate = $data['enddate'];
		return $this->db->where("$column BETWEEN '$startdate' AND '$enddate'");
	}
	
   public function getCountry(){
		$this->db->select('code,name')    ;
        $this->db->from('countries');
        $this->db->order_by('name asc');
        $query = $this->db->get();
		RETURN $query->result_array();
        //return $query->result();
    } 
	

	public function perHourData($perHourData= array(),$dateInterval=array(),$columName){
		$perHourArray = array();
		$temp =array();
		foreach($perHourData as $val){
			$temp[$val['hr']] = $val[$columName];
		}
		$hr = 24;
		for($hr = 1; $hr <=24; $hr++){
			IF(ISSET($temp[$hr]))
			{	$perHourArray[$hr][$columName]  = $temp[$hr];
				$perHourArray[$hr]['hr']  = $hr;
			}ELSE{
				$perHourArray[$hr][$columName]  = 0;
				$perHourArray[$hr]['hr']  = $hr;
			}
		}
		
		/* echo "<pre>";
		print_r($perHourArray);
		die; */
		return $perHourArray;
	}	
	
	public function dayWiseData($queryData= array(),$dateInterval=array(),$columName){
            //echo "<pre>";
           // print_r($queryData);
            //die;
		$startDate = $dateInterval['startdate'];
		$endDate =  $dateInterval['enddate'];
		$date 	=	'date';
		//$columName 	=	'totaluser';
		$perDayData = array();
		$temp = array();
		foreach($queryData as $val){
			$temp[$val[$date]] = $val[$columName];
		}
		
		
		$dayWiseData = array();
		
		for($startDate = strtotime($startDate); $startDate <= strtotime($endDate); $startDate = strtotime("+1 day", $startDate)) 
		{	$d = date('Y-m-d',$startDate); 
			if(isset($temp[$d])){
					$perDayData[$d][$date] =$d ; 
					$perDayData[$d]['month'] =$this->addOrdinalNumberSuffix($startDate);
					$perDayData[$d][$columName] =@$temp[$d];
				}else{
					
					$perDayData[$d][$date] =$d ; 
					//$perDayData[$d]['month'] =date('F d',$startDate);
					$perDayData[$d]['month'] =$this->addOrdinalNumberSuffix($startDate);
					$perDayData[$d][$columName] =0;
				}				
		}
		
	//	echo '<pre>';
	//	print_r($perDayData); die('xx');
		
		return  $perDayData ;
	}
	
	
	function addOrdinalNumberSuffix($startDate) {
		//echo date('F d',$startDate);
		 $num =	date('j',$startDate);
		if (!in_array(($num % 100),array(11,12,13))){
		  switch ($num % 10) {
			// Handle 1st, 2nd, 3rd
			case 1:  return date('F',$startDate).' '.$num.'st';
			case 2:  return date('F',$startDate).' '.$num.'nd';
			case 3:  return date('F',$startDate).' '.$num.'rd';
		  }
		}
		return date('F',$startDate).' '.$num.'th';
	}
        
    public function getSessionDataGraph($data= array()){
                         
		//echo date("Y-m-d",strtotime($data['startdate']);
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			$this->db->select('count(app_session.id) as totalsession,count(distinct app_session.customer_device_id) as uniquesession, HOUR(app_session.session_start) as hr');
			$this->timeInterval($data,"app_session.session_start");
                        $this->db->group_by('HOUR(app_session.session_start)'); 
			$this->db->order_by('hr', 'ASC'); 
			$query = $this->db->get('app_session');
			$result = $query->result_array();
			return $result;
			
		}else{
			$this->db->select("count(id) as totalsession,count(distinct app_session.customer_device_id) as uniquesession,DATE_FORMAT(app_session.session_start,'%d %M') as date",false);
			$this->timeInterval($data,"app_session.session_start");
                        $this->db->group_by('Day(app_session.session_start)');
			$this->db->order_by('date', 'ASC'); 
			$query = $this->db->get('app_session');
			return $result = $query->result_array();
                        //echo "dfdf";
                        // echo $this->db->last_query();
                        //print_r($result);die;
			//return $this->dayWiseData($result,$data,'totaluser');
		}
	 }    
         public function getNewSessionDataGraph($data= array()){
		//echo date("Y-m-d",strtotime($data['startdate']);
		if(date("Y-m-d",strtotime($data['startdate'])) === date("Y-m-d",strtotime($data['enddate']))){
			$this->db->select('count(app_session.id) as newsession, HOUR(app_session.session_start) as hr');
			$this->db->where('session_use',1);
                        $this->timeInterval($data,"app_session.session_start");
                        $this->db->group_by('HOUR(app_session.session_start)'); 
			$this->db->order_by('hr', 'ASC'); 
			$query = $this->db->get('app_session');
			return $result = $query->result_array();
			//return $this->perHourData($result,$data,'totaluser');
			
		}else{
			$this->db->select("count(id) as newsession,DATE_FORMAT(app_session.session_start,'%d %M') as date",false);
			$this->timeInterval($data,"app_session.session_start");
                        $this->db->where('session_use',1);
                        $this->db->group_by('Day(app_session.session_start)');
			$this->db->order_by('date', 'ASC'); 
			$query = $this->db->get('app_session');
			return $result = $query->result_array();
			//return $this->dayWiseData($result,$data,'totaluser');
		}
	 } 
         
        public function getNewSessionDataGraphYear()
          {

                  $this->db->select("count(id) as newsession,DATE_FORMAT(app_session.session_start,'%M') as date",false);
                  //$this->timeInterval($data,"app_session.session_start");
                  $this->db->where("YEAR(session_start)","YEAR(CURDATE())", false);
                  $this->db->group_by('MONTH(app_session.session_start)');
                  $this->db->order_by('date', 'ASC'); 
                  $query = $this->db->get('app_session');
                  return $result = $query->result_array();
                  //return $this->dayWiseData($result,$data,'totaluser');	
           } 
                 
         public function getSessionDataGraphYear()
         {
			$this->db->select("count(id) as totalsession,count(distinct app_session.customer_device_id) as uniquesession,DATE_FORMAT(app_session.session_start,'%M') as date",false);
			 $this->db->where("YEAR(session_start)","YEAR(CURDATE())", false);
                        $this->db->group_by('MONTH(app_session.session_start)');
			$this->db->order_by('date', 'ASC'); 
			$query = $this->db->get('app_session');
			return $result = $query->result_array();
                      
	 }
}




?>