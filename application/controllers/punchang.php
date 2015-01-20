<?php

class Punchang extends MY_Controller {

    public $uid = null;
    
    function __construct() {
        parent::__construct();
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
        if(!isset($_FILES)){
            redirect(base_url().'punchang');
        }
        //print_r($_FILES); die;
        if($_FILES['csv']['type'] != 'text/csv'){
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
                        $temp['sunrice'] = $csv_line[4];
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

}
