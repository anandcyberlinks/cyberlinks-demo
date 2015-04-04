<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('bucket',		           'newsnation1');
define('amazonFileUrl',		'http://'.bucket.'.s3.amazonaws.com/videos/');



/*
|--------------------------------------------------------------------------
| File Paths(upload/play) for server(local/s3)
|--------------------------------------------------------------------------
|
| 'bucket'		            = bucket name for amazon s3 server 
| 'baseurl'		            = base url
| 'serverVideoRelPath'		    = directory path to save video 
| 'serverImageRelPath'		    = directory path to save image 
| 'REAL_PATH'	                    = directory path 
| 'THUMB_SMALL_PATH'	            = directory path(relative) to save image(small) 
| 'THUMB_MEDIUM_PATH'		    = directory path(relative) to save image(medium) 
| 'THUMB_LARGE_PATH'	            = directory path(relative) to save image(large) 
| 'PER_PAGE'		            = records per page
| 'PROFILEPIC_PATH'		    = directory path(relative) to save image(profile) 
|
*/

$dir_path = getcwd();
$path_nw = str_replace('\\', '/', $dir_path);
if(isset($_SERVER['HTTP_HOST']))
{
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
    $base_url .= '://'. $_SERVER['HTTP_HOST'];
} else {
    $base_url = 'http://localhost/';
}
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('baseurl',		            $base_url);

if(isset($config['amazons3'])) {
    define('serverurl',	                'http://'.bucket.'.s3.amazonaws.com/');
    define('serverDir',                 'videos/');
    define('serverVideoRelPath',	'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('serverLogoRelPath',          'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('serverImageRelPath',	'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('REAL_PATH',		        '');
    define('THUMB_SMALL_PATH',          'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('THUMB_MEDIUM_PATH',         'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('THUMB_LARGE_PATH',          'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('PROFILEPIC_PATH',           'http://'.bucket.'.s3.amazonaws.com/videos/');
    define('CATEGORY_PATH',           'assets/upload/category/');
    define('CATEGORY_SMALL_PATH',           'assets/upload/category/small/');
    define('CATEGORY_MEDIUM_PATH',           'assets/upload/category/medium/');
    define('CATEGORY_LARGE_PATH',           'assets/upload/category/large/');

} else {
    define('serverurl',	                baseurl);
    define('serverDir',                 'videos/');
    define('serverVideoRelPath',	'assets/upload/video/');
    define('serverAudioRelPath',	'assets/upload/audio/');
    define('serverLogoRelPath',          'assets/upload/logo/');
    define('serverImageRelPath',	'assets/upload/thumbs/');
    define('REAL_PATH',		            $path_nw.'/');
    define('THUMB_SMALL_PATH',          'assets/upload/thumbs/small/');
    define('THUMB_MEDIUM_PATH',         'assets/upload/thumbs/medium/');
    define('THUMB_LARGE_PATH',          'assets/upload/thumbs/large/');
    define('PROFILEPIC_PATH',           'assets/upload/profilepic/');
    define('CATEGORY_PATH',           'assets/upload/category/');
    define('CATEGORY_SMALL_PATH',           'assets/upload/category/small/');
    define('CATEGORY_MEDIUM_PATH',           'assets/upload/category/medium/');
    define('CATEGORY_LARGE_PATH',           'assets/upload/category/large/');
    define('serverAdsRelPath',	'assets/upload/ads/');
}

define('PER_PAGE',10);

//--- Video Upload path --/
define('VIDEO_UPLOAD_PATH','assets/upload/video/');


//--- Category image path --/

$categorydimensions['small'] =  array('width'=>'320','height'=>'140');
$categorydimensions['medium'] =  array('width'=>'480','height'=>'215');
$categorydimensions['large'] =  array('width'=>'720','height'=>'320');

define('CATEGORY_DIMENSION', serialize($categorydimensions));

//--- Thumb image path --/

$thumbdimensions['small'] =  array('width'=>'320','height'=>'140');
$thumbdimensions['medium'] =  array('width'=>'480','height'=>'215');
$thumbdimensions['large'] =  array('width'=>'720','height'=>'320');

define('THUMB_DIMENSION', serialize($thumbdimensions));

//--- Splash Screen image path --/
define('SPLASH_SCREEN_PATH','assets/upload/splash/');

$dimensions['large'] =  array('width'=>'640','height'=>'1140');
$dimensions['medium'] =  array('width'=>'420','height'=>'750');
$dimensions['small'] =  array('width'=>'280','height'=>'500');

define('SPLASH_SCREEN_DIMENSION', serialize($dimensions));

define('VAST_PATH','./assets/upload/ads/vast/');
define('IMG_PATH','./assets/img/');
define('CSV_PATH','./assets/ads/csv/');
define('MAX_CUEPOINTS_FIELDS',10);  // aDD NEW CUE POIND FIELD LIMIT

//----------Campaign url -----------//
define('CAMPAIGN_URL', 'http://54.179.170.143/vast/getvast.php');
define('CAMPAIGN_REVENUE', 'http://54.179.170.143/vast/getDetail.php?akey=9eac320f70');
///----------------------//
/* End of file constants.php */
/* Location: ./application/config/constants.php */