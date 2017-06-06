<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

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
		//$this->access = array(11=>"4,11,12", 12=>"4,11,12"); //4:settlement, 11:set executive, 12:st supervisor 	
		$this->access = array(11=>"4,11,12"); //4:settlement, 11:set executive, 12:st supervisor 					  
	}
 	
	public function index()
	{    
		$this->usersList();    
	}
	 
	public function usersList()
	{    
		if(!admin_access() && !manage_user()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"manage",    
					   "results"=>$this->manage->getCallResultById_(array("result_status"=>'1')), 	
					   "status_list"=>$this->status_list, 
					   "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)), 
					   "currencies"=>$this->manage->getCurrencyAll_(array("Status ="=>1))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Users", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/users_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getUsersList($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !manage_user()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		   
		$search_string = "";   
		$search_arr = array(); 
		
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (a.mb_username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_nickname]))
		 {
			$search_string .= " AND (a.mb_nick LIKE '%".$this->common->escapeString_(trim($data[s_nickname]))."%') "; 
			$search_url .= "&s_nickname=".trim($data[s_nickname]); 
		 }
		
		if(trim($data[s_currency]))
		 { 
			$search_string .= " AND (FIND_IN_SET('".$this->common->escapeString_(trim($data[s_currency]))."', a.mb_currencies) ) ";
			$search_url .= "&s_currency=".trim($data[s_currency]); 
		 } 
		
		if(trim($data[s_usertype]))
		 {
			$search_string .= " AND (a.mb_usertype='".$this->common->escapeString_(trim($data[s_usertype]))."') ";  
			$search_url .= "&s_usertype=".trim($data[s_usertype]);   
		 }
		
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.mb_status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		
		//get the allowed usertype to change 
		if(!admin_only() && !admin_access() && !manage_user())
		 {
			 //$search_string .= " AND (a.mb_usertype='".$this->common->escapeString_(trim($this->access[$this->session->userdata("mb_usertype")]))."') ";   
			 $search_string .= " AND ( FIND_IN_SET(a.mb_usertype, '{$this->access[$this->session->userdata(mb_usertype)]}' ) 
			 						   AND a.mb_no<>{$this->session->userdata(mb_no)}    
									 ) ";  
		 }
		 
		if(!super_admin()) 
		 {
			$search_string .= " AND (a.mb_usertype <> 7) ";    
		 }
		
		 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "g4_member AS a", "a.mb_no")->TotalCount; 
	 	 
		$groups = $this->manage->getUsersList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"users":"user";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("groups"=>$groups, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
			$return = array("groups"=>$this->generateHtmlChatGroupsList($groups), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	
	public function generateHtmlChatGroupsList($users)
	{
		$return = "";  
		if(count($users))
		 { 		 
			foreach($users as $row=>$user){   
				$status = ($user->mb_status=='0' || $user->mb_status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$today_login = ($user->mb_today_login != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($user->mb_today_login)):'';
				$return .= "
						<tr class=\"group_row\" id=\"GroupRow{$group->GroupID}\" > 
							<!--<td class=\"center\" >".str_pad($user->mb_no,4,'0', STR_PAD_LEFT)."</td>-->
							<td class=\"center\" >{$user->mb_username}</td>
							<td class=\"center\" >{$user->mb_nick}</td>  
							<td class=\"center\" >{$user->UserType}</td> 
							<td >{$user->Currencies}</td> 
							<td class=\"center\" >{$today_login}</td>  
							<td class=\"center\" >{$user->UpdatedByNickname}</td>  
							<td class=\"center\" >{$status}</td>   
							<td class=\"center action\" >	
							";
				$return .= (manage_user())? "					 
								<a href=\"#UserModal\" title=\"edit user\" alt=\"edit user\" class=\"edit_user tip\" user-id=\"{$user->mb_no}\"  id=\"Edit{$user->mb_no}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>":"";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"20\" >No user found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageUser()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		
		//!admin_access() &&   
		if(!manage_user()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$user_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.mb_no =' => $user_id); 
		$user = ($user_id)?$this->manage->getUserById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
 					   "status_list"=>$this->status_list,  
					   "user"=>$user, 
					   "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)), 
					   "currencies"=>$this->manage->getCurrencyAll_(array("a.Status"=>1))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage User", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/user_popup_tpl',$data);  
		 
	} 
	
	
	public function manageUser()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		//!admin_access() && 
		if(!manage_user() ) 
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
			$check = ($this->input->post("hidden_auserid") == "")?$this->manage->getUserById_(array("a.mb_username ="=>$data[user_name], "a.mb_name ="=>"csa")):$this->manage->getUserById_(array("a.mb_username ="=>$data[user_name], "a.mb_no <>"=>$data[hidden_auserid], "a.mb_name ="=>"csa")); 
			
			if(count($check) > 0) $error .= "Username is already used by other user!<br>";	 
		 }
		 
		if($this->input->post("hidden_auserid") == "") 
		 {
			if($data[user_password] == "")
			 {
				 $error .= "Enter password!<br> ";
			 }
			 
		 }
		else
		 {
			 
		 }
		 
		if($data[user_nickname] == "")
		 {
			 $error .= "Enter nickname!<br>";
		 }
		
		if($data[user_email] == "")
		 {
			 $error .= "Enter email address!<br>";
		 }
		else
		 {
			$check = ($this->input->post("hidden_auserid") == "")?$this->manage->getUserById_(array("a.mb_email ="=>$data[user_email], "a.mb_name ="=>"csa")):$this->manage->getUserById_(array("a.mb_email ="=>$data[user_email], "a.mb_no <>"=>$data[hidden_auserid], "a.mb_name ="=>"csa")); 
			
			if(count($check) > 0) $error .= "Email is already used by other user!<br>";	 
		 }
		 
		if($data[user_idno] == "")
		 {
			 $error .= "Enter ID No.!<br>";
		 }
		else
		 {
			
			$check = ($this->input->post("hidden_auserid") == "")?$this->manage->getUserById_(array("a.mb_id ="=>$data[user_idno], "a.mb_name ="=>"csa")):$this->manage->getUserById_(array("a.mb_id ="=>$data[user_idno], "a.mb_no <>"=>$data[hidden_auserid], "a.mb_name ="=>"csa")); 
			
			if(count($check) > 0) $error .= "ID No. is already used by other user!<br>"; 
		 }
		
		//use in internal system 
		if(trim($data[user_internal]) == "")
		 {
			 //$error .= "Enter Internal System username!<br>";
		 }
		else
		 {
			$check = ($this->input->post("hidden_auserid") == "")?$this->manage->getUserById_(array("a.mb_internal_user ="=>trim($data[user_internal]), "a.mb_name ="=>"csa")):$this->manage->getUserById_(array("a.mb_internal_user ="=>trim($data[user_internal]), "a.mb_no <>"=>$data[hidden_auserid], "a.mb_name ="=>"csa")); 
			
			if(count($check) > 0) $error .= "Internal System Username is already used by other user!<br>";	 
		 }
		
		   
		if($data[user_type] == "")
		 {
			 $error .= "Select user type!<br>";
		 }
		 
		 if($data[user_level] == "")
		 {
			 $error .= "Select level!<br>";
		 }
		 
		if($data[user_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		  
		$user_currencies = implode(',', $this->input->post("user_cur"));   
		
		if($user_currencies == "")
		 {
			 $error .= "Select currency for user!<br>";
		 }
		 
		if($error)
		 { 	
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 	
			$action = ($this->input->post('hidden_action')=="add")?"add":"update";
			$current_date = date("Y-m-d H:i:s");
			$name = "csa"; //
			if($action == "add")
			 {	
			 	 
			 	$post_data = array(  
					'mb_id'=>$data['user_idno'], 
					'mb_username'=>$data['user_name'], 
					'mb_internal_user'=>$data['user_internal'], 
					'mb_password'=>$data['user_password'], 
					'mb_name'=>$name,
					'mb_nick'=>$data['user_nickname'], 
					'mb_email'=>$data['user_email'], 
					'mb_currencies'=>$user_currencies, 
					'mb_usertype'=>$data['user_type'], 
					'mb_level'=>$data['user_level'], 
					'mb_status'=>$data['user_status'],
					'mb_datetime'=>$current_date, 
					'mb_ip'=>$this->input->ip_address(), 
					'mb_updatedby'=>$this->session->userdata('mb_no'),
					'mb_email_certify'=>$current_date //last updated datetime
				 );   
				 
				$last_id = $this->manage->manageSettings_("g4_member", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"User added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding user!");
				 }   
				 
			 }
			else
			 {     
				 $post_data = array(      
					'mb_id'=>$data['user_idno'], 
					'mb_username'=>$data['user_name'], 
					'mb_internal_user'=>$data['user_internal'],    
					'mb_nick'=>$data['user_nickname'], 
					'mb_email'=>$data['user_email'], 
					'mb_currencies'=>$user_currencies, 
					'mb_usertype'=>$data['user_type'], 
					'mb_level'=>$data['user_level'], 
					'mb_status'=>$data['user_status'], 
					'mb_updatedby'=>$this->session->userdata('mb_no'), 
					'mb_email_certify'=>$current_date
					
				 );  
				 
				 if($this->input->post('user_password')) $post_data['mb_password'] = $this->input->post('user_password');
				 
				 $changes = ""; 
				 
				  
				 $conditions_array = array('a.mb_no ='=>$data[hidden_auserid]);  
				 $old = $this->manage->getUserById_($conditions_array);	
				 
				 $changes .= ($data['user_name'] != $old->mb_username)?"Username changed to ".$data['user_name']." from ".$old->mb_username."|||":""; 
				 $changes .= ($data['user_internal'] != $old->mb_internal_user)?"Internal Username changed to ".$data['user_internal']." from ".$old->mb_internal_user."|||":""; 
				 $changes .= ($this->input->post('user_password'))?"Password changed to ".$data['user_password']:""; 
				 $changes .= ($data['user_nickname'] != $old->mb_nick)?"Nickname changed to ".$data['user_nickname']." from ".$old->mb_nick."|||":"";   
				 $changes .= ($data['user_email'] != $old->mb_email)?"Email changed to ".$data['user_email']." from ".$old->mb_email."|||":"";   
				 $changes .= ($data['user_idno'] != $old->mb_id)?"ID No. changed to ".$data['user_idno']." from ".$old->mb_id."|||":"";   
				 $changes .= ($user_currencies != $old->mb_currencies)?"Currencies changed to ".$user_currencies." from ".$old->mb_currencies."|||":""; //change to full name
				 $changes .= ($data['user_type'] != $old->mb_usertype)?"User Type changed to ".$data['hidden_atype']." from ".$old->UserTypeName."|||":"";      
				 $changes .= ($data['user_level'] != $old->mb_level)?"User Level changed to ".$data['user_level']." from ".$old->mb_level."|||":"";      
				 $changes .= ($data['user_status'] != $old->mb_status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("g4_member", $post_data, $action, "mb_no", $this->input->post("hidden_auserid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"User updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						$return = array("success"=>0, "message"=>"Error updating user!");
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
	
	public function createCustomChatGroup()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!$this->session->userdata('mb_no') || !$this->session->userdata('mb_session_key'))
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Login required.", "403");
		 	return false; 
		 }
		
		if(!admin_access() && !manage_user()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false;  
		 }
		 
		 
		$conditions_array = array('a.Status ='=>'1' ); 
		$groups = $this->manage->createCustomChatGroup_($conditions_array); 
		
		echo json_encode($groups);
		 
	}  
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */