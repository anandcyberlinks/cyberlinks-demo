<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csv extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {

        $this->load->view('header');
        $this->load->view('test');
        $this->load->view('footer');
	
    }

    function readCSV() {
        //print_r($_FILES);
        //echo "test"; die;
	    $sampleFile = "assets/sample/samplefile.csv";
	    $file_sample = fopen($sampleFile, 'r');
            while (!feof($file_sample)) {
                $sample_of_text[] = fgetcsv($file_sample, 1024);
            }
            fclose($file_sample);
	    $sampledata['csv'] = $sample_of_text;
	   // echopre($sampledata);
	
        if (isset($_FILES['csv']) && $_FILES['csv']['name'] != "") {
            $csvFile = $_FILES['csv']['tmp_name'];
            $file_handle = fopen($csvFile, 'r');
            while (!feof($file_handle)) {
                $line_of_text[] = fgetcsv($file_handle, 1024);
            }
	    fclose($file_handle);
            for($i=0;$i<count($line_of_text);$i++){
		if($line_of_text[$i]!=''){
		    $data['csv'][$i-1]=$line_of_text[$i];
		}
	    }
	    if($sampledata['csv']['0']==$data['csv']['-1']){
		$this->load->view('table', $data);
	    }else{
		echo 'Select valid CSV File';
	    }
	   
	} else {
            echo 'Select valid CSV';
        }
    }

    function createXml() {
        $data = $_REQUEST;
	$dateTime= date('y_m_d_h:i:s');
	$path="xmlfile/";
	$filename=$path."XML_".$dateTime.".xml";
	$tagArr=array('Time','ID','Title','Dur','SOM','Device','Protect','Status','Type','Seg','sSP');
	$domtree = new DOMDocument('1.0', 'UTF-8');
	$xmlRoot = $domtree->createElement("xml");
	$xmlRoot = $domtree->appendChild($xmlRoot);
	$currentvalue = $domtree->createElement("data");
	$currentvalue = $xmlRoot->appendChild($currentvalue);
	foreach($data as $key=>$val){
	    foreach($val as $usrkey=>$value){
		for($i=0;$i<count($value);$i++){
		    $currentvalue->appendChild($domtree->createElement($tagArr[$i],$value[$i]));
		}
	    }
	}
	$domtree->save($filename);
	echo $filename;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
