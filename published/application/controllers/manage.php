<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller {

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
		$this->load->model("manage_model","manage");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")); 
	}
 	
	public function index()
	{   
		redirect(base_url()."manage/users");    
	}
	
	public function suggestionTypes()
	{   
		redirect(base_url()."suggestions/types");    
	} 
	
	public function managePromotions()
	{   
		redirect(base_url()."promotions/manage-promotions");    
	} 
	
	
	public function popupChangePassword()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		  
		//for promotions
		/*if(count($activity) > 0)
		 {
			$where_arr = array("a.Status ="=>'1', 
							   "a.ProductID"=>$activity->Product, 
							   "a.CurrencyID"=>$activity->Currency
							  );  
			$where_or = array("a.PromotionID"=>$activity->Promotion);  
		 } */
		 
		 //$promotions = $this->promotions->getChangePromotionById_($where_arr, $where_or);   
		 //end for promotions
		
		
		
		$data2 = array("main_page"=>"manage"   
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Change Password ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('common/change_password_popup_tpl',$data); 
		 
	} 
	
	public function changePassword()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s");
		
		$error =  ""; 		 
		
		if(strlen($data[password_new]) < 5)
		 {
			 $error .= "Password should be minimum of five characters!<br> ".strlen($data[password_new]);
		 }
		
		if(strlen($data[password_new]) > 20)
		 {
			 $error .= "Password should be maximum of 20 characters!<br> ";
		 }
		
		if($data[password_new] != $data[password_confirm])
		 {
			$error .= "Password did not match!<br> "; 
		 }
		 
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {     
			$post_data = array(      
				'mb_password'=>$data['password_new'], 
				'mb_email_certify'=>$current_date 
			 );    
			 
			$x = $this->manage->manageSettings_("g4_member", $post_data, "update", "mb_no", $this->session->userdata('mb_no'));	  
			
			if($x > 0)
			 {  
				$return = array("success"=>1, "message"=>"Password successfully changed.", "is_change"=>1);   
			 }
			else
			 { 
				 $return = array("success"=>0, "message"=>"Error changing password!");
			 } 
			 
		 }
		
		
		echo json_encode($return);
		 
		 
	}
  	
	
	public function popupChangeInternalUsername()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		
		if($this->config->item('change_internal_username') != TRUE)
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Function not available.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		
		$data2 = array("main_page"=>"manage"   
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Change Internal System Username ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('common/change_internal_username_popup_tpl',$data); 
		 
	}   
	
	public function changeInternaUsername()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		
		if($this->config->item('change_internal_username') != TRUE)
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Function not available.", "403");
		 	return false; 
		 } 
		   
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s");
		
		$error =  ""; 		 
		
		//use in internal system 
		if(trim($data[user_internal]))
		 {
			 //$error .= "Enter Internal System username!<br>";
			 $check = $this->manage->getUserById_(array("a.mb_internal_user ="=>trim($data[user_internal]), "a.mb_no <>"=>$this->session->userdata('mb_no') ));  
			 if(count($check) > 0) $error .= "Internal System Username is already used by other user!<br>";
		 }
		else
		 {
				 
		 }
		 
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {     
			$post_data = array(      
				'mb_internal_user'=>(trim($data[user_internal])), 
				'mb_email_certify'=>$current_date 
			 );    
			
			//internal username can be empty 
			$x = (trim($data[user_internal]))?$this->manage->manageSettings_("g4_member", $post_data, "update", "mb_no", $this->session->userdata('mb_no')):1;	  
			
			if($x > 0)
			 {  
			 	$this->session->set_userdata("mb_internal_user", trim($data[user_internal])); 
				
				$return = array("success"=>1, "message"=>"Internal System username successfully changed.", "is_change"=>1);    
			 }
			else
			 { 
				 $return = array("success"=>0, "message"=>"Error changing Internal System username!");
			 } 
			 
		 }
		
		
		echo json_encode($return);
		 
		 
	}	 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */