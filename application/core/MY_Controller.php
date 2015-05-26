<?php

ini_set('display_errors', 1);

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(APPPATH . 'libraries/getid3/getid3.php');
require APPPATH . 'libraries/aws.phar';

use Aws\S3\S3Client;
use Aws\Common\Aws;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('image_lib');
        $this->amazons3 = $this->session->userdata('upload_on');
        $sess = $this->session->all_userdata();
        if (!(isset($sess['lan']))) {
            $lang = 'eng';
            $this->session->set_userdata('lan', $lang);
        }
        $dir_path = getcwd();
        $path_nw = str_replace('\\', '/', $dir_path);
        if ($this->amazons3) {
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
            define('EVENT_URL', 'rtsp://belive.mobi:1935/belive/');
            define('EVENT_URL_WEB', 'rtmp://belive.mobi:1935/belive/');
            define('EVENT_URL_MOBILE', 'http://belive.mobi:1935/belive/');
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
    }

    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function get_meta_tags($html) {
        preg_match_all("/<title>(.*)<\/title>/", $html, $title);
        preg_match_all("/<meta name=\"description\" content=\"(.*)\">/i", $html, $description);
        preg_match_all("/<meta name=\"keywords\" content=\"(.*)\">/i", $html, $keywords);
        $res["content"] = @array("title" => $title[1][0], "descritpion" => $description[1][0], "keywords" => $keywords[1][0]);
        return $res;
    }

    function getMonths($month, $count = 1) {
        $now = new DateTime();
        $start = DateTime::createFromFormat("F Y", $month);
        $list = array();
        $interval = new DateInterval(sprintf("P%dM", $count));
        while ($start <= $now) {
            $list[$start->format("Y")][] = $start->format("F");
            $start->add($interval);
        }
        return $list;
    }

//print_r(getMonths("August 2012"));

    function get_youtube($url) {
        $id = $this->getYoutubeIdFromUrl($url);
        $html = $this->get_data($url);
        $data = $this->get_meta_tags($html);

        $youtube = sprintf('http://gdata.youtube.com/feeds/api/videos/%s?v=2&alt=json', $id);
        //$youtube = "http://www.youtube.com/oembed?url=". $url ."&format=json"; 
        $curl = curl_init($youtube);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        $data['id'] = $id;
        $data['detail'] = (array) json_decode($return);
        return $data;
    }

    function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $qs);
            if (isset($qs['v'])) {
                return $qs['v'];
            } else if ($qs['vi']) {
                return $qs['vi'];
            }
        }
        if (isset($parts['path'])) {
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path) - 1];
        }
        return false;
    }

    function checkpermission($role, $permission) {
        $this->db->where(array('role_id' => $role, 'permission' => $permission));
        $query = $this->db->get('permission');
        if (count($query->result()) == '0') {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function getimage() {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $tmp->id;
        $this->db->select('relative_path');
        $this->db->where('uid', $id);
        $this->db->where('type', 'logo');
        $query = $this->db->get('files');
        //echo $this->db->last_query();
        $result = $query->result();
        if (count($result) != '0') {
            return base_url() . $result[0]->relative_path;
        } else {
            return base_url() . 'assets/img/avatar3.png';
        }
    }

    public function show_view($view, $data = '') {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = $tmp->id;
        if ($id != '') {
            //$this->load->view('header', $data);
            if (strtolower($s[0]->role) == 'advertiser') {
                $this->load->view('header_ad', $data);
                //  $this->load->view('sidebar_ads', $data);
            } else {
                $this->load->view('header', $data);
                //   $this->load->view('sidebar', $data);
            }
            $this->load->view($view, $data);
            $this->load->view('footer', $data);
        } else {
            $this->session->set_flashdata('msg', 'Please Login');
            redirect(base_url() . 'layout');
        }
    }

### package ####

    function countVideo($package_id) {
        $this->db->where('package_id', $package_id);
        $query = $this->db->get('package_video');
        return count($query->result());
    }

    function check($cid, $pk_id) {
        $this->db->where('content_id', $cid);
        $this->db->where('package_id', $pk_id);
        $query = $this->db->get('package_video');
        // echo $this->db->last_query();
        return count($query->result());
    }

    public function show_video_view($view, $data = '') {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = $tmp->id;
        if ($id != '') {
            $this->load->view('header', $data);
            //$this->load->view('sidebar', $data);
            $this->load->view('videoEditHeader', $data);
            $this->load->view($view, $data);
            $this->load->view('footer', $data);
        } else {
            $this->session->set_flashdata('msg', 'Please Login');
            redirect(base_url());
        }
    }

    public function show_ads_view($view, $data = '') {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = $tmp->id;
        if ($id != '') {
            if (strtolower($s[0]->role) == 'advertiser') {
                //  $this->load->view('sidebar_ads', $data);
                $this->load->view('header_ad', $data);
            } else {
                $this->load->view('header', $data);
                //  $this->load->view('sidebar', $data);
            }
            $this->load->view('ads/adsEditHeader', $data);
            $this->load->view($view, $data);
            $this->load->view('footer', $data);
        } else {
            $this->session->set_flashdata('msg', 'Please Login');
            redirect(base_url());
        }
    }

    function loadPo($word) {
        $sess = $this->session->all_userdata();
        if (isset($sess['lan'])) {
            $filename = 'language/' . $sess['lan'] . '.po';
        } else {
            $data['lan'] = 'language/eng.po';
        }
        if (!$file = fopen($filename, 'r')) {
            return false;
        }
        $type = 0;
        $translations = array();
        $translationKey = '';
        $plural = 0;
        $header = '';
        do {
            $line = trim(fgets($file));
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (preg_match("/msgid[[:space:]]+\"(.+)\"$/i", $line, $regs)) {
                $type = 1;
                $translationKey = stripcslashes($regs[1]);
            } elseif (preg_match("/msgid[[:space:]]+\"\"$/i", $line, $regs)) {
                $type = 2;
                $translationKey = '';
            } elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && ($type == 1 || $type == 2 || $type == 3)) {
                $type = 3;
                $translationKey .= stripcslashes($regs[1]);
            } elseif (preg_match("/msgstr[[:space:]]+\"(.+)\"$/i", $line, $regs) && ($type == 1 || $type == 3) && $translationKey) {
                $translations[$translationKey] = stripcslashes($regs[1]);
                $type = 4;
            } elseif (preg_match("/msgstr[[:space:]]+\"\"$/i", $line, $regs) && ($type == 1 || $type == 3) && $translationKey) {
                $type = 4;
                $translations[$translationKey] = '';
            } elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 4 && $translationKey) {
                $translations[$translationKey] .= stripcslashes($regs[1]);
            } elseif (preg_match("/msgid_plural[[:space:]]+\".*\"$/i", $line, $regs)) {
                $type = 6;
            } elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 6 && $translationKey) {
                $type = 6;
            } elseif (preg_match("/msgstr\[(\d+)\][[:space:]]+\"(.+)\"$/i", $line, $regs) && ($type == 6 || $type == 7) && $translationKey) {
                $plural = $regs[1];
                $translations[$translationKey][$plural] = stripcslashes($regs[2]);
                $type = 7;
            } elseif (preg_match("/msgstr\[(\d+)\][[:space:]]+\"\"$/i", $line, $regs) && ($type == 6 || $type == 7) && $translationKey) {
                $plural = $regs[1];
                $translations[$translationKey][$plural] = '';
                $type = 7;
            } elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 7 && $translationKey) {
                $translations[$translationKey][$plural] .= stripcslashes($regs[1]);
            } elseif (preg_match("/msgstr[[:space:]]+\"(.+)\"$/i", $line, $regs) && $type == 2 && !$translationKey) {
                $header .= stripcslashes($regs[1]);
                $type = 5;
            } elseif (preg_match("/msgstr[[:space:]]+\"\"$/i", $line, $regs) && !$translationKey) {
                $header = '';
                $type = 5;
            } elseif (preg_match("/^\"(.*)\"$/i", $line, $regs) && $type == 5) {
                $header .= stripcslashes($regs[1]);
            } else {
                unset($translations[$translationKey]);
                $type = 0;
                $translationKey = '';
                $plural = 0;
            }
        } while (!feof($file));
        fclose($file);
        $merge[''] = $header;
        $translations = array_merge($merge, $translations);

        if (array_key_exists($word, $translations)) {
            return $translations[$word];
        } else {
            return $word;
        }
    }

    /**
     * Function For LOg
     * $user = log file name
     * $msg = message
     */
    function log($user, $msg) {
        $t = $_SERVER['REQUEST_TIME'];
        $time = (date("Y-m-d h:i:sa", $t));
        $ip = $_SERVER['SERVER_ADDR'];
        $file = "log/$user.log";
        $fileHandle = fopen($file, 'a'); // Note that the mode has changed
        $data = "LOG   =>> " . $time . "||" . $ip . "||" . $msg . "\n"; // set data we will be writing
        fwrite($fileHandle, $data); // write data to file 
        fclose($fileHandle); // close the file since we're done
    }

    /**
     * Function For Debug
     * $user = log file name
     * $msg = message
     */
    function debug($user, $msg) {
        $t = $_SERVER['REQUEST_TIME'];
        $time = (date("Y-m-d h:i:sa", $t));
        $ip = $_SERVER['SERVER_ADDR'];
        $file = "log/$user.log";
        $fileHandle = fopen($file, 'a'); // Note that the mode has changed
        $data = "DEBUG =>> " . $time . "||" . $ip . "||" . $msg . "\n"; // set data we will be writing
        fwrite($fileHandle, $data); // write data to file 
        fclose($fileHandle); // close the file since we're done
    }

    /**
     * Function For Error
     * $user = log file name
     * $msg = message
     */
    function error($filename, $msg) {
        $t = $_SERVER['REQUEST_TIME'];
        $time = (date("Y-m-d h:i:sa", $t));
        $ip = $_SERVER['SERVER_ADDR'];
        $file = "log/$filename.log";
        $fileHandle = fopen($file, 'a'); // Note that the mode has changed
        $data = "ERROR =>> " . $time . "||" . $ip . "||" . $msg . "\n"; // set data we will be writing
        fwrite($fileHandle, $data); // write data to file 
        fclose($fileHandle); // close the file since we're done
    }

    function sendmail($to, $subject, $body, $attachment = '') {

        $this->load->library('PHPMailer/phpmailer');
        $mail = new PHPMailer();
        //$mail->isSMTP();                                      // Set mailer to use SMTP
        //$mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        //$mail->SMTPAuth = true;                               // Enable SMTP authentication
        //$mail->Username = 'cyberlinkslive@gmail.com';      // SMTP username
        //$mail->Password = 'cYBERLINKS1!';                         // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->From = 'Admin@cyberlinks.co.in';
        $mail->FromName = 'Admin Cyberlinks';
        $mail->addAddress($to);    // Add a recipient
        //$mail->addAddress('pavan.prajapati@cyberlinks.in', 'Pawan PAAAArjapti');     // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');                       // Reply To.........
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        if (isset($attachment) && $attachment != '') {
            $mail->addAttachment($attachment);
            $mail->addAttachment('/tmp/' . $attachment, 'new.jpg'); // Add attachments
        }
        // echopre($attachment);
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = 'Success';
        return $mail->send();
        // echopre($mail);
    }

    /*     * ******* function used to get video file size using getid3 ibrary ********* */

    function getVideoFileSize($fileName, $path) {
        $this->load->library('getid3/getid3');
        $getID3 = new GetId3;
        $ThisFileInfo = $getID3->analyze($path . $fileName);

        return $ThisFileInfo;
    }

    /*     * ******* UPLOAD SECTION STARTS(Edited by rekha) ******** */

    /*
      /----------------------------------------------------------------------------------
      / 	File Upload
      /----------------------------------------------------------------------------------
      /
      / 	$incoming_tmp                   = as temp path of file to be uploaded
      / 	$incoming_original              = original filename
      / 	$content_type                   = content type
      /
     */

    function _upload($tmpFilePath, $fileNameUnique, $type) {
        $fileInfo = $this->getFileInfo($tmpFilePath);
        $mimeType = $fileInfo['mime_type'];
        list($mimeTypeN, $binary) = explode("/", $mimeType);
        //echo $this->amazons3; die;
        if ($this->amazons3) {
            $bucket = bucket;
            // Create a `Aws` object using a configuration file
            $aws = Aws::factory(APPPATH . 'config/amazoneS3.php');
            // Get the client from the service locator by namespace
            $client = $aws->get('s3');
            if (!$client->doesBucketExist($bucket)) {
                $client->createBucket(array('Bucket' => $bucket, 'ACL' => 'public-read'));
            }
            $uploader = UploadBuilder::newInstance()
                    ->setClient($client)
                    ->setSource($tmpFilePath)
                    ->setBucket($bucket)
                    ->setKey('videos/'.$fileNameUnique)
                    ->setOption('ACL', 'public-read')
                    ->setOption('ContentType', $mimeType)
                    ->build();
            try {
                $uploader->upload();
                return true;
            } catch (MultipartUploadException $e) {
                $uploader->abort();
                return false;
            }
        } else {
            if ($mimeTypeN == 'video') {
                $result = $this->upload_move_files($tmpFilePath, $fileNameUnique, $type);
                return $result;
            } else {
                $result = $this->upload_move_files($tmpFilePath, $fileNameUnique, $type);
                return $result;
            }
        }
    }

    /*
      /------------------------------------------------------------------------------------
      /   function to move a file from one location(temp) to another location
      /------------------------------------------------------------------------------------
      /
      /
      / 	$fileTempPath 	            = temporary path of a file
      / 	$fileUniqName  	            = unique name of file
      / 	$fileUploadPath             = destination path where file have to be uploaded
      / 	$thumb 		            = this is to specify whether we have to create thumbnail size or not(Image file)
      /
      /
     */

    public function upload_move_files($fileTempPath, $fileUniqName, $type) {
        switch ($type) {
            case 'thumb':
                $fileUploadPath = REAL_PATH . serverImageRelPath;
                $dimensions = unserialize(THUMB_DIMENSION);
                $pathSmall = REAL_PATH . THUMB_SMALL_PATH;
                $pathMedium = REAL_PATH . THUMB_MEDIUM_PATH;
                $pathLarge = REAL_PATH . THUMB_LARGE_PATH;
                break;
            case 'splash':
                $fileUploadPath = REAL_PATH . SPLASH_SCREEN_PATH;
                $dimensions = unserialize(SPLASH_SCREEN_DIMENSION);
                $pathSmall = REAL_PATH . SPLASH_SCREEN_PATH;
                $pathMedium = REAL_PATH . SPLASH_SCREEN_PATH;
                $pathLarge = REAL_PATH . SPLASH_SCREEN_PATH;
                break;
            case 'category':
                $fileUploadPath = REAL_PATH . CATEGORY_PATH;
                $dimensions = unserialize(CATEGORY_DIMENSION);
                $pathSmall = REAL_PATH . CATEGORY_SMALL_PATH;
                $pathMedium = REAL_PATH . CATEGORY_MEDIUM_PATH;
                $pathLarge = REAL_PATH . CATEGORY_LARGE_PATH;
                break;
            case 'applications':
                $fileUploadPath = REAL_PATH . APPLICATIONS_PATH;
                $dimensions = unserialize(CATEGORY_DIMENSION);
                $pathSmall = REAL_PATH . APPLICATIONS_SMALL_PATH;
                $pathMedium = REAL_PATH . APPLICATIONS_MEDIUM_PATH;
                $pathLarge = REAL_PATH . APPLICATIONS_LARGE_PATH;
                break;
            case 'video':
                $fileUploadPath = REAL_PATH . serverVideoRelPath;
                $dimensions = '';
                break;
            case 'audio':
                $fileUploadPath = REAL_PATH . serverAudioRelPath;
                $dimensions = '';
                break;
            case 'ads':
                $fileUploadPath = REAL_PATH . serverAdsRelPath;
                $dimensions = '';
                break;
        }
        try {
            if (move_uploaded_file($fileTempPath, $fileUploadPath . $fileUniqName)) {
                $fileDestPath = $fileUploadPath . $fileUniqName;
                if ($type != 'video' && $type != 'audio') {
                    foreach ($dimensions as $key => $value) {
                        if ($key == 'small') {
                            $path = $pathSmall;
                        } else if ($key == 'medium') {
                            $path = $pathMedium;
                        } else if ($key == 'large') {
                            $path = $pathLarge;
                        }

                        if ($type == 'splash') {
                            $fileExt = $this->_getFileExtension($fileUniqName);
                            $resizefilename = current(explode(".", $fileUniqName));
                            $filename = $resizefilename . '_' . $key . '.' . $fileExt;
                        } else {
                            $filename = $fileUniqName;
                        }
                        $img = $this->create_thumbnail($filename, $fileDestPath, $path, $value['width'], $value['height']);
                        $filename = '';
                    }
                }
                return true;
            } else {
                echo "There was an error uploading the file, please try again!";
                return false;
            }
        } catch (Exception $e) {
            echo $e;
            exit;
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to get file extension
      /--------------------------------------------------------------------------------
      /
      / 	$fileName           = filename
      /
     */

    function _getFileExtension($fileName) {
        $fileExtension = end(explode(".", $fileName));
        return $fileExtension;
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to get file extension from url
      /--------------------------------------------------------------------------------
      /
      / 	$filePath           = filepath(http)
      /
     */

    function _getFileExtensionUrl($filePath) {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        return $fileExtension;
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to download file using curl
      /--------------------------------------------------------------------------------
      /
      / 	$fileSrcPath           = source path
      / 	$fieDestPath           = Destination path
      /
     */



    /*
     * Calculate HMAC-SHA1 according to RFC2104
     * See http://www.faqs.org/rfcs/rfc2104.html
     */

    /*
     * Calculate HMAC-SHA1 according to RFC2104
     * See http://www.faqs.org/rfcs/rfc2104.html
     */

    function hmacsha1($key, $data) {
        $blocksize = 64;
        $hashfunc = 'sha1';
        if (strlen($key) > $blocksize)
            $key = pack('H*', $hashfunc($key));
        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack(
                'H*', $hashfunc(
                        ($key ^ $opad) . pack(
                                'H*', $hashfunc(
                                        ($key ^ $ipad) . $data
                                )
                        )
                )
        );
        return bin2hex($hmac);
    }

    /*
     * Used to encode a field for Amazon Auth
     * (taken from the Amazon S3 PHP example library)
     */

    function hex2b64($str) {
        $raw = '';
        for ($i = 0; $i < strlen($str); $i+=2) {
            $raw .= chr(hexdec(substr($str, $i, 2)));
        }
        return base64_encode($raw);
    }

    function _uploadFileCurl($fileSrcPath, $fieDestPath, $videoFileUniqName) {

        $output_filename = 'assets/upload/video/' . $videoFileUniqName;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fileSrcPath);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        //print_r($result);


        $fp = fopen($output_filename, 'w');
        fwrite($fp, $result);
        fclose($fp);

        if ($this->amazons3) {
            $fileInfo = $this->getFileInfo($output_filename);
            $mimeType = $fileInfo['mime_type'];
            $bucket = bucket;
            // Create a `Aws` object using a configuration file
            $aws = Aws::factory(APPPATH . 'config/amazoneS3.php');
            // Get the client from the service locator by namespace
            $client = $aws->get('s3');
            if (!$client->doesBucketExist($bucket)) {
                $client->createBucket(array('Bucket' => $bucket, 'ACL' => 'public-read'));
            }
            $uploader = UploadBuilder::newInstance()
                    ->setClient($client)
                    ->setSource($output_filename)
                    ->setBucket($bucket)
                    ->setKey($videoFileUniqName)
                    ->setOption('ACL', 'public-read')
                    ->setOption('ContentType', $mimeType)
                    ->build();
            try {
                $uploader->upload();
                unlink($output_filename);
                return true;
            } catch (MultipartUploadException $e) {
                $uploader->abort();
                unlink($output_filename);
                return false;
            }
        } else {
            return true;
        }
    }

    /*
      /--------------------------------------------------------------------------------
      /   function to download file using ftp
      /--------------------------------------------------------------------------------
      /
      / 	$fileSrcPath           = source path
      / 	$fieDestPath           = Destination path
      /
     */

    function _downloadFileFtp($fileSrcPath, $fieDestPath, $ftp_conn, $fileNameUniq) {
        //echo $fieDestPath; die;
        ftp_pasv($ftp_conn, TRUE);
        $data = ftp_get($ftp_conn, $fieDestPath, $fileSrcPath, FTP_BINARY, 0);
        if ($data == 1) {
            //echo 1; die;
            if ($this->amazons3) {
                $fiePath = REAL_PATH . serverVideoRelPath . $fileNameUniq;
                //Here is the file we are downloading, replace spaces with %20
                echo $ch = curl_init(str_replace(" ", "%20", $fiePath));
                die;
                //File to save the contents to
                $fp = fopen($fieDestPath, 'wb');
                //give curl the file pointer so that it can write to it
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $data = curl_exec($ch); //get curl response	
                curl_close($ch);
                fclose($fp);
                if (filesize($fieDestPath) > 0) {
                    //echo 1; die;
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    function create_thumbnail($filename, $srcPath, $uploadPath, $width, $height) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $srcPath;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['thumb_marker'] = '';
        $config['width'] = $width;
        $config['height'] = $height;
        $config['new_image'] = $uploadPath . $filename;
        $this->image_lib->initialize($config);
        $result = $this->image_lib->resize();
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
            exit;
        }
        return TRUE;
    }

    /*      purpose : function to upload an Image file from using simple form upload(without ajax)
     * 	    parameters :
     * 		$field 		: name of form field(file type)
     * 		$fileUniqName  	: unique name of file
     * 		$fileUploadPath : destination path where file have to be uploaded
     * 		$thumb 		: this is to specify whether we have to create thumbnail size or not(Image file) 
     */

    /*   function _uploadImage($fieldName, $fileNameUnique, $fileUploadPath, $thumb = FALSE) {
      $config['upload_path'] = $fileUploadPath;
      $config['file_name'] = $fileNameUnique;
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload($fieldName)) {
      $this->data['error'] = array('error' => $this->upload->display_errors());
      return False;
      } else {
      $upload_data = $this->upload->data();
      if($thumb) {
      $srcPath = $upload_data['full_path'];
      $smallImg = $this->create_thumbnail($incoming_original, $srcPath, REAL_PATH.THUMB_SMALL_PATH, '320', '140');
      $medImg = $this->create_thumbnail($incoming_original, $srcPath,  REAL_PATH.THUMB_MEDIUM_PATH, '480', '215');
      $largeImg = $this->create_thumbnail($incoming_original, $srcPath, REAL_PATH.THUMB_LARGE_PATH, '720', '320');
      }
      return True;
      }

      } */


    /*      purpose : function to upload a video file from using simple form upload(without ajax)
     * 	    parameters :
     * 		$field 		: name of form field(file type)
     * 		$fileUniqName  	: unique name of file
     * 		$fileUploadPath : destination path where file have to be uploaded
     */


    /* function _uploadVideo($fieldName, $fileNameUnique, $fileUploadPath) {
      $config['upload_path'] = $fileUploadPath;
      $config['file_name'] = $fileNameUnique;
      $this->load->library('upload', $config);
      if (!$this->upload->do_upload($fieldName)) {
      $this->data['error'] = array('error' => $this->upload->display_errors());
      return False;
      } else {
      $upload_data = $this->upload->data();
      return True;
      }

      } */


    /*
      /--------------------------------------------------------------------
      /  function to show success message
      /--------------------------------------------------------------------
      /
      / 	$message                    = message to be displayed
      /
     */

    function _successmsg($message) {
        $succMsg = sprintf('<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
        return $succMsg;
    }

    /*
      /--------------------------------------------------------------------
      /  function to show error message
      /--------------------------------------------------------------------
      /
      / 	$message                    = message to be displayed
      /
     */

    function _errormsg($message) {
        $errorMsg = sprintf('<div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div>', $message);
        return $errorMsg;
    }

    /*
      /--------------------------------------------------------------------
      /  function to show warning message
      /--------------------------------------------------------------------
      /
      / 	$message                    = message to be displayed
      /
     */

    function _warningmsg($message) {
        $errorMsg = sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-warning alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $message);
        return $errorMsg;
    }

    /*
      /--------------------------------------------------------------------
      /  unction to set a file information using getid3 library
      /--------------------------------------------------------------------
      /
      / 	$filePath                   = Path of file
      /
     */

    function getFileInfo($filePath) {
        $this->load->library('getid3/getid3');
        $getID3 = new GetId3;
        $ThisFileInfo = $getID3->analyze($filePath);
        return $ThisFileInfo;
    }

    /*
      /--------------------------------------------------------------------
      /  unction to delete a file from server(local/s3)
      /--------------------------------------------------------------------
      /
      / 	$fileName                   = name of file
      /   $fileType                   = type of file(image/video)
      /
     */

    function _deleteFile($fileName, $fileDir) {
        //echo $fileName; die
        if ($this->amazons3) {
            $bucket = bucket;
            $aws = Aws::factory(APPPATH . 'config/amazoneS3.php');
            // Get the client from the service locator by namespace
            $client = $aws->get('s3');
            $newFilePath = $fileName;
            $existFile = $client->doesObjectExist($bucket, $newFilePath);
            if ($existFile) {
                $result = $client->deleteObject(array(
                    // Bucket is required
                    'Bucket' => bucket,
                    // Key is required
                    'Key' => $newFilePath,
                ));
                if ($result) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            $filePath = $fileDir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    /*
      /--------------------------------------------------------------------
      /  function to download a file from server(local/s3)
      /--------------------------------------------------------------------
      /
      / 	$fileName                   = name of file
      /   $fullPath                   = path of file(image/video)
      /
     */

    function _downloadFile($fileName, $fullPath) {
        if ($this->amazons3) {
            $bucket = bucket;
            $aws = Aws::factory(APPPATH . 'config/amazoneS3.php');
            // Get the client from the service locator by namespace
            $client = $aws->get('s3');
            $newFilePath = 'videos/' . $fileName;
            $existFile = $client->doesObjectExist($bucket, $newFilePath);
            if ($existFile) {
                try {
                    // Get the object
                    $result = $client->getObject(array(
                        'Bucket' => $bucket,
                        'Key' => $newFilePath
                    ));

                    // Display the object in the browser
                    header("Content-Type: {$result['ContentType']}");
                    header("Pragma: public"); // required
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private", false); // required for certain browsers
                    header("Content-Type: $ctype");
                    header("Content-Disposition: attachment; filename=\"" . basename($fullPath) . "\";");
                    header("Content-Transfer-Encoding: binary");
                    header("Content-Length: {$result['ContentLength']}");
                    ob_clean();
                    flush();
                    readfile($fullPath);
                    //return $result['Body'];
                } catch (S3Exception $e) {
                    //echo $e->getMessage() . "\n";
                    return false;
                }
            } else {
                return false;
            }
        } else {
            if (headers_sent())
                die('Headers Sent');

            // Required for some browsers
            if (ini_get('zlib.output_compression'))
                ini_set('zlib.output_compression', 'Off');

            // File Exists?
            if (file_exists($fullPath)) {
                // Parse Info / Get Extension
                try {
                    $fsize = filesize($fullPath);
                    $path_parts = pathinfo($fullPath);
                    $ext = strtolower($path_parts["extension"]);
                    // Determine Content Type
                    switch ($ext) {
                        case "gif": $ctype = "image/gif";
                            break;
                        case "png": $ctype = "image/png";
                            break;
                        case "jpeg":
                        case "jpg": $ctype = "image/jpg";
                            break;
                        default: $ctype = "application/force-download";
                    }

                    header("Pragma: public"); // required
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private", false); // required for certain browsers
                    header("Content-Type: $ctype");
                    header("Content-Disposition: attachment; filename=\"" . basename($fullPath) . "\";");
                    header("Content-Transfer-Encoding: binary");
                    header("Content-Length: " . $fsize);
                    ob_clean();
                    flush();
                    readfile($fullPath);
                    return true;
                } catch (S3Exception $e) {
                    //echo $e->getMessage() . "\n";
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /*
      /--------------------------------------------------------------------
      /  function to get url content
      /--------------------------------------------------------------------
      /
      / 	$url                   = url
      /
     */

    function get_urlcontent($url) {
        $response = "";
        $ch = curl_init();
        // define options
        $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        );
        // apply those options
        curl_setopt_array($ch, $optArray);
        // execute request and get response
        $result = curl_exec($ch);
        return str_replace(' ', '', $result);
        for ($i = 0; $i <= strlen($result); $i++) {
            echo ord($result[$i]) . '----' . $result[$i];
            echo '<br/>';
        }
    }

    public function readCsvFile($file) {
        $i = 0;
        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                //  echo "<p> $num fields in line $row: <br /></p>\n";
                if ($row == 1) {
                    $row++;
                    continue;
                }

                if (strtolower($data[0]) == 'video') {
                    $xmlfile = $this->createVideoXml($data[4], $data[1], $data[3]);
                }
                if (strtolower($data[0]) == 'banner') {
                    $xmlfile = $this->createBannerXml($data[4], $data[1]);
                }
                $result[$i]['type'] = $data[0];
                $result[$i]['filepath'] = $xmlfile;
                $result[$i]['offset'] = $data[2];
                $i++;
            }

            fclose($handle);
            return serialize($result);
        }
    }

    public function createVideoXml($title, $file, $duration) {
        $path = VAST_PATH;
        $filename = 'video' . str_replace(' ', '-', $title);
        $filename .= '-' . date('ymdhis') . rand();
        $this->load->helper('xml');
        $dom = xml_dom();
        $video = xml_add_child($dom, 'VAST');
        xml_add_attribute($video, 'version', '2.0');
        $ad = xml_add_child($video, 'Ad');
        $inline = xml_add_child($ad, 'InLine');
        $adsystem = xml_add_child($inline, 'AdSystem', 'demo video');
        $adtitle = xml_add_child($inline, 'AdTitle', $title);
        $creatives = xml_add_child($inline, 'Creatives');
        $creative = xml_add_child($creatives, 'Creative');
        $linear = xml_add_child($creative, 'Linear');
        $duration = xml_add_child($linear, 'Duration', $duration);
        $mediafiles = xml_add_child($linear, 'MediaFiles');
        $mediafile = xml_add_child($mediafiles, 'MediaFile', $file);
        xml_add_attribute($mediafile, 'type', 'video/mp4');
        xml_add_attribute($mediafile, 'bitrate', '300');
        xml_add_attribute($mediafile, 'width', '480');
        xml_add_attribute($mediafile, 'height', '270');

        $xml = xml_print($dom, true);

        $xmlobj = new SimpleXMLElement($xml);
        $xmlobj->saveXML($path . $filename . ".xml");
        return $path . $filename . ".xml";
    }

    public function createBannerXml($title, $file) {
        $path = VAST_PATH;
        $filename = 'banner' . str_replace(' ', '-', $title);
        $filename .= '-' . date('ymdhis');
        $this->load->helper('xml');
        $dom = xml_dom();
        $video = xml_add_child($dom, 'VAST');
        xml_add_attribute($video, 'version', '2.0');
        $ad = xml_add_child($video, 'Ad');
        $inline = xml_add_child($ad, 'InLine');
        $adsystem = xml_add_child($inline, 'AdSystem', 'demo video');
        $adtitle = xml_add_child($inline, 'AdTitle', $title);
        $creatives = xml_add_child($inline, 'Creatives');
        $creative = xml_add_child($creatives, 'Creative');
        $linear = xml_add_child($creative, 'NonLinearAds');
        $nonlinear = xml_add_child($linear, 'NonLinear');
        $staticsource = xml_add_child($nonlinear, 'StaticResource', $file);
        xml_add_child($nonlinear, 'NonLinearClickThrough', 'url');
        xml_add_attribute($staticsource, 'creativeType', "image/jpeg");
        xml_add_attribute($nonlinear, 'width', '480');
        xml_add_attribute($nonlinear, 'height', '270');
        xml_add_attribute($nonlinear, 'minSuggestedDuration', '00:00:15');

        $xml = xml_print($dom, true);

        $xmlobj = new SimpleXMLElement($xml);
        $xmlobj->saveXML($path . $filename . ".xml");
        return $path . $filename . ".xml";
    }

    function time_from_seconds($seconds) {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds - ($h * 3600) - ($m * 60);
        return sprintf('%02d:%02d:%02d', $h, $m, $s);
    }

    function dateFormat($date) {
        $temp = strtotime($date);
        if ($temp > 0) {
            return date('d-m-Y m:i:s', $temp);
        } else {
            return false;
        }
    }

    function deleteDir($dirPath) {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}
