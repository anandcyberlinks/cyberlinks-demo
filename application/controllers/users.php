<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

        function __construct(){
          parent::__construct();
		  $this->load->library('session');
        }
        function index(){
				//$data['layout']=$this; 
				$user_lang = $this->uri->segment(3); 				
                if(isset($user_lang)){
					$lan=$user_lang;
                }else{
                    $lan='eng';								   
                }					
				$this->session->set_userdata('lan',$lan);

				if(isset($_SERVER['HTTP_REFERER']))
                {
                    $redirect_to = str_replace(base_url(),'',$_SERVER['HTTP_REFERER']);
					if($lan == 'hin')
					{
						$this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>भाषा सफलतापूर्वक बदला</div></div></section>');
					}
					else
					{
						$this->session->set_flashdata('message', '<section class="content"><div class="col-xs-12"><div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Languange change successfully</div></div></section>');
					}
					
                }
                else
                {
                    $redirect_to = $this->uri->uri_string();
                }            

                redirect($redirect_to);
               

            //$this->show_view('dashboard',$data);
        }
 }

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */