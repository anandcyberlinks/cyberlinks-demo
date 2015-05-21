<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

//define('bucket',		           'newsnation1');
define('bucket',		           'globalpunjab');
define('amazonFileUrl',		'http://'.bucket.'.s3.amazonaws.com/images/');


/*
  |--------------------------------------------------------------------------
  | File Paths(upload/play) for server(local/s3)
  |--------------------------------------------------------------------------
  |
  | 'bucket'		            = bucket name for amazon s3 server
  | 'baseurl'		            = base url
  | 'serverVideoRelPath'            = directory path to save video
  | 'serverImageRelPath'            = directory path to save image
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
if (isset($_SERVER['HTTP_HOST'])) {
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
    $base_url .= '://' . $_SERVER['HTTP_HOST'];
} else {
    $base_url = 'http://localhost/';
}
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
define('baseurl', $base_url);

if (isset($config['amazons3'])) {
    define('serverurl', 'http://' . bucket . '.s3.amazonaws.com/');
    define('serverDir', 'videos/');
    define('serverVideoRelPath', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('serverLogoRelPath', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('serverImageRelPath', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('REAL_PATH', '');
    define('THUMB_SMALL_PATH', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('THUMB_MEDIUM_PATH', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('THUMB_LARGE_PATH', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('PROFILEPIC_PATH', 'http://' . bucket . '.s3.amazonaws.com/videos/');
    define('CATEGORY_PATH', 'assets/upload/category/');
    define('CATEGORY_SMALL_PATH', 'assets/upload/category/small/');
    define('CATEGORY_MEDIUM_PATH', 'assets/upload/category/medium/');
    define('CATEGORY_LARGE_PATH', 'assets/upload/category/large/');
    define('APPLICATIONS_PATH', 'assets/upload/applications/');
    define('APPLICATIONS_SMALL_PATH', 'assets/upload/applications/small/');
    define('APPLICATIONS_MEDIUM_PATH', 'assets/upload/applications/medium/');
    define('APPLICATIONS_LARGE_PATH', 'assets/upload/applications/large/');
} else {
    define('serverurl', baseurl);
    define('serverDir', 'videos/');
    define('serverVideoRelPath', 'assets/upload/video/');
    define('serverAudioRelPath', 'assets/upload/audio/');
    define('serverLogoRelPath', 'assets/upload/logo/');
    define('serverImageRelPath', 'assets/upload/thumbs/');
    define('REAL_PATH', $path_nw . '/');
    define('THUMB_SMALL_PATH', 'assets/upload/thumbs/small/');
    define('THUMB_MEDIUM_PATH', 'assets/upload/thumbs/medium/');
    define('THUMB_LARGE_PATH', 'assets/upload/thumbs/large/');
    define('PROFILEPIC_PATH', 'assets/upload/profilepic/');
    define('EVENTPIC_PATH', 'assets/upload/eventpic/');
    define('CATEGORY_PATH', 'assets/upload/category/');
    define('CATEGORY_SMALL_PATH', 'assets/upload/category/small/');
    define('CATEGORY_MEDIUM_PATH', 'assets/upload/category/medium/');
    define('CATEGORY_LARGE_PATH', 'assets/upload/category/large/');
    define('APPLICATIONS_PATH', 'assets/upload/applications/');
    define('APPLICATIONS_SMALL_PATH', 'assets/upload/applications/small/');
    define('APPLICATIONS_MEDIUM_PATH', 'assets/upload/applications/medium/');
    define('APPLICATIONS_LARGE_PATH', 'assets/upload/applications/large/');
    define('serverAdsRelPath', 'assets/upload/ads/');
}

define('PER_PAGE', 10);

//--- Video Upload path --/
define('VIDEO_UPLOAD_PATH', 'assets/upload/video/');


//--- Category image path --/

$categorydimensions['small'] = array('width' => '320', 'height' => '140');
$categorydimensions['medium'] = array('width' => '480', 'height' => '215');
$categorydimensions['large'] = array('width' => '720', 'height' => '320');

define('CATEGORY_DIMENSION', serialize($categorydimensions));

//--- Thumb image path --/

$thumbdimensions['small'] = array('width' => '320', 'height' => '140');
$thumbdimensions['medium'] = array('width' => '480', 'height' => '215');
$thumbdimensions['large'] = array('width' => '720', 'height' => '320');

define('THUMB_DIMENSION', serialize($thumbdimensions));

//--- Splash Screen image path --/
define('SPLASH_SCREEN_PATH', 'assets/upload/splash/');

$dimensions['large'] = array('width' => '640', 'height' => '1140');
$dimensions['medium'] = array('width' => '420', 'height' => '750');
$dimensions['small'] = array('width' => '280', 'height' => '500');

define('SPLASH_SCREEN_DIMENSION', serialize($dimensions));

define('VAST_PATH', './assets/upload/ads/vast/');
define('IMG_PATH', './assets/img/');
define('CSV_PATH', './assets/ads/csv/');
define('MAX_CUEPOINTS_FIELDS', 10);  // aDD NEW CUE POIND FIELD LIMIT
//----------Campaign url -----------//
define('CAMPAIGN_URL', 'http://54.179.170.143/vast/getvast.php');
define('CAMPAIGN_REVENUE', 'http://54.179.170.143/vast/getDetail.php?akey=9eac320f70');
///----------------------//

//------ Skin Templates Path ---//
define('SKINS_FOLDER', './assets/upload/skins/');
//-----------------------------//


//---- Push Notification ---//
define('APNS_PASSPHRASE','9540105334');
define('APNS_CERT','assets/pushnotification/ckCyberlinks.pem');
define('GOOGLE_API_KEY','AIzaSyAyyorAM4icS2agPLwmEWGJivCiXVfWvHQ');

$timezones = array(
    'Pacific/Midway'       => "(GMT-11:00) Midway Island",
    'US/Samoa'             => "(GMT-11:00) Samoa",
    'US/Hawaii'            => "(GMT-10:00) Hawaii",
    'US/Alaska'            => "(GMT-09:00) Alaska",
    'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
    'America/Tijuana'      => "(GMT-08:00) Tijuana",
    'US/Arizona'           => "(GMT-07:00) Arizona",
    'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
    'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
    'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
    'America/Mexico_City'  => "(GMT-06:00) Mexico City",
    'America/Monterrey'    => "(GMT-06:00) Monterrey",
    'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
    'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
    'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
    'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
    'America/Bogota'       => "(GMT-05:00) Bogota",
    'America/Lima'         => "(GMT-05:00) Lima",
    'America/Caracas'      => "(GMT-04:30) Caracas",
    'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
    'America/La_Paz'       => "(GMT-04:00) La Paz",
    'America/Santiago'     => "(GMT-04:00) Santiago",
    'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
    'Greenland'            => "(GMT-03:00) Greenland",
    'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
    'Atlantic/Azores'      => "(GMT-01:00) Azores",
    'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
    'Africa/Casablanca'    => "(GMT) Casablanca",
    'Europe/Dublin'        => "(GMT) Dublin",
    'Europe/Lisbon'        => "(GMT) Lisbon",
    'Europe/London'        => "(GMT) London",
    'Africa/Monrovia'      => "(GMT) Monrovia",
    'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
    'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
    'Europe/Berlin'        => "(GMT+01:00) Berlin",
    'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
    'Europe/Brussels'      => "(GMT+01:00) Brussels",
    'Europe/Budapest'      => "(GMT+01:00) Budapest",
    'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
    'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
    'Europe/Madrid'        => "(GMT+01:00) Madrid",
    'Europe/Paris'         => "(GMT+01:00) Paris",
    'Europe/Prague'        => "(GMT+01:00) Prague",
    'Europe/Rome'          => "(GMT+01:00) Rome",
    'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
    'Europe/Skopje'        => "(GMT+01:00) Skopje",
    'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
    'Europe/Vienna'        => "(GMT+01:00) Vienna",
    'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
    'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
    'Europe/Athens'        => "(GMT+02:00) Athens",
    'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
    'Africa/Cairo'         => "(GMT+02:00) Cairo",
    'Africa/Harare'        => "(GMT+02:00) Harare",
    'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
    'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
    'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
    'Europe/Kiev'          => "(GMT+02:00) Kyiv",
    'Europe/Minsk'         => "(GMT+02:00) Minsk",
    'Europe/Riga'          => "(GMT+02:00) Riga",
    'Europe/Sofia'         => "(GMT+02:00) Sofia",
    'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
    'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
    'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
    'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
    'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
    'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
    'Europe/Moscow'        => "(GMT+03:00) Moscow",
    'Asia/Tehran'          => "(GMT+03:30) Tehran",
    'Asia/Baku'            => "(GMT+04:00) Baku",
    'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
    'Asia/Muscat'          => "(GMT+04:00) Muscat",
    'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
    'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
    'Asia/Kabul'           => "(GMT+04:30) Kabul",
    'Asia/Karachi'         => "(GMT+05:00) Karachi",
    'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
    'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
    'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
    'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
    'Asia/Almaty'          => "(GMT+06:00) Almaty",
    'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
    'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
    'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
    'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
    'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
    'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
    'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
    'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
    'Australia/Perth'      => "(GMT+08:00) Perth",
    'Asia/Singapore'       => "(GMT+08:00) Singapore",
    'Asia/Taipei'          => "(GMT+08:00) Taipei",
    'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
    'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
    'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
    'Asia/Seoul'           => "(GMT+09:00) Seoul",
    'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
    'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
    'Australia/Darwin'     => "(GMT+09:30) Darwin",
    'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
    'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
    'Australia/Canberra'   => "(GMT+10:00) Canberra",
    'Pacific/Guam'         => "(GMT+10:00) Guam",
    'Australia/Hobart'     => "(GMT+10:00) Hobart",
    'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
    'Australia/Sydney'     => "(GMT+10:00) Sydney",
    'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
    'Asia/Magadan'         => "(GMT+12:00) Magadan",
    'Pacific/Auckland'     => "(GMT+12:00) Auckland",
    'Pacific/Fiji'         => "(GMT+12:00) Fiji",
);
define('TIME_ZONE_LIST',serialize($timezones));
define('SERVER_TIME_ZONE', 'IST');

//-------------------------//

/* End of file constants.php */
/* Location: ./application/config/consta
nts.php */