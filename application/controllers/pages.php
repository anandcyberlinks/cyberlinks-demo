        <?php
        
        if (!defined('BASEPATH'))
        exit('No direct script access allowed');
        
        class Pages extends MY_Controller {
        
        public $role = null;
        public $user_id = null;
        public $user = null;
        public $role_id = null;
        
        function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->library('form_validation');
        $this->load->model('page_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
            $this->user = $s[0]->username;
            $this->user_id = $s[0]->id;
            $this->role_id = $s[0]->role_id;
        }
        protected $validation_rules = array
        (
        'add_page' => array(
            array(
                'field' => 'page_title',
                'label' => 'Page name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'page_description',
                'label' => 'Description',
                'rules' => 'trim|required'
            )
        ),
        'update_page' => array(
            array(
                'field' => 'page_title',
                'label' => 'Page name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'page_description',
                'label' => 'Description',
                'rules' => 'trim|required'
            )
        )
    );
        
        
        
        function index() {
        
        $data['welcome'] = $this;
        $data['result'] = $this->page_model->getPagedata();
        $this->show_view('page', $data);    
        }
        
        /* 	Add and Edit Category	 */

    function addpage() {
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $pageid = base64_decode($id);
            }
            if (isset($pageid)) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_page']);
                    if ($this->form_validation->run()) {
                        $_POST['id'] = $pageid;
                        $_POST['status'] = $this->input->post('status');
                        $this->page_model->savePage($_POST);
                        $msg = $this->loadPo($this->config->item('success_record_update'));
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', $this->_successmsg($msg));
                        redirect('pages');
                    } else {
                       
                        $this->data['result'] = $this->page_model->editPagedata();
                        $this->show_view('editpage', $this->data);
                    }
                } else {
                     // $s = $this->page_model->editPagedata($pageid);
                  //    echo '<pre>';print_r($s);echo '</pre>';
                   $this->data['result'] = $this->page_model->editPagedata($pageid);
                    $this->show_view('editpage', $this->data);
                }
            } else {
                if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                   // print_r($_POST); die;
                    $_POST['user_id'] = $this->user_id;
                    $_POST['status'] = $this->input->post('status');
                    $this->form_validation->set_rules($this->validation_rules['add_page']);
                    if ($this->form_validation->run()) {
                        $result = $this->page_model->checkPage($_POST['page_title'],$this->user_id);
                        if ($result == 0) {
                            $this->page_model->savePage($_POST);
                            $msg = $this->loadPo($this->config->item('success_record_add'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
                            redirect('pages');
                        } else {
                            $msg = $this->loadPo($this->config->item('warning_record_exists'));
                            $this->session->set_flashdata('message', $this->_warningmsg($msg));
                            redirect('pages/');
                        }
                    } else {
                       // $this->data['allParentCategory'] = $this->Category_model->getAllCategory();
                        $this->show_view('addpage', $this->data);
                    }
                } else {
                   // $this->data['allParentCategory'] = $this->Category_model->getAllCategory();
                    $this->show_view('addpage', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', $this->_errormsg($this->config->item('error_permission')));
            redirect(base_url() . 'pages');
        }
    }
        
        
        
        function changestatus() {
        $data['id'] = $_GET['id'];
        $data['status'] = $_GET['status'];
        $this->page_model->updatestatus($data);
      $msg = $this->loadPo($this->config->item('success_record_update'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
        redirect(base_url() . 'pages');
    }
    
    public function deletePage() {
        $data['welcome'] = $this;
        $id = $_GET['id'];
        $last_id = $this->page_model->deletePage($id);
        $msg = $this->loadPo($this->config->item('success_record_delete'));
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', $this->_successmsg($msg));
       redirect(base_url() . 'pages');
    }
    
    
}
