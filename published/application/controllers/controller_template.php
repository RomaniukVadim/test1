<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NameOfController extends CI_Controller { // NameOfController must start with Capital Letter and must be same filename

	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
		$data = array("title"=>"PSP Notice");			//this is the array for data to be used on the template file
		$this->load->view('header',$data);				//$this->load->view() 	- loads the file under the view folder.
														//						- Second parameter is the array variable to be used within the file called
		$this->load->view('main_content/header');
		$this->load->view('main_content/header');
		$this->load->view('footer');
	}
	
	
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */