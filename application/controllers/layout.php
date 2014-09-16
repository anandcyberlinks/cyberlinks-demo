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
        $this->load->model('user_model');
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
            redirect(base_url().'video');
        }
        #################################
        if (isset($_POST['login'])) {
            $data['username'] = $_POST['username'];
            $data['password'] = md5($_POST['password']);
            $result = $this->user_model->CheckUser($data);
            if (count($result) == '1') {
                $this->session->set_userdata($result);
                $this->log($data['username'], 'SuccesFully Loged In');
                redirect(base_url().'video');
            } else {
                $this->session->set_flashdata('msg', 'Authentication Failure Try Again');
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
        //print_r($data);
        $data['welcome'] = $this;
        $this->show_view('dashboard', $data);
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
                $id = $this->user_model->genratetoken($email); // Genrate new token and return id
                $token = $this->user_model->fetchtoken($id);   //fetch ganrated token
                $to = $_POST['email'];
                $subject = 'Reset Password';
                $body = base_url() . 'layout/token/?token=' . $token[0]->token;
                $mail = $this->sendmail($to, $subject, $body);
                if (!$mail) {
                    $this->session->set_flashdata('msg', 'Mail Not Sent');
                    $this->error('mail', 'Email Not Sent=> trying to reset password with email =>>' . $_POST['email']);
                    redirect('layout/forgot');
                } else {
                    $this->log('mail', 'Email Sent=> reset password link sent on email =>>' . $_POST['email']);
                    echo 'Password reset link sent Check you mail box';
                }
            } else {
                $this->session->set_flashdata('msg', 'Invalid Email');
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
                            $this->session->set_flashdata('succ', 'Password Changed Try to login');
                            redirect(base_url());
                        } else {
                            echo 'Password And confirm Password do not match ';
                        }
                    }
                } else {
                    $this->user_model->updateuser($data); //if token for activate user
                    $this->log('log', 'User Scces fullfully verified using Email link userid-> ' . $data['id']);
                    $this->user_model->deletetoken($data['id']);
                    $this->session->set_flashdata('succ', 'Mail Succesfully verified');
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
                $p_image = $_FILES['image']['name'] . "." . $fileExt;
               // echo $p_image; die;
                //echo $ext; die;
                if (in_array($fileExt, $allowedExt)) {
                    if ($_FILES['image']['size'] <= $allowedFileSize) {
                        // print_r( $fileInfoArray = pathinfo($p_image));
                        $src = $_FILES['image']['tmp_name'];
                        $dest = "assets/img/$p_image";

                        $isMoved = move_uploaded_file($src, $dest);
                        if ($isMoved == true) {
                            $this->user_model->do_upload($this->user_id, $p_image);
                            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Image Changed Success Fully</div></div></section>');
                            redirect(base_url() . 'layout/profile');
                        } else {
                            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Somthing Is wrong Image not Uploaded</div></div></section>');
                            redirect(base_url() . 'layout/profile');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Please Upload Max ' . $allowedSize . ' MB</div></div></section>');
                        redirect(base_url() . 'layout/profile');
                        echo "";
                    }
                } else {
                    $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Please Upload File With Valid Extension jpg, jpeg, png, bmp, gif</div></div></section>');
                    redirect(base_url() . 'layout/profile');
                }
    }

}
