<?php



if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include(APPPATH.'libraries/getid3/getid3.php');
require APPPATH.'libraries/aws.phar';
use Aws\S3\S3Client;
use Aws\Common\Aws;
use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\Model\MultipartUpload\UploadBuilder;

class MY_Controller extends CI_Controller {
    

    var $base_url = ''; // The page we are linking to
    var $prefix = ''; // A custom prefix added to the path.
    var $suffix = ''; // A custom suffix added to the path.
    var $total_rows = 3; // Total number of items (database results)
    var $per_page = 1; // Max number of items you want shown per page
    var $num_links = 1; // Number of "digit" links to show before/after the currently viewed page
    var $cur_page = 1; // The current page being viewed
    var $use_page_numbers = FALSE; // Use page number for segment instead of offset
    var $first_link = '&lsaquo; First';
    var $next_link = 'Next... →';
    var $prev_link = '← pre';
    var $last_link = 'Last &rsaquo;';
    var $uri_segment = 3;
    var $full_tag_open = '';
    var $full_tag_close = '';
    var $first_tag_open = '';
    var $first_tag_close = '&nbsp;';
    var $last_tag_open = '<li>';
    var $last_tag_close = '</li>';
    var $first_url = ''; // Alternative URL for the First Page.
    var $cur_tag_open = '<li class="active"><a href="#">';
    var $cur_tag_close = '</a></li>';
    var $next_tag_open = '<li>';
    var $next_tag_close = '</li>';
    var $prev_tag_open = '<li>';
    var $prev_tag_close = '</li>';
    var $num_tag_open = '<li>';
    var $num_tag_close = '</li>';
    var $page_query_string = FALSE;
    var $query_string_segment = 'per_page';
    var $display_pages = TRUE;
    var $anchor_class = '';
    
    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    
    function get_meta_tags ($html){
         preg_match_all ("/<title>(.*)<\/title>/", $html, $title);
         preg_match_all ("/<meta name=\"description\" content=\"(.*)\">/i", $html, $description);
         preg_match_all ("/<meta name=\"keywords\" content=\"(.*)\">/i", $html, $keywords);
         $res["content"] = @array("title" => $title[1][0], "descritpion" => $description[1][0], "keywords" =>  $keywords[1][0]);
         return $res;
    }
    
    function get_youtube($url){
        $id = $this->getYoutubeIdFromUrl($url);
        $html = $this->get_data($url);
        $data = $this->get_meta_tags($html);
        
        
        $youtube = sprintf('http://gdata.youtube.com/feeds/api/videos/%s?v=2&alt=json',$id);
        //$youtube = "http://www.youtube.com/oembed?url=". $url ."&format=json"; 
        $curl = curl_init($youtube);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $return = curl_exec($curl);
        curl_close($curl);
        
        $data['detail'] = (array) json_decode($return);
        return $data;
    }
    
    function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if($qs['vi']){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;
    }


    // constructor of this controller
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $sess = $this->session->all_userdata();
        if (!(isset($sess['lan']))) {
            $lang = 'eng';
            $this->session->set_userdata('lan', $lang);
        }
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
        $id = $tmp->id;
        $this->db->select('image');
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        $result = $query->result();
        if ($result[0]->image != "") {
            return base_url() . 'assets/img/' . $result[0]->image;
        } else {
            return base_url() . 'assets/img/avatar3.png';
        }
    }

    public function show_view($view, $data = '') {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = $tmp->id;
        if ($id != '') {
            $this->load->view('header', $data);
            $this->load->view('sidebar', $data);
            $this->load->view($view, $data);
            $this->load->view('footer', $data);
        } else {
            $this->session->set_flashdata('msg', 'Please Login');
            redirect(base_url().'layout');
        }
    }

    public function show_video_view($view, $data = '') {
        $s = $this->session->all_userdata();
        $tmp = $s['0'];
        $id = $tmp->id;
        if ($id != '') {
            $this->load->view('header', $data);
            $this->load->view('sidebar', $data);
            $this->load->view('videoEditHeader', $data);
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

    function sendmail($to, $subject, $body) {
        $this->load->library('PHPMailer/phpmailer');
        $mail = new PHPMailer();
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'cyberlinkslive@gmail.com';      // SMTP username
        $mail->Password = 'Cyberlinks!@#';                         // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->From = 'Admin@cyberlinks.co.in';
        $mail->FromName = 'Admin Cyberlinks';
        $mail->addAddress($to);    // Add a recipient
        //$mail->addAddress('pavan.prajapati@cyberlinks.in', 'Pawan PAAAArjapti');     // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');                       // Reply To.........
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('index.php');                  // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = 'Success';
        return $mail->send();
    }
	
	/********* function used to get video file size using getid3 ibrary **********/
	
	function getVideoFileSize($fileName, $path) {
        $this->load->library('getid3/getid3');
        $getID3 = new GetId3;
		$ThisFileInfo = $getID3->analyze($path.$fileName);

        return $ThisFileInfo;
    }
	
	/********* function used to upload video file on amazon s3 server **********/
	
	function uploadVideoAS3($incoming_tmp, $incoming_original, $content_type) 
	{
       	$bucket = bucket;
		// Create a `Aws` object using a configuration file
		$aws =  Aws::factory(APPPATH.'config/amazoneS3.php');
		// Get the client from the service locator by namespace
		$client = $aws->get('s3');
		if (!$client->doesBucketExist($bucket)) {
			$client->createBucket(array('Bucket' => $bucket,'ACL' => 'public-read'));						
		} 
		$uploader = UploadBuilder::newInstance()
			->setClient($client)
			->setSource($incoming_tmp)
			->setBucket($bucket)
			->setKey('videos/'.$incoming_original) 
			->setOption('ACL', 'public-read')
			->setOption('ContentType', $content_type)
			->build();
			try {
				$uploader->upload();
				return true;
			} catch (MultipartUploadException $e) {
				$uploader->abort();
				return false;
			}
    }
	
	function deleteFileS3($fileName)
	{
		$bucket = bucket;
		$aws =  Aws::factory(APPPATH.'config/amazoneS3.php');
		// Get the client from the service locator by namespace
		$client = $aws->get('s3');
		$newFilePath =  'videos/'.$fileName;
		$existFile = $client->doesObjectExist($bucket, $newFilePath);
		if($existFile)
		{
			$result = $client->deleteObject(array(
					// Bucket is required
					'Bucket' => bucket,
					// Key is required
					'Key' => $newFilePath,
			));
			if($result) {
				return true;
			}
		} else {
			return false;
		}
	}
	
	function downloadFileS3($fileName, $fullPath)
	{
		$bucket = bucket;
		$aws =  Aws::factory(APPPATH.'config/amazoneS3.php');
		// Get the client from the service locator by namespace
		$client = $aws->get('s3');
		$newFilePath =  'videos/'.$fileName;
		$existFile = $client->doesObjectExist($bucket, $newFilePath);
		if($existFile)
		{
			try {
				// Get the object
				$result = $client->getObject(array(
					'Bucket' => $bucket,
					'Key'    => $newFilePath
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

	}

}
