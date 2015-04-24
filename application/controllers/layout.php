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

    public function index() {
        ###check user session #######
        $s = $this->session->all_userdata();
        $tmp = @$s['0'];
        if (isset($tmp->id)) {
            if ($tmp->role == 'advertiser') {
                redirect(base_url() . 'ads');
            } else {
                //redirect(base_url().'layout/dashboard');
                redirect(base_url() . 'analytics/report');
            }
        }
        #################################
        if (isset($_POST['login'])) {
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            $result = $this->user_model->CheckUser($data);
            if (count($result) == '1') {
                $result['main_username'] = $_POST['username'];
                $result['main_password'] = $_POST['password'];
                $this->session->set_userdata($result);
                //print_r($_POST); die;
                if (isset($_POST['remember_me'])) {
                    //echo 'hello'; die;
                    setcookie('user', $result['main_username'], time() + (86400 * 365), "/");
                    setcookie('password', $result['main_password'], time() + (86400 * 365), "/");
                    setcookie('remember', 'on', time() + (86400 * 365), "/");
                } else {
                    setcookie('user', $result['main_username'], time() - (86400 * 365), "/");
                    setcookie('password', $result['main_password'], time() - (86400 * 365), "/");
                    setcookie('remember', 'on', time() - (86400 * 365), "/");
                }
                $msg = $this->loadPo($this->config->item('success_login'));
                $s = $this->session->all_userdata();
                $this->log($data['username'], $msg);
                if ($s['0']->role == 'Advertiser') {
                    redirect(base_url() . 'ads_analytics/report');
                }
                if ($s['0']->role == 'Superadmin') {
                    redirect(base_url() . 'user');
                }
                else {
                    // redirect(base_url().'layout/dashboard');
                    redirect(base_url() . 'analytics/report');
                }
            } else {
                $this->session->set_flashdata('msg', $this->config->item('warning_auth'));

                $this->error('error', 'Unauthorised User Try to Login With Username =>>' . $_POST['username']);
                redirect('layout');
            }
        } else {
            $this->load->view('login');
        }
    }

    function register() {
        if (isset($_POST['submit'])) {
            $temp = array();
            $temp['username'] = $_POST['email'];
            $temp['email'] = $_POST['email'];
            $temp['domain'] = $_POST['domain'];
            $temp['owner_id'] = 17;
            $temp['first_name'] = $_POST['first_name'];
            $temp['last_name'] = $_POST['last_name'];
            $temp['contact_no'] = $_POST['contact_no'];
            $temp['password'] = md5($_POST['password']);
            $temp['role_id'] = 1;
            $temp['created'] = date('Y-m-d h:m:i');
            $this->db->insert('users', $temp);
            $user_id = $this->db->insert_id();
            $token['token'] = md5(uniqid());
            $token['action'] = 'verification';
            $this->user_model->genratetoken($token, $user_id);
            $link = base_url() . 'layout/token/?token=' . $token['token']; //mail body
            ###email to user ####
            $body = file_get_contents(base_url().'layout/email_user?link='.$link);
            $subject = 'Email Verification link For Multitv';
            $to = $temp['email'];
            $this->sendmail($to, $subject, $body); //mail to user
            
            #####email to admin
            $email = $temp['email'];
            $body = file_get_contents(base_url().'layout/email_admin?email='.$email);
            $subject = 'Multitv Notification';
            
            //$this->sendmail('arshad.faiyaz@cyberlinks.co.in', $subject, $body); //mail to user
            redirect('layout/succ_register');
        } else {
            $this->load->view('register');
        }
    }

    function succ_register() {
        $this->load->view('success');
    }
    
    function email_user(){
        $this->load->view('email/email_user');
    }
    function email_admin(){
        $this->load->view('email/email_admin');
    }
    function token_email() {
        $this->load->view('email/token_email');
    }

    /*
     * Dashboard
     */

    function dashboard() {
        $data['result'] = $this->session->all_userdata();
        $data['welcome'] = $this;
        $data['videos'] = $this->videos_model->get_videocountstatus($this->user_id);
        
        $date = $this->getMonths("August 2012");
        //echo "<pre>"; print_r($date);
        foreach ($date as $key=>$val){
            $data['years'][] = $key;
        }
       //echo "<pre>"; print_r($data['years']); die;
        //$data['years'] = array(2013 => 2013, 2014 => 2014, 2015 => 2015);
        $data['months'] = array(1 => "Jan", 2 => "Feb", 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        $this->show_view('dashboard', $data);
    }

    function dashboardchart($type = null) {
        $result = array();
        switch ($type) {
            case 'category_video' :
                $query = sprintf('SELECT 
                                cat.id,cat.category,SUM(if(c.id is null,0,1)) as total
                                FROM `categories` cat
                                left join contents c on c.category = cat.id
                                where (cat.u_id = %d OR cat.parent_id = %d)
                                group by cat.id', $this->user_id, $this->user_id);
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(count($dataset));
                foreach ($dataset as $key => $val) {
                    $result['data'][] = array('label' => ucwords($val->category), 'value' => $val->total);
                }
                break;
            case 'users_video' :
                $query = sprintf('SELECT u.id,
                                    concat(u.first_name,\' \',u.last_name) as name,
                                    SUM(if(c.id is null,0,1)) as total 
                                    FROM `users` u 
                                    left join contents c on c.uid = u.id
                                    where u.id = %d OR u.owner_id = %d
                                    group by u.id ', $this->user_id, $this->user_id);
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(count($dataset));
                foreach ($dataset as $key => $val) {
                    $result['data'][] = array('label' => ucwords($val->name), 'value' => $val->total);
                }
                break;
            case 'dailyvideo' :
                $month = $_GET['month'];
                $year = $_GET['year'];
                $totalDays = date('t', mktime(0, 0, 0, $month, 1, $year));
                $fields = array();
                for ($i = 1; $i <= $totalDays; $i++) {
                    $fields[] = sprintf("SUM(if(c.created between '%s' AND '%s',1,0)) as '%s' ", date('Y-m-d G:i:s', mktime(0, 0, 0, $month, $i, $year)), date('Y-m-d G:i:s', mktime(23, 59, 59, $month, $i, $year)), date('M d', mktime(23, 59, 59, $month, $i, $year)));
                }
                $query = sprintf('select %s from contents c where uid = %d', implode(',', $fields), $this->user_id);
                
                $dataset = $this->db->query($query)->result();
                $result['color'] = $this->randColor(1);
                foreach ($dataset[0] as $key => $val) {
                    $result['data'][] = array('y' => $key, 'value' => $val);
                }
                break;
        }
        echo json_encode($result);
        exit;
    }

    function randColor($numColors) {
        $chars = "ABCDEF0123456789";
        $size = strlen($chars);
        $str = array();
        for ($i = 0; $i < $numColors; $i++) {
            $tmp = '#';
            for ($j = 0; $j < 6; $j++) {
                $tmp .= $chars[rand(0, $size - 1)];
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
                    $this->log('log', 'User Sccesfull verified using Email link userid-> ' . $data['id']);
                    $this->user_model->deletetoken($data['id']);
                    $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_email_verified'))));
                    redirect(base_url()/'layout');
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

    function profile() {
        $data['welcome'] = $this;
        $this->user_id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->user_id;
        $data['data'] = $this->user_model->profile($this->user_id);
        //print_r($data['data']); die;
        $this->show_view('userProfile', $data);
    }

    function checkemail() {
        $email = $_GET['email'];
        $this->db->select('id');
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        $result = $query->result();
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

    function checkUser() {
        $user = $_GET['username'];
        $this->db->select('id');
        $this->db->where('username', $user);
        $query = $this->db->get('users');
        $result = $query->result();
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

    function do_upload() {
        $this->user_id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $this->user_id;
        //print_r($_FILES); DIE;
        $image_info = getimagesize($_FILES["image"]["tmp_name"]);
        $image_width = $image_info[0];
        $fileExt = end(explode('.', $_FILES['image']['name']));
        $allowedSize = 2; //MB
        $allowedFileSize = $allowedSize * 1024 * 1024; // Bytes
        $allowedExt = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
        $extns = implode(',', $allowedExt);
        $p_image = uniqid() . "_" . $this->user_id . "." . $fileExt;
        // echo $p_image; die;
        //echo $ext; die;
        if (in_array($fileExt, $allowedExt)) {
            if ($_FILES['image']['size'] <= $allowedFileSize) {
                // print_r( $fileInfoArray = pathinfo($p_image));
                $src = $_FILES['image']['tmp_name'];
                $dest = PROFILEPIC_PATH . $p_image;

                $userData = $this->user_model->fetchuser($this->user_id);
                $profilePicOld = REAL_PATH . PROFILEPIC_PATH . $userData[0]->image;
                $isMoved = move_uploaded_file($src, $dest);
                if ($isMoved == true) {
                    if (file_exists($profilePicOld)) {
                        unlink($profilePicOld);
                    }
                    $this->user_model->do_upload($this->user_id, $dest, REAL_PATH . PROFILEPIC_PATH . $p_image);
                    $this->session->set_flashdata('message', $this->_successmsg($this->loadPo($this->config->item('success_file_update'))));
                    redirect(base_url() . 'layout/profile/' . $this->user_id);
                } else {
                    $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_upload'))));
                    redirect(base_url() . 'layout/profile');
                }
            } else {
                $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_size') . $this->config->item('error_max_file_size') . $allowedSize)));
                redirect(base_url() . 'layout/profile');
                echo "";
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->loadPo($this->config->item('error_file_format'))));
            redirect(base_url() . 'layout/profile');
        }
    }

    function adserver_login() {
        $s = $this->session->all_userdata();
        $tmp = @$s['0'];
        if (isset($tmp->id)) {
            if ($tmp->role == 'Advertiser') {
                //echo "<form method='post' action='../tax.php' id='ad_form'>"
                echo "<form method='post' action='../../multitv/www/admin/index.php' id='ad_form'>"
                . "<input type='hidden' name='apicall' value='1'>"
                . "<input type='hidden' name='username' value='" . @$s['main_username'] . "'>"
                . "<input type='hidden' name='password' value='" . @$s['main_password'] . "'>"
                . "<input type='hidden' name='login' value='Login'>"
                . "</form>";

                echo '<script>document.forms["ad_form"].submit();</script>';
                exit;
                //$this->show_view('userProfile', $data);
            }
        }
    }

}
