<?php

class Punchang extends MY_Controller {

    public $uid = null;

    function __construct() {
        parent::__construct();
        $this->load->config('messages');
        $this->load->model('panchang_model');
        $s = $this->session->all_userdata();
        $this->uid = $s[0]->id;
    }

    function index() {
        $data['welcome'] = $this;

        $this->load->library("pagination");
        $config = array();
        $config["base_url"] = base_url() . "punchang/index/";
        $config["total_rows"] = $this->panchang_model->get_count($this->uid);
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['result'] = $this->panchang_model->get_pan($this->uid, PER_PAGE, $page);
        $data["links"] = $this->pagination->create_links();
        $this->show_view('punchang', $data);
    }

    function submitcsv() {
        if (!isset($_FILES)) {
            redirect(base_url() . 'punchang');
        }
        //print_r($_FILES); die;
        if ($_FILES['csv']['type'] != 'text/csv') {
            echo json_encode(array('result' => 'invalid file'));
            die;
        }
        if ($_FILES['csv']['error'] == 0) {
            $fp = fopen($_FILES['csv']['tmp_name'], 'r') or die(json_encode(array('result' => 'error')));
            $num = 0;
            while ($csv_line = fgetcsv($fp, 1024)) {
                for ($i = 0, $j = count($csv_line[$i]); $i < $j; $i++) {
                    if ($num == 0) {
                        $title = $csv_line;
                        //echo "<pre>"; print_r($csv_line);
                    } else {
                        $temp = array();
                        $temp['u_id'] = $this->uid;
                        $temp['date'] = $csv_line[0];
                        $temp['month'] = $csv_line[1];
                        $temp['pakshya'] = $csv_line[2];
                        $temp['tithi'] = $csv_line[3];
                        $temp['sunrise'] = $csv_line[4];
                        $temp['sunset'] = $csv_line[5];
                        $temp['rahukal'] = $csv_line[6];
                        $temp['status'] = 1;
                        $temp['created'] = date('Y-m-d m:i:s');
                        //echo '<pre>'; print_r($temp);
                        $this->panchang_model->insert($temp);
                    }
                    $num++;
                }
            }
            fclose($fp);
            echo json_encode(array('result' => 'success'));
        } else {
            echo json_encode(array('result' => 'error'));
        }
    }

    function delete() {
        $id = $this->uri->segment(3);
        $this->db->delete('panchang', array('id' => $id));
        $msg = $this->loadPo($this->config->item('success_record_delete'));
        $this->log($this->user, $msg);
        $this->session->set_flashdata('message', $this->_successmsg($msg));
        redirect(base_url() . 'punchang');
    }

    function add() {
        $data['welcome'] = $this;
        $id = ($this->uri->segment(3));
        if (isset($id) && $id > 0) {
            $this->db->where('id', $id);
            $this->db->from('panchang');
            $result = $this->db->get()->result();
            if(count($result)>0){
                $data['result'] = $result;
                $_POST['id'] = $id;
            }
            //print_r($data['result']);
        }
        if (isset($_POST['submit'])) {
            
            //echo '<pre>';            print_r($_POST); die;
            $_POST['u_id'] = $this->uid;
            $_POST['created'] = date('Y-m-d m:i:s');
            $_POST['rahukal'] = implode(' TO ', $_POST['rahukal']);
            unset($_POST['submit']);
            $this->panchang_model->insert($_POST);
            $msg = $this->loadPo($this->config->item('success_record_add'));
            $this->log($this->user, $msg);
            $this->session->set_flashdata('message', $this->_successmsg($msg));
            redirect(base_url() . 'punchang');
        } else {
            $this->show_view('addPunchang', $data);
        }
    }

    function checkemail() {
        $data['date'] = $_GET['date'];
        $this->db->where('date', $data['date']);
        $result = $this->db->get('panchang')->result();
        //print_r($result);
        if (count($result) == '0') {
            echo '1';
        } else {
            echo '0';
        }
    }

}
