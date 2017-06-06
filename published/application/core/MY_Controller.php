<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	
	public function __construct(){    
		parent::__construct();
		
		if($this->config->item('maintenance_mode') == TRUE && $this->input->ip_address() != "10.120.10.221" && $this->input->ip_address() != "10.120.0.221") {
			redirect("error/under-maintenance");
		}
		
		
		$this->load->library('encrypt');
		
		$this->load->model('currency_model', '_currency');
		$this->load->model("manage_model","manage");
		
		//$this->load->library('session'); 
		//$this->session->sess_write();
 		$session_key = $this->session->userdata("mb_session_key");    
		if(!empty($session_key)){
			$this->load->model('employee','employee');
			$emplog = $this->employee->get_table_entry(
										"member_login",
										array(
											"mb_no"=>$this->session->userdata("mb_no"),
											"date_logout"=>"0000-00-00 00:00:00",
											"session_key"=>$session_key
											)
										);
			if(empty($emplog)){ 
				$this->session->sess_destroy();
				/*$data = array("title"=>"12Bet Settlement");
				$this->load->view('header',$data);
				$this->load->view('redirect');
				$this->load->view('footer');
				return false;*/ 
				error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin", "403");
		 		return false; 
			}
		}
		else{
			$data = array("title"=>"12Bet CAL");
			/*$this->load->view('header',$data);
			$this->load->view('redirect');
			$this->load->view('footer');
			return false;*/ 
			//error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin", "403"); 
			
			redirect("login");
		    return false; 
		}
	}

}
/* End of file my_controller.php */
/* Location: ./application/core/my_controller.php */