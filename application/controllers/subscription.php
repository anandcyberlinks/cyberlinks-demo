<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Subscription extends MY_Controller {

    public $user = null;
    public $role_id = null;
    public $uid = null;

    function __construct() {
        parent::__construct();
        $this->load->model('subscription_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->uid = $s[0]->id;
        $this->role_id = $s[0]->role_id;
    }

	protected $validation_rules = array
        (
        'add_subscription' => array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required'
            )
        ),
        'update_subscription' => array(
            array(
                'field' => 'days',
                'label' => 'days',
                'rules' => 'trim|required'
            )
        )
    );

    /* 	Search Subscription  */

    function index() {
	$sortby = $this->uri->segment(3);
	$sort =   $this->uri->segment(4);
	if($sortby == ""){
	    $sortby = 'days';
	}
	if($sort == ""){
	    $sort = 'ASC';
	}
	$this->data['welcome'] = $this;
        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } elseif (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $search_for = $this->session->userdata('search_form');
	$search_for['uid'] = $this->uid;
        $this->data['result'] = $this->subscription_model->getSearchSubscription($search_for, $this->uid, $sortby, $sort);
        $this->data["search_data"] = $search_for;
        $this->show_view('subscription', $this->data);
    }

    /* 	Add and Edit Subscription	 */

    function addSubscription() {
	$this->data['welcome'] = $this;
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_GET['action'])) {
                $id = $_GET['action'];
                $sid = base64_decode($id);
            }
            if (isset($sid)) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_subscription']);
                    if ($this->form_validation->run()){
                        $post['id'] = $sid;
                        $post['name'] = $this->input->post('name');
			$post['days'] = $this->input->post('days');
                        $post['status'] = $this->input->post('status');
                        $post['modified'] = date('Y-m-d');
			$valid = $this->subscription_model->Checkusername(array('name'=>$post['name'], 'id'=>$post['id']));
			if($valid == '0'){
			    $this->subscription_model->update_subscription($post);
			    $msg = $this->loadPo('Package Updated Successfully.');
			    $this->log($this->user, $msg);
			    $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $msg . '</div></div></section>');
			    redirect('subscription');
			}else {
			$this->data['valid'] = "Duration Name allready Exist";
                        $this->data['edit'] = $this->subscription_model->editSubscription($sid);
                        $this->show_view('update_package', $this->data);
			}
		    } else {
                        $this->data['edit'] = $this->subscription_model->editSubscription($sid);
                        $this->show_view('update_package', $this->data);
                    }
                } else {
			$this->data['edit'] = $this->subscription_model->editSubscription($sid);
			//print_r($this->data); die;
			$this->show_view('update_package', $this->data);
                }
            } else {
                if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                    $post['name'] = $this->input->post('name');
		    $post['days'] = $this->input->post('days');
                    $post['status'] = $this->input->post('status');
		    $post['uid'] = $this->uid;
                    $post['created'] = date('Y-m-d');
                    $post['modified'] = date('Y-m-d');
                    $this->form_validation->set_rules($this->validation_rules['add_subscription']);
                    if ($this->form_validation->run()) {
			
                        $result = $this->subscription_model->checkSubscription($post['name'], $this->uid);
                        if ($result == 0) {
                            $this->subscription_model->saveSubscription($post);
                            $msg = $this->loadPo('Subscription Duration Added Successfully.');
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            redirect('subscription');
                        } else {
                            $msg = $this->loadPo('Subscription name Already exists.');
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            redirect('subscription');
                        }
                    } else {
                        $this->show_view('add_package', $this->data);
                    }
                } else {
                    $this->show_view('add_package', $this->data);
                }
            }
        } else {
            $message = $this->loadPo('Access Denined ! Contact Admin!');
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $message));
            redirect(base_url() . 'subscription');
        }
    }

    /* 	Delete Subscription */

    function deleteSubscription() {
        $per = $this->checkpermission($this->role_id, 'delete');
        if ($per) {
            if (isset($_GET['action'])) {
                $id = $_GET['action'];
                $sid = base64_decode($id);
            }            
		$result = $this->subscription_model->deleteSubscription($sid);
            if ($result == '1') {
                $msg = $this->loadPo('Subscription Deleted Successfully.');
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $msg . '</div></div></section>');
                redirect(base_url() . 'subscription');
            }else {
            $message = $this->loadPo('Access Denined ! Contact Admin!');
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $message));
            redirect(base_url() . 'subscription');
	    }
        } else {
            $message = $this->loadPo('Access Denined ! Contact Admin!');
            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $message));
            redirect(base_url() . 'subscription');
        }
    }

    function changeStatus(){
	$data['id'] =  $this->uri->segment(3);
	$data['status'] = $this->uri->segment(4);
	$this->subscription_model->changestatus($data);
	redirect(base_url().'subscription');
	
    }
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
	