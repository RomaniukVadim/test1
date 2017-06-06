<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->load->model('employee','employee');
	}
	
	public function index()
	{    
		$this->error_404(); 
	}
	 
	 
	public function error_404()
	{   
		header("HTTP/1.1 404 Not Found"); 
		$data = array("page_title"=>"404 Page Not Found", 
					  "header_title"=>"404 Page Not Found", 
					  "error_message"=>"The page you requested was not found. Check URL.",
					  "error_type"=>"404");
		$this->load->view('header',$data);
		$this->load->view('error_tpl');
		$this->load->view('footer'); 
	}	
	
	
	public function under_maintenance()
	{   
		if($this->config->item('maintenance_uptime') <= date("Y/m/d H:i")) {  
			redirect("dashboard");   
		}
		 
		  
		header("HTTP/1.1 503 Service Unavailable"); 
		
		$data = array("page_title"=>"OFFLINE", 
					  "header_title"=>"OFFLINE", 
					  "error_message"=>"Some big improvements be made. Please come back later!",
					  "error_type"=>"503", 
					  "maintenance_uptime"=>$this->config->item('maintenance_uptime'));
		$this->load->view('header',$data);  
		$this->load->view('under_maintenance_tpl');   
		$this->load->view('footer'); 
 
	} 
	
	
	public function checkUptime()
	{   
		if($this->config->item('maintenance_uptime') <= date("Y/m/d H:i")) {
			$actual = 1;    
		}
		
		if($actual == 1)//
		 {  
		 	 $return = array("live_system"=>1, 
							 "message"=>"Please wait. System configuring settings."
					   );
		 	 echo json_encode($return); 
		 }
		else
		 {
			$return = array("live_system"=>0, 
							"message"=>"Additional downtime. Please wait.", 
							"maintenance_uptime"=>$this->config->item('maintenance_uptime')
					   );
			 echo json_encode($return); 
		 }
	}
	 
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */