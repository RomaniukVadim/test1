<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_12Bet extends MY_Controller {

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
		$this->load->model("users_12bet_model","users");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active"));   
	}
 	
	public function index()
	{    
		$this->users12betList();    
	}
	 
	public function users12betList()
	{    
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  					
		$data2 = array("main_page"=>"12bet_users",      	
					   "status_list"=>$this->status_list, 
					   "currencies"=>$this->common->getCurrency_() 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Users", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('users_12bet/users_12bet_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getUsers12betList()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		   
		$search_string = "";   
		$search_arr = array(); 
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.UserID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (a.UserName LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
		 }
		 
		if(trim($data[s_systemid]))
		 {
			$search_string .= " AND (a.SystemID LIKE '%".$this->common->escapeString_(trim($data[s_systemid]))."%') "; 
			$search_url .= "&s_systemid=".trim($data[s_systemid]); 
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (a.Currency='".$this->common->escapeString_($data[s_currency])."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 }
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_12bet_users AS a", "a.UserID")->TotalCount; 
	 	 
		$users = $this->users->getUsers12BetList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"status":"status";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("status"=>$currencies, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("status"=>$this->generateHtmlUsers12betList($users), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlUsers12betList($users)
	{
		$return = ""; 
		if(count($users))
		 { 
			foreach($users as $row=>$user){   
				$users = ($user->Users_12Bet=='0' || $user->Users_12Bet=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($user->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($user->DateUpdated)):'';
				$return .= "
						<tr class=\"user_row\" id=\"Users_12BetRow{$result->CurrencyID}\" > 
							<td class=\"center\" >{$user->Username}</td>
							<td class=\"center\" >{$user->SystemID}</td>   
							<td class=\"center\" >{$user->CurrencyName}</td>  
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CurrencyModal\" title=\"edit user\" alt=\"edit user\" class=\"edit_user tip\" user-id=\"{$user->UserID}\"  id=\"Edit{$user->UserID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No 12BET user found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageUsers12bet()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$user_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.UserID =' => $user_id); 
		$user = ($user_id)?$this->users->getUser12BetById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "currencies"=>$this->common->getCurrency_(), 
					   "user"=>$user
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage 12BET User", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('users_12bet/users_12bet_popup_tpl',$data);  
		 
	} 
	
	
	public function manageUser12Bet()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		 
		   
		$error = "";   
		$data = $this->input->post();   
		
		if($data[user_name] == "")
		 {
			 $error .= "Enter username!<br>";
		 }
		else
		 {
			$cusername_array = array('a.Username ='=>trim($data[user_name])); 
			if(trim($data[hidden_auserid]) > 0)$cusername_array["a.UserID <>"] = trim($data[hidden_auserid]); 
			$check_username = $this->users->getUser12BetById_($cusername_array);	
			if(count($check_username) > 0)$error .= "Username already exists. Please check!<br>"; 
		 }
		 
		if($data[user_systemid] == "")
		 {
			 $error .= "Enter System ID!<br>";
		 }  
		else
		 {
			$csystem_array = array('a.SystemID ='=>trim($data[user_systemid]) );  
			if(trim($data[hidden_auserid]) > 0)$csystem_array["a.UserID <>"] = trim($data[hidden_auserid]); 	
			$check_systemid = $this->users->getUser12BetById_($csystem_array);	
			if(count($check_systemid) > 0)$error .= "SystemID already exists. Please check!<br>"; 	 
		 }
		 
		if($data[user_currency] == "")
		 {
			 $error .= "Select currency!<br>";
		 } 
		 
		if($data[user_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		
		 
		if($error)
		 { 	
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 
			$action = ($this->input->post('hidden_action')=="add")?"add":"update";
			$current_date = date("Y-m-d H:i:s"); 
			 
			if($action == "add")
			 {	  
			 	$post_data = array(   
					'Username'=>trim($data['user_name']),   
					'Currency'=>trim($data['user_currency']), 
					'SystemID'=>trim($data['user_systemid']),   
					'AddedBy'=>$this->session->userdata('mb_no'), 
					'DateAdded'=>$current_date,    
					'UpdatedBy'=>$this->session->userdata('mb_no'), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['user_status'] 
				 );   
				 
				$last_id = $this->users->manageUser12Bet_("csa_12bet_users", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"12BET User added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding 12BET User!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Username'=>trim($data['user_name']),   
					'Currency'=>trim($data['user_currency']), 
					'SystemID'=>trim($data['user_systemid']),  
					'UpdatedBy'=>$this->session->userdata('mb_no'), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['user_status'] 
				 );  
				        
				 $changes = ""; 
				  
				 $conditions_array = array('a.UserID ='=>trim($data[hidden_auserid]));  
				 $old = $this->users->getUser12BetById_($conditions_array);	
				  
				 $changes .= ($data['user_name'] != $old->Username)?"Username changed to ".$data['user_name']." from ".$old->Username."|||":"";  
				 $changes .= ($data['user_systemid'] != $old->SystemID)?"System ID changed to ".$data['user_systemid']." from ".$old->SystemID."|||":"";  
				 $changes .= ($data['user_currency'] != $old->Currency)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":"";    
				 $changes .= ($data['user_status'] != $old->Status)?"Stutus changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->users->manageUser12Bet_("csa_12bet_users", $post_data, $action, "UserID", $this->input->post("hidden_auserid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"12BET User updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating 12BET User!");
					 }  
				  }
				 else
				  { 
					//no changes    
					$return = array("success"=>1, "message"=>"No changes made!");
				  }
				
			 }//end else UPDATE
			  
		 }//end else NO ERROR
		 
		 echo json_encode($return);
		
	} 
	
	
	public function searchActivities()
	{    
		if(!view_access() && !admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$skeywords = trim($this->uri->segment(3));
		  					
		$data2 = array("main_page"=>"12bet_users",      	
					   //"status_list"=>$this->status_list, 
					   //"currencies"=>$this->common->getCurrency_(), 
					   "page_ajax"=>1, 
					   "skeywords"=>$skeywords
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Users Activities", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('users_12bet/users_12bet_search_tpl');
		$this->load->view('footer');   
	}
	
	public function get12betUserByName()
	{
		$where_arr = array();
		$data = $this->input->post();  
		//ISNULL(AS)
		
		if(trim($data[s_username]) == "")
		 {
			$error .= "Enter 12BET Username!<br>";
		 }
		else
		 {
			 if($data[s_username]) $where_arr["a.Username"] = trim($data[s_username]);
		 } 
		
		$results = $this->users->getUser12BetById_($where_arr);   
  		
		if(count($results) > 0) 
		 {  
		 	 $can_view_act = (in_array($results->Currency, explode(',', $this->session->userdata(mb_currencies))))?1:0;
			 $return = array("success"=>1, "user"=>$results, "message"=>"User found!", "can_view_act"=>$can_view_act);
		 } 
		else
		 {
			$return = array("success"=>0, "message"=>"12BET Username not found. Please add to record!"); 
		 }
		 
		 
		echo  json_encode($return); 
	}	 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */