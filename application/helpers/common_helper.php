<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
  function time_from_seconds($seconds) { 
        $h = floor($seconds / 3600); 
        $m = floor(($seconds % 3600) / 60); 
        $s = $seconds - ($h * 3600) - ($m * 60); 
        return sprintf('%02d:%02d:%02d', $h, $m, $s); 
	}       
        
  function GetDays($sStartDate, $sEndDate){
      // Firstly, format the provided dates.
      // This function works best with YYYY-MM-DD
      // but other date formats will work thanks
      // to strtotime().
     echo  $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
      echo $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));
  
      // Start the variable off with the start date
      $aDays[] = $sStartDate;
    
      // Set a 'temp' variable, sCurrentDate, with
      // the start date - before beginning the loop
      $sCurrentDate = $sStartDate;
    
      // While the current date is less than the end date
      while($sCurrentDate < $sEndDate){
	// Add a day to the current date
	$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
    
	// Add this new day to the aDays array
	$aDays[] = $sCurrentDate;
      }
    
      // Once the loop has finished, return the
      // array of days.
      return $aDays;
}

function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script
$strDateFrom = date('Y-m-d',strtotime($strDateFrom));
$strDateTo = date('Y-m-d',strtotime($strDateTo));

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

?>