<?php
ini_set('display_errors', 1);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Category extends MY_Controller {

    public $user = null;
    public $role_id = null;

    function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->load->library('form_validation');
        $this->load->library('Session');
        $this->data['welcome'] = $this;
        $s = $this->session->all_userdata();
        $this->user = $s[0]->username;
        $this->role_id = $s[0]->role_id;
    }

    protected $validation_rules = array
        (
        'add_category' => array(
            array(
                'field' => 'category',
                'label' => 'Category name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            )
        ),
        'update_Category' => array(
            array(
                'field' => 'category',
                'label' => 'Category name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required'
            )
        )
    );

    function index() {
        $sort = $this->uri->segment(3);
        $sort_by = $this->uri->segment(4);
        switch ($sort) {
            case "category":
                $sort = 'a.category';
                if ($sort_by == 'asc')
                    $this->data['show_c'] = 'desc';
                else
                    $this->data['show_c'] = 'asc';
                break;
            case "parent":
                $sort = 'parent';
                if ($sort_by == 'asc')
                    $this->data['show_p'] = 'desc';
                else
                    $this->data['show_p'] = 'asc';
                break;
            case "status":
                $sort = 'a.status';
                if ($sort_by == 'asc')
                    $this->data['show_s'] = 'desc';
                else
                    $this->data['show_s'] = 'asc';
                break;
            default:
                $sort = 'a.category';
        }
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "category/index";
        $config["total_rows"] = $this->Category_model->getRecord_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_rows'] = $config["total_rows"];
        $this->data['category'] = $this->Category_model->getCategory($config["per_page"], $page, $sort, $sort_by);
        $this->data['allParentCategory'] = $this->Category_model->getparent();
        $this->show_view('category', $this->data);
    }

    /* 	Search Category  */

    function searchCategory() {

        if (isset($_POST['submit']) && $_POST['submit'] == 'Search') {
            $this->session->set_userdata('search_form', $_POST);
        } elseif (isset($_POST['reset']) && $_POST['reset'] == 'Reset') {
            $this->session->unset_userdata('search_form');
        }
        $search_for = $this->session->userdata('search_form');
        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "category/searchCategory";
        $config["total_rows"] = count($this->Category_model->getSearchCount($search_for));
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data['category'] = $this->Category_model->getSearchCategory($config["per_page"], $page, $search_for);
        $this->data["links"] = $this->pagination->create_links();
        $this->data["search_data"] = $search_for;
        $this->data['allParentCategory'] = $this->Category_model->getAllParentCategory();
        $this->data['total_rows'] = $config["total_rows"];
        $this->show_view('category', $this->data);
    }

    /* 	Add and Edit Category	 */

    function addCategory() {
        $per = $this->checkpermission($this->role_id, 'add');
        if ($per) {
            if (isset($_GET['action'])) {
                $id = $_GET['action'];
                $cid = base64_decode($id);
            }
            if (isset($cid)) {
                if (isset($_POST['submit']) && $_POST['submit'] == "Update") {
                    $this->form_validation->set_rules($this->validation_rules['update_Category']);
                    if ($this->form_validation->run()) {
                        $post['id'] = $cid;
                        $post['category'] = $this->input->post('category');
                        $post['parent_id'] = $this->input->post('parent_id');
                        $post['description'] = $this->input->post('description');
                        $post['status'] = $this->input->post('status') == 'on' ? 1 : 0;
                        $post['modified'] = date('Y-m-d');
                        $this->Category_model->update_category($post);
                        $msg = $this->loadPo('Category Successfully Updated');
                        $this->log($this->user, $msg);
                        $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $msg . '</div></div></section>');
                        redirect('category');
                    } else {
                        $this->data['allParentCategory'] = $this->Category_model->getparent();
                        $this->data['edit'] = $this->Category_model->editCategory($cid);
                        $this->show_view('edit_category', $this->data);
                    }
                } else {
                    $this->data['allParentCategory'] = $this->Category_model->getparent();
                    $this->data['edit'] = $this->Category_model->editCategory($cid);
                    $this->show_view('edit_category', $this->data);
                }
            } else {
                if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
                    $post['category'] = $this->input->post('category');
                    $post['parent_id'] = $this->input->post('parent_id');
                    $post['description'] = $this->input->post('description');
                    $post['status'] = $this->input->post('status');
                    $post['created'] = date('Y-m-d');
                    $post['modified'] = date('Y-m-d');
                    $this->form_validation->set_rules($this->validation_rules['add_category']);
                    if ($this->form_validation->run()) {
                        $result = $this->Category_model->checkCategory($post['category']);
                        if ($result == 0) {
                            $this->Category_model->saveCategory($post);
                            $msg = $this->loadPo('Category has been Added Successfully');
                            $this->log($this->user, $msg);
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            redirect('category');
                        } else {
                            $msg = $this->loadPo('Category Allready exist');
                            $this->session->set_flashdata('message', sprintf('<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>%s</div></div></section>', $msg));
                            redirect('category/');
                        }
                    } else {
                        $this->data['allParentCategory'] = $this->Category_model->getparent();
                        $this->show_view('add_category', $this->data);
                    }
                } else {
                    $this->data['allParentCategory'] = $this->Category_model->getparent();
                    $this->show_view('add_category', $this->data);
                }
            }
        } else {
            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin</div></div></section>');
            redirect(base_url() . 'category');
        }
    }

    /* 	Delete Category */

    function deleteCategory() {
        $per = $this->checkpermission($this->role_id, 'delete');
        //echo $this->role_id;
        if ($per) {
            $id = $_GET['id'];
            $video = $this->Category_model->fetch_video($id);
            if ($video == '0') {
                $child = $this->Category_model->fetchChild($id);
                if($child == '0'){
                    $this->Category_model->delete_category($id);
                    $msg = $this->loadPo('Category Successfully Deleted');
                    $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $msg . '</div></div></section>');
                    redirect(base_url() . 'category');
                }else{
                    $msg = $this->loadPo('Child in this Category You Can\'t Delete this');
                    $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $child . ' ' . $msg . '</div></div></section>');
                    redirect(base_url() . 'category');
                }
            } else {
                $msg = $this->loadPo('Video in this Category You Can\'t Delete this');
                $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . $video . ' ' . $msg . '</div></div></section>');
                redirect(base_url() . 'category');
            }
        } else {
            $this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Access Denined ! Contect Admin</div></div></section>');
            redirect(base_url() . 'category');
        }
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */    