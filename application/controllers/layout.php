<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Layout extends MY_Controller {

    public $user_id = null;
    public $user = null;
    public $role_id = null;
    public $role = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('user_model');
        $this->load->model('videos_model');
        $this->load->library('session');
        $this->load->helper('url');
        $session = $this->session->all_userdata();
        if (isset($session[0])) {
            $this->user_id = $session[0]->id;
            $this->user = $session[0]->username;
            $this->role_id = $session[0]->role_id;
            $this->role = $session[0]->role;
        }
    }

    /*
     * Function for user login
     */

    public function index(){
        ###check user session #######
        $s = $this->session->all_userdata();
        $tmp = @$s['0'];
        if (isset($tmp->id)) {
            redirect(base_url().'layout/dashboard');
        }
        #################################
        if (isset($_POST['login'])) {
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            $result = $this->user_model->CheckUser($data);
            if (count($result) == '1') {
                $this->session->set_userdata($result);
                $msg = $this->loadPo($this->config->item('success_login'));
                $this->log($data['username'], $msg);
                redirect(base_url().'layout/dashboard');
            } else { 
                $this->session->set_flashdata('msg', $this->config->item('warning_auth'));
                
                $this->error('error', 'Unauthorised User Try to Login With Username =>>' . $_POST['username']);
                redirect('layout');
            }
        } else {
            $this->load->view('login');
        }
    }

    /*
     * Dashboard
     */

    function dashboard() {
        $data['result'] = $this->session->all_userdata();
        $data['welcome'] = $this;
        $data['videos'] = $this->videos_model->get_videocountstatus($this->user_id);
        $data['years'] = array(2012=>2012,2013=>2013,2014=>2014);
        $data['months'] = array(1=>"Jan",2=>"Feb",3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'); 
        $this->show_view('dashboard', $data);
    }
    
    function dashboardchart($type = null){
        $result = array();
        switch($type){
            case 'category_video' :
                $query = sprintf('SELECT 
                                cat.id,cat.category,SUM(if(c.id is null,0,1)) as total
                                FROM `categories` cat
                                left join contents c on c.category = cat.id
                                where (cat.u_id = %d OR cat.parent_id = %d)
                                group by cat.id',$this->user_id,$this->user_id);
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(count($dataset));
                foreach($dataset as $key=>$val){
                    $result['data'][] = array('label'=>ucwords($val->category),'value'=>$val->total);   
                }
                break;
            case 'users_video' :
                    $query = sprintf('SELECT u.id,
                                    concat(u.first_name,\' \',u.last_name) as name,
                                    SUM(if(c.id is null,0,1)) as total 
                                    FROM `users` u 
                                    left join contents c on c.uid = u.id
                                    where u.id = %d OR u.owner_id = %d
                                    group by u.id ',$this->user_id,$this->user_id);
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(count($dataset));
                foreach($dataset as $key=>$val){
                    $result['data'][] = array('label'=>ucwords($val->name),'value'=>$val->total);   
                }
                break;
            case 'dailyvideo' :
                $month = $_GET['month'];
                $year = $_GET['year'];
                $totalDays = date('t',mktime(0,0,0,$month,1,$year));
                $fields = array();
                for($i=1;$i<=$totalDays;$i++){
                    $fields[] = sprintf("SUM(if(c.created between '%s' AND '%s',1,0)) as '%s' ",date('Y-m-d G:i:s',mktime(0,0,0,$month,$i,$year)),date('Y-m-d G:i:s',mktime(23,59,59,$month,$i,$year)),date('M d',mktime(23,59,59,$month,$i,$year)));
                }
                $query = sprintf('select %s from contents c where uid = %d', implode(',',$fields),$this->user_id);
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(1);
                foreach($dataset[0] as $key=>$val){
                    $result['data'][] = array('y'=>$key,'value'=>$val);   
                }
                break;
        }
        echo json_encode($result);
        exit;
    }
    
    function randColor( $numColors ) {
        $chars = "ABCDEF0123456789";   
        $size = strlen( $chars );
        $str = array();
        for( $i = 0; $i < $numColors; $i++ ) {
            $tmp = '#';
            for( $j = 0; $j < 6; $j++ ) {
                $tmp .= $chars[ rand( 0, $size - 1 ) ];
            }
            $str[$i] = $tmp;
        }
        return $str;
    }

    function logout() {
        $this->log($user, 'SuccesFully Loged Out');
        $this->session->sess_destroy();
        redirect(base_url());
    }

    /*
     * Function for forgot password
     */

    function forgot() {
        if (isset($_POST['forgot'])) {
            //print_r($_POST);
            $data['email'] = $_POST['email'];
            $email = $this->user_model->Checkemail($data);
            if (count($email) == '1') {
                $email['action'] = 'password_reset';
                $email['token'] = sha1(md5(uniqid()) . rand('1', '5000') . md5(time()));
                $id = $this->user_model->genratetoken($email); // Generate new token and return id
                $token = $this->user_model->fetchtoken($id);   //fetch generated token
                $to = $_POST['email'];
                $subject = 'Reset Password';
                $body = base_url() . 'layout/token/?token=' . $token[0]->token;
                $mail = $this->sendmail($to, $subject, $body);
                if (!$mail) {
                    $this->session->set_flashdata('msg', $this->_errormsg($this->loadPo($this->config->item('error_mail_sent'))));
                    $this->error('mail', 'Email Not Sent=> trying to reset password with email =>>' . $_POST['email']);
                    redirect('layout/forgot');
                } else {
                    $this->log('mail', 'Email Sent=> reset password link sent on email =>>' . $_POST['email']);
                    echo 'Password reset link sent Check you mail box';
                }
            } else {
                $this->session->set_flashdata('msg', $this->_errormsg($this->loadPo($this->config->item('error_mail_invalid'))));
                $this->error('log', 'trying to reset password with invalid email =>>' . $_POST['email']);
                redirect('layout/forgot');
            }
        } else {
            $this->load->view('forgot');
        }
    }

    /*
     * Function for Handle token
     */

    function token() {
        if ($_GET) {
            $token = $_GET['token'];
            $detail = $this->user_model->fetchtokendetail($token);
            $data['id'] = @$detail[0]->user_id;
            if (count($detail) == '1') {
                if (($detail[0]->action) == 'password_reset') {
                    $this->load->view('reset', $data);
                    if ($_POST) {
                        if ($_POST['password'] == $_POST['password2']) {
                            $data = $_POST;
                            $this->user_model->password($data);
                            $this->user_model->deletetoken($_POST['id']);
                            $this->log('log', 'Password changes Scces fully using email link userid-> ' . $_POST['id']);
                            $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_password_changed'))));
                            redirect(base_url());
                        } else {
                            echo 'Password And confirm Password do not match ';
                        }
                    }
                } else {
                    $this->user_model->updateuser($data); //if token for activate user
                    $this->log('log', 'User Scces fullfully verified using Email link userid-> ' . $data['id']);
                    $this->user_model->deletetoken($data['id']);
                    $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_email_verified'))));
                    redirect(base_url());
                }
            } else {
                echo 'Authentication Failed Token Expired or Invalid';
            }
        } else {
            echo 'Authentication Failed';
        }
    }

    /*
     * Function For Send Mail
     */

    function sendmail($to, $subject, $body) {
        $this->load->library('PHPmailer/phpmailer');
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

    function profile() {
        $data['welcome'] = $this;
        $data['data'] = $this->user_model->profile($this->user);
        $this->show_view('userProfile', $data);
    }

    function do_upload() {
        //print_r($_FILES); DIE;
        $image_info = getimagesize($_FILES["image"]["tmp_name"]);
        $image_width = $image_info[0];
        $fileExt = end(explode('.', $_FILES['image']['name']));
        $allowedSize = 2; //MB
        $allowedFileSize = $allowedSize * 1024 * 1024; // Bytes
        $allowedExt = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
        $extns = implode(',', $allowedExt);
        $p_image = uniqid()."_".$this->user_id.".".$fileExt;
       // echo $p_image; die;
        //echo $ext; die;
        if (in_array($fileExt, $allowedExt)) {
            if ($_FILES['image']['size'] <= $allowedFileSize) {
                // print_r( $fileInfoArray = pathinfo($p_image));
                $src = $_FILES['image']['tmp_name'];
                $dest = PROFILEPIC_PATH.$p_image;

                $userData = $this->user_model->fetchuser($this->user_id);
                $profilePicOld = REAL_PATH.PROFILEPIC_PATH.$userData[0]->image;
                $isMoved = move_uploaded_file($src, $dest);
                if ($isMoved == true) {
                    if(file_exists($profilePicOld)){
                        unlink($profilePicOld);
                    }
                    $this->user_model->do_upload($this->user_id, $dest, REAL_PATH.PROFILEPIC_PATH.$p_image);
                    $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_file_update'))));
                    redirect(base_url() . 'layout/profile');
                } else {
                    $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_upload'))));
                    redirect(base_url() . 'layout/profile');
                }
            } else {
                $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_size').$this->config->item('error_max_file_size').$allowedSize)));
                redirect(base_url() . 'layout/profile');
                echo "";
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_format'))));
            redirect(base_url() . 'layout/profile');
        }
    }

}
