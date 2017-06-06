<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat_Groups extends MY_Controller {

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
		$this->chatGroupsList();    
	}
	 
	public function chatGroupsList()
	{    
		if(!admin_only()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"manage",    
					   "results"=>$this->manage->getCallResultById_(array("result_status"=>'1')), 	
					   "status_list"=>$this->status_list, 
					   "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)) 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Chat Groups", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/chat_groups_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getChatGroupsList($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_only()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		   
		$search_string = "";   
		$search_arr = array(); 
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.GroupID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		
		if(trim($data[s_usertype]))
		 {
			$search_string .= " AND (a.UserTypes LIKE '%".$this->common->escapeString_(trim($data[s_usertype]))."%') ";  
			
			$search_string .= " AND (FIND_IN_SET('".$this->common->escapeString_(trim($data[s_usertype]))."', a.UserTypes) ) "; 
			$search_url .= "&s_usertype=".trim($data[s_usertype]);   
		 }
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
		
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_chat_group AS a", "a.GroupID")->TotalCount; 
	 	 
		$groups = $this->manage->getChatGroupsList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"groups":"group";
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
	
	
	public function generateHtmlChatGroupsList($groups)
	{
		$return = "";  
		if(count($groups))
		 { 		 
			foreach($groups as $row=>$group){   
				$status = ($group->Status=='0' || $group->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($group->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($group->DateUpdated)):'';
				$return .= "
						<tr class=\"group_row\" id=\"GroupRow{$group->GroupID}\" > 
							<td class=\"center\" >".str_pad($group->GroupID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$group->Name}</td>  
							<td >{$group->UserGroupTypes}</td> 
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#GroupChatModal\" title=\"edit group\" alt=\"edit group\" class=\"edit_group tip\" group-id=\"{$group->GroupID}\"  id=\"Edit{$group->GroupID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"6\" >No custom chat group found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageChatGroup()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_only()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$group_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.GroupID =' => $group_id); 
		$group = ($group_id)?$this->manage->getChatGroupById_($conditions_array):""; 
		
		//if($group->SpecificUsers)
		//$agents = ($group->SpecificUsers)?$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa")):
		
		$data2 = array("main_page"=>"manage",     
 					   "status_list"=>$this->status_list,  
					   "group"=>$group, 
					   "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)), 
					   "currencies"=>$this->manage->getCurrencyAll_(array("a.Status"=>1))
					  // "agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa"))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Chat Group", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/chat_group_popup_tpl',$data);  
		 
	} 
	 
	 
	public function manageChatGroup()
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
		 
		if($data[group_name] == "")
		 {
			 $error .= "Enter group name!<br>";
		 }
		
		/*if($data[group_usertypes] == "")
		 {
			 $error .= "Select users!<br>";
		 }*/
		 
		if($data[group_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		 
		$user_types = implode(',', $data[group_type]); 
		$specific_users = implode(',', $data[group_specific]);   
		$specific_users_txt = (trim($specific_users))?$specific_users:"*** All ***";
		
		$specific_users .= '';
		$user_types .= '';
		
		if( (count($data[group_specific]) <= 0) && (count($data[group_type]) <= 0) )
		 {
			 $error .= "Select participants!<br>";
		 }
		  
		/*if($user_types == "" && $specific_users=="")
		 {
			 $error .= "Select user types or specific users!<br>";
		 }*/
		 
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
					'Name'=>$data['group_name'], 
					'Description'=>$data['group_desc'],
					'UserTypes'=>$user_types, 
					'Currency'=>$data['group_currency'],
					'SpecificUsers'=>$specific_users,
					'AddedBy'=>$this->session->userdata("mb_no"),
					'DateAdded'=>$current_date, 
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date, 
					'Status'=>$data['group_status']
				 );   
				 
				$last_id = $this->manage->manageSettings_("csa_chat_group", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Chat group added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding chat group!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['group_name'], 
					'Description'=>$data['group_desc'],
					'UserTypes'=>$user_types,   
					'Currency'=>$data['group_currency'], 
					'SpecificUsers'=>$specific_users,
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date, 
					'Status'=>$data['group_status']
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.GroupID ='=>$data[hidden_agroupid]);  
				 $old = $this->manage->getChatGroupById_($conditions_array);	
				 $old_specific_users = ($old->SpecificUsers)?$old->SpecificUsers:"*** All ****"; 
				 
				 $changes .= ($data['group_name'] != $old->Name)?"Group Name changed to ".$data['group_name']." from ".$old->Name."|||":""; 
				 $changes .= ($data['group_desc'] != $old->Description)?"Description changed to ".$data['group_desc']." from ".$old->Description."|||":"";   
				 $changes .= ($user_types != $old->UserTypes)?"User Types changed to ".$user_types." from ".$old->UserTypes."|||":""; 
				 
				 $changes .= ($specific_users != $old->SpecificUsers)?"Specific Users changed to ".$specific_users_txt." from ".$old_specific_users."|||":""; 
				 
				 $changes .= ($data['group_currency'] != $old->Currency)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":"";     
				 $changes .= ($data['group_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_chat_group", $post_data, $action, "GroupID", $this->input->post("hidden_agroupid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Chat group updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating chat group!");
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
		    
		$conditions_array = array('a.Status ='=>'1' ); 
		$groups = $this->manage->createCustomChatGroup_($conditions_array); 
		$chat = array();
		$currencies = explode(',', $this->session->userdata('mb_currencies')); 
		foreach($groups as $row=>$group){
			if($group->Currency <= 0)
			 {
				 array_push($chat, $group);
			 }
			else
			 {
				if(in_array($group->Currency, $currencies)) array_push($chat, $group);
			 }
		}
		
		echo json_encode($chat);
		 
	}  
	
	public function getAgentList()
	{
		
		$where_arr = array("a.mb_status ="=>'1');
		$data = $this->input->post();   
		$result = implode(',', $data['group_type']);
		
		//if($result) $where_arr["FIND_IN_SET(a.mb_usertype, '{$result}') !="] = 0;
		if($data[group_currency]) $where_arr["FIND_IN_SET('{$data[group_currency]}', a.mb_currencies) !="] = 0;
		//if($data[group_currency]) $where_arr[] = array("FIND_IN_SET('{$data[group_currency]}', a.mb_currencies) !="=>0 );  
		//$this->db->where("FIND_IN_SET(a.mb_usertype, '{$data[result]}') !=", '0'); 
		
		$results = $this->common->getUserAll_($where_arr, $order_by=array("a.mb_usertype"=>"ASC"));   
 		 
		echo  json_encode($results); 
	} 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */