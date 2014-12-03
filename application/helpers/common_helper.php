<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
  function time_from_seconds($seconds) { 
        $h = floor($seconds / 3600); 
        $m = floor(($seconds % 3600) / 60); 
        $s = $seconds - ($h * 3600) - ($m * 60); 
        return sprintf('%02d:%02d:%02d', $h, $m, $s); 
	}       
        
?>