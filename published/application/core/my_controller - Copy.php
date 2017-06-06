<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Controller extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
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
				$data = array("title"=>"12Bet Designers");
				$this->load->view('header',$data);
				$this->load->view('redirect');
				$this->load->view('footer');
				return false;
			}
		}
		else{
			$data = array("title"=>"12Bet Designers");
			$this->load->view('header',$data);
			$this->load->view('redirect');
			$this->load->view('footer');
			return false;
		}
	}

}
/* End of file my_controller.php */
/* Location: ./application/core/my_controller.php */