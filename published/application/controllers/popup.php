<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popup extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/dashboard

	 *	- or -  
	 * 		http://example.com/index.php/dashboard/index
	 *	- or -
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/dashboard/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->model("banks_model","banks");  
		$this->load->model("common_model","common"); 
	}
 	
	public function index()
	{   
	 	
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"banks", 
					   //"view_statuslist"=>$view_statuslist, 
					   //"activities"=>$activities, 
					   "pagination"=>create_pagination($pagination_options), 
					   "currencies"=>$this->common->getCurrency_(), 
					   "sources"=>$this->common->getSource_(), 
					   "types"=>$this->banks->getBankCategory_(),
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page
					   "s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - Settlement - Dashboard - ".$total_rows, 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('banks/bank_activities_tpl');
		$this->load->view('footer');   
		 
	}
	
	
	public function bankActivity()
	{   
		 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"banks",  
					   "currencies"=>$this->common->getCurrency_(), 
					   "sources"=>$this->common->getSource_(), 
					   "types"=>$this->banks->getBankCategory_(),
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page
					   "s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - Settlement - Manage Bank Activity - ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('popup/bank_popup_tpl',$data); 
		 
	} 
	 
	public function promotionsActivity()
	{    
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"Promotions",  
					   "currencies"=>$this->common->getCurrency_(), 
					   "sources"=>$this->common->getSource_(), 
					   "types"=>$this->banks->getBankCategory_(),
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page
					   "s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - Settlement - Manage Promotions Activity - ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('popup/promotions_popup_tpl',$data); 
		 
	} 
	
	
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */