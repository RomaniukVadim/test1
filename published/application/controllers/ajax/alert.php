<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alert extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function check_session(){
		$session_key = $this->session->userdata("mb_session_key");
		if(!empty($session_key)){
			$this->load->model('Employee','employee');
			$emplog = $this->employee->get_table_entry(
										"member_login",
										array(
											"mb_no"=>$this->session->userdata("mb_no"),
											"date_logout"=>"0000-00-00 00:00:00",
											"session_key"=>$session_key
											)
										);
			if(empty($emplog)){
				return json_encode(array(
									"err_no"=>9,
									"message"=>"You have been logged out. Please login again. 123xx - ".$this->session->userdata("mb_no")
								));
			}
			return false;
		}
		else{
			return json_encode(array(
								"err_no"=>9,
								"message"=>"You have been logged out. Please login again. 456xx - ".$this->session->userdata("mb_no")
							));
		}
	}
	
	public function index(){
		// How often to poll, in microseconds (1,000,000 μs equals 1 s)
		define('MESSAGE_POLL_MICROSECONDS', 500000);
		 
		// How long to keep the Long Poll open, in seconds
		define('MESSAGE_TIMEOUT_SECONDS', 30);
		 
		// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
		define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);

		// Close the session prematurely to avoid usleep() from locking other requests
		session_write_close();

		// Automatically die after timeout (plus buffer)
		set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);

		// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
		$counter = MESSAGE_TIMEOUT_SECONDS;

		// Poll for messages and hang if nothing is found, until the timeout is exhausted
		while($counter > 0)
		{
			$session_res = $this->check_session();
			
			if(!$session_res){
				// Otherwise, sleep for the specified time, after which the loop runs again
				usleep(MESSAGE_POLL_MICROSECONDS);
		 
				// Decrement seconds from counter (the interval was set in μs, see above)
				$counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
				
			}
			else{
				// Break out of while loop if new data is populated
				$data = $session_res;
				break;
			}
		}
		
		if(isset($data) && !empty($data))
		{
			// Send data to client; you may want to precede it by a mime type definition header, eg. in the case of JSON or XML
			//echo $data;
			echo $data;
		}
		else
		{
			echo json_encode(array("err_no"=>0,"msg"=>""));
		}
		
			
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */